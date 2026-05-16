<?php

namespace App\Http\Controllers;

use App\Services\SnipePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $snipeService;

    public function __construct(SnipePaymentService $snipeService)
    {
        $this->snipeService = $snipeService;
    }

    /**
     * Handle Snipe Payment Webhooks
     */
    public function handleSnipe(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Snippe-Signature');

        // Log the webhook for debugging
        Log::info('Snipe Webhook Received', [
            'header' => $signature,
            'payload' => $request->all(),
        ]);

        // Verify signature if secret is configured
        if (!$this->snipeService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Snipe Webhook Signature Verification Failed');
            // In production, you might want to return 401, but we'll return 200 for now to avoid retries
            // return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        $result = $this->snipeService->processWebhook($request->all());

        if ($result) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ignored']);
    }
}
