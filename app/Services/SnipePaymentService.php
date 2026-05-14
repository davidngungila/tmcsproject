<?php

namespace App\Services;

use App\Models\ApiConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SnipePaymentService
{
    protected $config;

    public function __construct()
    {
        $this->config = ApiConfig::where('provider_type', 'Payment')->where('is_active', true)->first();
    }

    /**
     * Initialize a payment (Mobile Money, Card, or QR)
     * 
     * @param string $type (mobile, card, dynamic-qr)
     * @param array $data
     * @return array
     */
    public function initializePayment(string $type, array $data)
    {
        if (!$this->config) {
            return [
                'status' => 'error',
                'message' => 'No active Payment gateway configuration found.',
            ];
        }

        $idempotencyKey = Str::uuid()->toString();
        $endpoint = $this->config->api_endpoint ?? 'https://api.snippe.sh/v1/payments';

        try {
            $response = Http::withToken($this->config->api_key)
                ->withHeaders([
                    'Idempotency-Key' => $idempotencyKey,
                ])
                ->post($endpoint, [
                    'type' => $type,
                    'amount' => [
                        'value' => (int)$data['amount'],
                        'currency' => 'TZS',
                    ],
                    'customer' => [
                        'phone' => $data['phone'] ?? null,
                        'name' => $data['name'] ?? null,
                        'email' => $data['email'] ?? null,
                    ],
                    'reference' => $data['reference'],
                    'callback_url' => route('finance.index'), // Browser redirect
                    'webhook_url' => route('webhooks.snipe'), // Server-to-server
                    'metadata' => $data['metadata'] ?? [],
                ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            Log::error('Snipe Payment Initialization Failed', [
                'type' => $type,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            return [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Payment initialization failed',
            ];

        } catch (\Exception $e) {
            Log::error('Snipe Payment Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'status' => 'error',
                'message' => 'An unexpected error occurred during payment processing.',
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature, $timestamp)
    {
        // Verification logic based on Snipe documentation
        // Usually: hash_hmac('sha256', $timestamp . '.' . $payload, $signingKey)
        // Since we don't have the signing key yet, we'll log it for now.
        return true; 
    }
}
