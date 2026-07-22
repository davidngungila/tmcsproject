<?php

namespace App\Console\Commands;

use App\Models\Contribution;
use App\Services\FeedTanEcommerceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('payments:check-status')]
#[Description('Check and update status of all pending FeedTan payments')]
class CheckAllPaymentsStatus extends Command
{
    protected $name = 'payments:check-status';
    /**
     * Execute the console command.
     */
    public function handle(FeedTanEcommerceService $feedTanService)
    {
        $this->info('Checking all pending FeedTan payments...');

        // Get all unverified contributions with FeedTan order reference (latest 5)
        $contributions = Contribution::where('is_verified', false)
            ->whereNotNull('feedtan_order_reference')
            ->whereIn('feedtan_status', ['PROCESSING', 'PENDING'])
            ->latest()
            ->limit(5)
            ->get();

        $this->info("Found {$contributions->count()} pending payments to check.");

        $updated = 0;
        $verified = 0;
        $failed = 0;

        foreach ($contributions as $contribution) {
            $this->info("Checking contribution #{$contribution->id} - {$contribution->receipt_number}");

            try {
                $statusResponse = $feedTanService->checkPaymentStatus($contribution->feedtan_order_reference);

                if (!$statusResponse['success']) {
                    $this->error("Failed to check status for contribution #{$contribution->id}: {$statusResponse['error']}");
                    continue;
                }

                $paymentData = $statusResponse['data'];
                $isPaid = $paymentData['is_paid'] ?? false;
                $status = $paymentData['status'] ?? null;

                // Update without transaction to avoid lock issues
                try {
                    $contribution->update([
                        'feedtan_status' => $status,
                        'feedtan_payment_method' => $paymentData['payment_method'] ?? null,
                    ]);

                    if ($isPaid && in_array($status, ['SUCCESS', 'SETTLED']) && !$contribution->is_verified) {
                        $contribution->update([
                            'is_verified' => true,
                            'verified_at' => now(),
                            'verified_by' => 1,
                            'feedtan_paid_at' => $paymentData['paid_at'] ?? now(),
                        ]);

                        // Create accounting entries
                        $this->createAccountingEntries($contribution);

                        $this->info("✓ Contribution #{$contribution->id} VERIFIED - Status: {$status}");
                        $verified++;
                    } elseif (in_array($status, ['FAILED', 'DECLINED', 'CANCELLED'])) {
                        $contribution->update([
                            'feedtan_error_reason' => 'Payment ' . strtolower($status),
                        ]);
                        $this->warn("✗ Contribution #{$contribution->id} FAILED - Status: {$status}");
                        $failed++;
                    } else {
                        $this->info("○ Contribution #{$contribution->id} still pending - Status: {$status}");
                    }

                    $updated++;
                } catch (\Exception $e) {
                    $this->error("Failed to update contribution #{$contribution->id}: {$e->getMessage()}");
                }
            } catch (\Exception $e) {
                $this->error("Exception checking contribution #{$contribution->id}: {$e->getMessage()}");
            }

            // Add delay to reduce lock contention
            sleep(1);
        }

        $this->info('');
        $this->info('Summary:');
        $this->info("  Total checked: {$contributions->count()}");
        $this->info("  Updated: {$updated}");
        $this->info("  Verified: {$verified}");
        $this->info("  Failed: {$failed}");
        $this->info('Payment status check completed.');

        return Command::SUCCESS;
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
