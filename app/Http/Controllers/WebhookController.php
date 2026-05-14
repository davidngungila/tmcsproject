<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Snipe Payment Webhooks
     */
    public function handleSnipe(Request $request)
    {
        $payload = $request->all();
        $eventType = $request->header('X-Webhook-Event');

        Log::info('Snipe Webhook Received', [
            'event' => $eventType,
            'payload' => $payload,
        ]);

        if ($eventType === 'payout.completed') {
            $this->processCompletedPayment($payload['data']);
        } elseif ($eventType === 'payout.failed') {
            $this->processFailedPayment($payload['data']);
        }

        return response()->json(['status' => 'ok']);
    }

    protected function processCompletedPayment(array $data)
    {
        $reference = $data['reference'];
        $contribution = Contribution::where('receipt_number', $reference)->first();

        if ($contribution) {
            $contribution->update([
                'is_verified' => true,
                'notes' => ($contribution->notes ?? '') . "\nPaid via Snipe. Provider: {$data['channel']['provider']}",
            ]);

            Log::info("Contribution {$reference} marked as verified via Snipe.");
        }
    }

    protected function processFailedPayment(array $data)
    {
        $reference = $data['reference'];
        Log::warning("Snipe Payment Failed for Reference: {$reference}. Reason: " . ($data['failure_reason'] ?? 'Unknown'));
    }
}
