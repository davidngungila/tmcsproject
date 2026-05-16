<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contribution;
use App\Models\Member;
use Illuminate\Support\Str;

class SnipePaymentService
{
    protected string $snippeKey;
    protected string $webhookSecret;
    protected string $postPaymentRedirectUrl;
    protected ?string $webhookUrl;
    protected string $baseUrl;

    public function __construct()
    {
        $cfg = config('services.snippe', []);
        $this->snippeKey = (string) ($cfg['api_key'] ?? '');
        $this->webhookSecret = (string) ($cfg['webhook_secret'] ?? '');
        $this->postPaymentRedirectUrl = (string) ($cfg['post_payment_redirect_url'] ?? 'https://tmcs.feedtancmg.org/member/profile');
        $webhook = $cfg['webhook_url'] ?? null;
        $this->webhookUrl = is_string($webhook) && $webhook !== '' ? $webhook : null;
        $this->baseUrl = rtrim((string) ($cfg['base_url'] ?? 'https://api.snippe.sh'), '/');
    }

    public function isConfigured(): bool
    {
        return $this->snippeKey !== '';
    }

    /**
     * Create a payment checkout session
     */
    public function createCheckout(Contribution $contribution)
    {
        $member = $contribution->member;
        
        try {
            $sessionPayload = [
                'amount' => (int) $contribution->amount,
                'currency' => 'TZS',
                'allowed_methods' => ['mobile_money', 'card'],
                'customer' => [
                    'name' => $member->full_name,
                    'phone' => $this->formatPhoneForSnippe($member->phone),
                    'email' => $member->email ?? 'customer@example.com',
                ],
                'description' => "Contribution #{$contribution->receipt_number} - {$contribution->contribution_type}",
                'redirect_url' => $this->postPaymentRedirectUrl,
                'metadata' => [
                    'contribution_id' => $contribution->id,
                    'receipt_number' => $contribution->receipt_number,
                ],
            ];
            
            if ($this->webhookUrl) {
                $sessionPayload['webhook_url'] = $this->webhookUrl;
            }

            $response = Http::timeout(45)->withHeaders([
                'Authorization' => 'Bearer ' . $this->snippeKey,
                'Content-Type' => 'application/json',
                'Idempotency-Key' => 'contrib-' . $contribution->id . '-' . time(),
            ])->post($this->baseUrl . '/api/v1/sessions', $sessionPayload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'checkout_url' => $data['data']['checkout_url'] ?? null,
                    'payment_link_url' => $data['data']['payment_link_url'] ?? null,
                    'reference' => $data['data']['reference'] ?? null,
                ];
            }

            Log::error('Snippe session creation failed', [
                'response' => $response->body(),
                'contribution_id' => $contribution->id,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Snippe checkout error', [
                'error' => $e->getMessage(),
                'contribution_id' => $contribution->id,
            ]);
            return null;
        }
    }

    /**
     * Create a direct mobile money payment (USSD push)
     */
    public function initializePayment(string $type, array $data)
    {
        try {
            $phone = $this->formatPhoneForSnippe($data['phone']);
            if ($phone === '') {
                return ['status' => 'error', 'message' => 'Please provide a valid Tanzania mobile number.'];
            }

            $payload = [
                'payment_type' => $type === 'mobile' || $type === 'mobile_money' ? 'mobile' : $type,
                'details' => [
                    'amount' => (int) $data['amount'],
                    'currency' => 'TZS',
                ],
                'phone_number' => $phone,
                'customer' => [
                    'name' => $data['name'] ?? 'Customer',
                    'email' => $data['email'] ?? null,
                ],
                'reference' => $data['reference'],
                'metadata' => $data['metadata'] ?? [],
            ];
            
            if ($this->webhookUrl) {
                $payload['webhook_url'] = $this->webhookUrl;
            }

            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . $this->snippeKey,
                'Content-Type' => 'application/json',
                'Idempotency-Key' => 'pay-' . $data['reference'] . '-' . time(),
            ])->post($this->baseUrl . '/v1/payments', $payload);

            if ($response->successful()) {
                $json = $response->json();
                return [
                    'status' => 'success',
                    'data' => $json['data'] ?? $json,
                ];
            }

            Log::error('Snippe payment initiation failed', [
                'response' => $response->body(),
                'reference' => $data['reference'],
            ]);

            return [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Payment initiation failed',
            ];

        } catch (\Exception $e) {
            Log::error('Snippe payment error', [
                'error' => $e->getMessage(),
                'reference' => $data['reference'],
            ]);
            return ['status' => 'error', 'message' => 'Failed to reach payment gateway.'];
        }
    }

    /**
     * Process webhook event
     */
    public function processWebhook(array $payload)
    {
        $eventType = $payload['event_type'] ?? null;
        $data = $payload['data'] ?? [];

        switch ($eventType) {
            case 'payment.completed':
                return $this->handlePaymentCompleted($data);
            case 'payment.failed':
                return $this->handlePaymentFailed($data);
            default:
                Log::info('Unhandled webhook event', ['event_type' => $eventType]);
                return false;
        }
    }

    /**
     * Handle payment completed event
     */
    protected function handlePaymentCompleted(array $data)
    {
        $contributionId = $data['metadata']['contribution_id'] ?? null;
        
        if (!$contributionId) {
            Log::error('Payment completed webhook missing contribution_id');
            return false;
        }

        $contribution = Contribution::find($contributionId);
        if (!$contribution) {
            Log::error('Contribution not found for payment webhook', ['contribution_id' => $contributionId]);
            return false;
        }

        // Update contribution status
        $contribution->update([
            'is_verified' => true,
            'notes' => ($contribution->notes ?? '') . "\nPaid via Snipe. Reference: " . ($data['reference'] ?? 'N/A'),
        ]);

        // Send notifications
        // We need to inject FinanceController or duplicate logic.
        // For simplicity, we'll call a dedicated notification method
        $this->sendSuccessNotifications($contribution);

        return true;
    }

    /**
     * Handle payment failed event
     */
    protected function handlePaymentFailed(array $data)
    {
        $contributionId = $data['metadata']['contribution_id'] ?? null;
        
        if ($contributionId) {
            $contribution = Contribution::find($contributionId);
            if ($contribution) {
                $contribution->update([
                    'notes' => ($contribution->notes ?? '') . "\nPayment Failed via Snipe. Reason: " . ($data['failure_reason'] ?? 'Unknown'),
                ]);
            }
        }

        return true;
    }

    protected function sendSuccessNotifications(Contribution $contribution)
    {
        try {
            $messagingService = app(MessagingService::class);
            $member = $contribution->member;
            $amount = number_format($contribution->amount, 0);
            $type = ucfirst(str_replace('_', ' ', $contribution->contribution_type));

            // 1. Send SMS
            if ($member->phone) {
                $smsMessage = "Dear {$member->full_name}, your payment of TZS {$amount} for {$type} has been CONFIRMED. Receipt: {$contribution->receipt_number}. Thank you!";
                $messagingService->sendSms($member->phone, $smsMessage);
            }

            // 2. Send Email with PDF
            if ($member->email) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.receipt_pdf', compact('contribution'))->output();
                \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\ContributionReceiptMailable($contribution, $pdf));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send success notifications: " . $e->getMessage());
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        if (!$signature || !$this->webhookSecret) {
            return false;
        }
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Snippe expects MSISDN as digits only, e.g. 2557XXXXXXXX (no leading +).
     */
    protected function formatPhoneForSnippe(?string $phone): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === null || $digits === '') {
            return '';
        }
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '255' . substr($digits, 1);
        } elseif (strlen($digits) === 9) {
            $digits = '255' . $digits;
        }
        
        return $digits;
    }
}
