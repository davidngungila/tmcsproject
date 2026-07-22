<?php

namespace App\Jobs;

use App\Models\Contribution;
use App\Services\FeedTanEcommerceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckFeedTanPaymentStatus implements ShouldQueue
{
    use Queueable;

    public $tries = 10;
    public $backoff = [30, 60, 120, 300, 600]; // Retry intervals in seconds

    protected $contributionId;

    /**
     * Create a new job instance.
     */
    public function __construct($contributionId)
    {
        $this->contributionId = $contributionId;
    }

    /**
     * Execute the job.
     */
    public function handle(FeedTanEcommerceService $feedTanService): void
    {
        $contribution = Contribution::find($this->contributionId);

        if (!$contribution || !$contribution->feedtan_order_reference) {
            Log::warning('FeedTan status check: Contribution not found or missing order reference', [
                'contribution_id' => $this->contributionId
            ]);
            return;
        }

        // Check if already verified
        if ($contribution->is_verified) {
            Log::info('FeedTan status check: Contribution already verified', [
                'contribution_id' => $this->contributionId
            ]);
            return;
        }

        // Check payment status
        $statusResponse = $feedTanService->checkPaymentStatus($contribution->feedtan_order_reference);

        if (!$statusResponse['success']) {
            Log::error('FeedTan status check failed', [
                'contribution_id' => $this->contributionId,
                'error' => $statusResponse['error'] ?? 'Unknown error'
            ]);
            
            // Retry if this is a temporary failure
            if ($this->attempts < $this->tries) {
                $this->release($this->backoff[min($this->attempts, count($this->backoff) - 1)]);
            }
            return;
        }

        $paymentData = $statusResponse['data'];
        $isPaid = $paymentData['is_paid'] ?? false;
        $status = $paymentData['status'] ?? null;

        // Update contribution with latest status
        DB::beginTransaction();
        try {
            $contribution->update([
                'feedtan_status' => $status,
                'feedtan_payment_method' => $paymentData['payment_method'] ?? null,
            ]);

            // If payment is successful, verify the contribution
            if ($isPaid && in_array($status, ['SUCCESS', 'SETTLED'])) {
                $contribution->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                    'verified_by' => 1, // System user
                    'feedtan_paid_at' => $paymentData['paid_at'] ?? now(),
                ]);

                // Create accounting entries
                $this->createAccountingEntries($contribution);

                Log::info('FeedTan payment verified successfully', [
                    'contribution_id' => $this->contributionId,
                    'order_reference' => $contribution->feedtan_order_reference,
                    'amount' => $contribution->amount
                ]);
            } elseif (in_array($status, ['FAILED', 'DECLINED', 'CANCELLED'])) {
                Log::warning('FeedTan payment failed', [
                    'contribution_id' => $this->contributionId,
                    'status' => $status
                ]);
                // Don't retry for failed payments
                return;
            } else {
                // Still pending, retry later
                if ($this->attempts < $this->tries) {
                    $this->release($this->backoff[min($this->attempts, count($this->backoff) - 1)]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FeedTan status update failed', [
                'contribution_id' => $this->contributionId,
                'error' => $e->getMessage()
            ]);
            
            if ($this->attempts < $this->tries) {
                $this->release($this->backoff[min($this->attempts, count($this->backoff) - 1)]);
            }
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
            Log::error('Accounting accounts not found for FeedTan payment', [
                'debit' => $debitAccountCode,
                'credit' => $creditAccountCode
            ]);
            return;
        }

        // Create debit entry
        \App\Models\LedgerEntry::create([
            'account_id' => $debitAccount->id,
            'transaction_date' => $contribution->contribution_date,
            'description' => "FeedTan Payment: {$contribution->receipt_number} - {$contribution->member->full_name}",
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
            'description' => "FeedTan Payment: {$contribution->receipt_number} - {$contribution->member->full_name}",
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
}

