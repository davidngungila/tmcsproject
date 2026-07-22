<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Handle FeedTan payment callback
     */
    public function feedTanCallback(Request $request)
    {
        Log::info('FeedTan callback received', $request->all());

        try {
            $data = $request->input('data');
            $orderReference = $data['order_reference'] ?? null;
            $isPaid = $data['is_paid'] ?? false;
            $status = $data['status'] ?? null;

            if (!$orderReference) {
                Log::error('FeedTan callback missing order_reference');
                return response()->json(['success' => false, 'message' => 'Missing order reference'], 400);
            }

            // Find contribution by feedtan_order_reference
            $contribution = Contribution::where('feedtan_order_reference', $orderReference)->first();

            if (!$contribution) {
                Log::warning('FeedTan callback: Contribution not found', ['order_reference' => $orderReference]);
                return response()->json(['success' => false, 'message' => 'Contribution not found'], 404);
            }

            // Update contribution with callback data
            DB::beginTransaction();
            try {
                $contribution->update([
                    'feedtan_status' => $status,
                    'feedtan_payment_method' => $data['payment_method'] ?? null,
                ]);

                // If payment is successful, verify the contribution
                if ($isPaid && in_array($status, ['SUCCESS', 'SETTLED'])) {
                    if (!$contribution->is_verified) {
                        $contribution->update([
                            'is_verified' => true,
                            'verified_at' => now(),
                            'verified_by' => 1, // System user
                            'feedtan_paid_at' => $data['paid_at'] ?? now(),
                        ]);

                        // Create accounting entries
                        $this->createAccountingEntries($contribution);

                        // Send notifications
                        $this->sendContributionNotifications($contribution);

                        Log::info('FeedTan callback: Contribution verified', [
                            'contribution_id' => $contribution->id,
                            'order_reference' => $orderReference,
                            'amount' => $contribution->amount
                        ]);
                    }
                } elseif (in_array($status, ['FAILED', 'DECLINED', 'CANCELLED'])) {
                    Log::warning('FeedTan callback: Payment failed', [
                        'contribution_id' => $contribution->id,
                        'status' => $status
                    ]);
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Callback processed successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('FeedTan callback processing failed', [
                    'order_reference' => $orderReference,
                    'error' => $e->getMessage()
                ]);
                return response()->json(['success' => false, 'message' => 'Processing failed'], 500);
            }
        } catch (\Exception $e) {
            Log::error('FeedTan callback exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    /**
     * Create accounting entries for verified contribution
     */
    protected function createAccountingEntries(Contribution $contribution)
    {
        $debitAccountCode = '1100'; // Bank account for FeedTan payments
        $debitAccount = \App\Models\Account::where('code', $debitAccountCode)->first();

        $creditAccountCode = match ($contribution->contribution_type) {
            'Tithe' => '4000',
            'Offering' => '4100',
            'Special' => '4200',
            'Harvest' => '4300',
            default => '4900',
        };
        $creditAccount = \App\Models\Account::where('code', $creditAccountCode)->first();

        if (!$debitAccount || !$creditAccount) {
            Log::error('Accounting accounts not found for FeedTan callback', [
                'debit' => $debitAccountCode,
                'credit' => $creditAccountCode
            ]);
            return;
        }

        // Create debit entry
        \App\Models\LedgerEntry::create([
            'account_id' => $debitAccount->id,
            'transaction_date' => $contribution->contribution_date,
            'description' => "FeedTan Payment (Callback): {$contribution->receipt_number} - {$contribution->member->full_name}",
            'debit' => $contribution->amount,
            'credit' => 0,
            'reference_type' => 'Contribution',
            'reference_id' => $contribution->id,
            'recorded_by' => 1, // System user
        ]);

        // Create credit entry
        \App\Models\LedgerEntry::create([
            'account_id' => $creditAccount->id,
            'transaction_date' => $contribution->contribution_date,
            'description' => "FeedTan Payment (Callback): {$contribution->receipt_number} - {$contribution->member->full_name}",
            'debit' => 0,
            'credit' => $contribution->amount,
            'reference_type' => 'Contribution',
            'reference_id' => $contribution->id,
            'recorded_by' => 1, // System user
        ]);

        // Update account balances
        $debitAccount->increment('balance', $contribution->amount);
        $creditAccount->increment('balance', $contribution->amount);
    }

    /**
     * Send notifications for verified contribution
     */
    protected function sendContributionNotifications(Contribution $contribution)
    {
        $member = $contribution->member;
        $amount = number_format($contribution->amount, 0);
        $type = ucfirst(str_replace('_', ' ', $contribution->contribution_type));
        
        // Send SMS (Queued)
        if ($member->phone) {
            \App\Jobs\SendSmsJob::dispatch(
                $member->phone,
                "Dear {$member->full_name}, your contribution of TZS {$amount} for {$type} has been confirmed. Receipt: {$contribution->receipt_number}. Thank you!"
            );
        }

        // Send Email with PDF (Queued)
        if ($member->email) {
            \Illuminate\Support\Facades\Mail::to($member->email)
                ->queue(new \App\Mail\ContributionReceiptMailable($contribution));
        }
    }
}

