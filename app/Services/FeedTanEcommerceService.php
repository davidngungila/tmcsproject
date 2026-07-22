<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;

class FeedTanEcommerceService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = SystemSetting::get('feedtan.base_url', 'https://pay.feedtancmg.org/api/ecommerce');
    }

    /**
     * Initiate a mobile money payment
     */
    public function initiatePayment(array $data): array
    {
        $payload = [
            'amount' => $data['amount'],
            'phone_number' => $this->formatPhoneNumber($data['phone_number']),
            'payer_name' => $data['payer_name'],
            'description' => $data['description'],
            'order_reference' => $data['order_reference'] ?? null,
            'email' => $data['email'] ?? null,
            'callback_url' => $data['callback_url'] ?? url('/api/payments/feedtan/callback'),
            'metadata' => $data['metadata'] ?? null,
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(180)->post($this->baseUrl . '/payments/initiate', $payload);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                Log::info('FeedTan payment initiated successfully', [
                    'order_reference' => $result['data']['order_reference'] ?? null,
                    'transaction_id' => $result['data']['transaction_id'] ?? null,
                ]);
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            Log::error('FeedTan payment initiation failed', [
                'status' => $response->status(),
                'response' => $result,
            ]);

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Payment initiation failed',
            ];
        } catch (\Exception $e) {
            Log::error('FeedTan API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to connect to payment provider: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(string $orderReference): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/payments/status/' . $orderReference);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            Log::error('FeedTan status check failed', [
                'order_reference' => $orderReference,
                'status' => $response->status(),
                'response' => $result,
            ]);

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Status check failed',
            ];
        } catch (\Exception $e) {
            Log::error('FeedTan status check exception', [
                'order_reference' => $orderReference,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to check payment status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction history
     */
    public function getTransactionHistory(array $filters = []): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/payments/history', $filters);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                    'pagination' => $result['pagination'] ?? null
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Failed to fetch transaction history',
            ];
        } catch (\Exception $e) {
            Log::error('FeedTan history fetch exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to fetch transaction history: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to required format (255712345678)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 255
        if (str_starts_with($phone, '0')) {
            $phone = '255' . substr($phone, 1);
        }

        // If starts with +255, remove the +
        if (str_starts_with($phone, '+255')) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Check if payment is successful
     */
    public function isPaymentSuccessful(string $orderReference): bool
    {
        $status = $this->checkPaymentStatus($orderReference);

        if (!$status['success']) {
            return false;
        }

        $data = $status['data'];
        return ($data['is_paid'] ?? false) && in_array($data['status'] ?? '', ['SUCCESS', 'SETTLED']);
    }
}
