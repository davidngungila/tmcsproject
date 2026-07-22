<?php

namespace App\Jobs;

use App\Models\Contribution;
use App\Services\FeedTanEcommerceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InitiateFeedTanPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contributionId;
    protected $paymentData;

    public function __construct($contributionId, array $paymentData)
    {
        $this->contributionId = $contributionId;
        $this->paymentData = $paymentData;
    }

    public function handle(FeedTanEcommerceService $feedTanService)
    {
        $contribution = Contribution::find($this->contributionId);
        
        if (!$contribution) {
            Log::error('Contribution not found for payment initiation', ['id' => $this->contributionId]);
            return;
        }

        try {
            $feedTanResponse = $feedTanService->initiatePayment($this->paymentData);

            if (!$feedTanResponse['success']) {
                $contribution->update([
                    'feedtan_status' => 'FAILED',
                    'feedtan_error_reason' => $feedTanResponse['error'] ?? 'Payment initiation failed',
                ]);
                Log::error('FeedTan payment initiation failed', [
                    'contribution_id' => $contribution->id,
                    'error' => $feedTanResponse['error'] ?? 'Unknown error',
                ]);
                return;
            }

            // Update contribution with FeedTan references
            $contribution->update([
                'feedtan_order_reference' => $feedTanResponse['data']['order_reference'],
                'feedtan_transaction_id' => $feedTanResponse['data']['transaction_id'],
                'feedtan_status' => $feedTanResponse['data']['status'],
                'feedtan_error_reason' => null,
            ]);

            // Dispatch job to poll payment status
            CheckFeedTanPaymentStatus::dispatch($contribution->id)->delay(now()->addSeconds(30));

            Log::info('FeedTan payment initiated successfully', [
                'contribution_id' => $contribution->id,
                'order_reference' => $feedTanResponse['data']['order_reference'],
            ]);

        } catch (\Exception $e) {
            $contribution->update([
                'feedtan_status' => 'FAILED',
                'feedtan_error_reason' => 'Exception: ' . $e->getMessage(),
            ]);
            Log::error('FeedTan payment initiation exception', [
                'contribution_id' => $contribution->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
