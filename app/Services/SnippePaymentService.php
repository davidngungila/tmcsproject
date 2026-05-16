<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contribution;
use App\Models\Member;
use Illuminate\Support\Str;

class SnippePaymentService
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
     * Create a payment checkout session (Web View)
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
                'Idempotency-Key' => 'session-' . $contribution->id . '-' . time(),
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
    public function createMobileMoneyPayment(Contribution $contribution)
    {
        try {
            $member = $contribution->member;
            $phone = $this->formatPhoneForSnippe($member->phone);
            
            if ($phone === '') {
                return ['error' => 'Please provide a valid Tanzania mobile number for M-Pesa / mobile money.'];
            }

            $payload = [
                'payment_type' => 'mobile',
                'details' => [
                    'amount' => (int) $contribution->amount,
                    'currency' => 'TZS',
                ],
                'phone_number' => $phone,
                'customer' => $this->customerBlock($member),
                'metadata' => [
                    'contribution_id' => $contribution->id,
                    'receipt_number' => $contribution->receipt_number,
                ],
            ];
            
            if ($this->webhookUrl) {
                $payload['webhook_url'] = $this->webhookUrl;
            }

            $response = Http::connectTimeout(25)
                ->timeout(120)
                ->withHeaders([
                'Authorization' => 'Bearer ' . $this->snippeKey,
                'Content-Type' => 'application/json',
                'Idempotency-Key' => 'mobile-' . $contribution->id . '-' . time(),
            ])->post($this->baseUrl . '/v1/payments', $payload);

            $parsed = $this->parseV1PaymentCreateResponse($response);
            if (! $parsed['ok']) {
                Log::error('Snippe mobile money payment failed', [
                    'contribution_id' => $contribution->id,
                    'message' => $parsed['message'] ?? null,
                    'response' => $parsed['raw'] ?? $response->body(),
                ]);

                return ['error' => $parsed['message'] ?? 'Payment gateway rejected the request.'];
            }

            $data = $parsed['data'];

            return [
                'payment_token' => $data['payment_token'] ?? null,
                'reference' => $data['reference'] ?? null,
                'status' => $data['status'] ?? null,
                'payment_qr_code' => $data['payment_qr_code'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Snippe mobile money payment error', [
                'error' => $e->getMessage(),
                'contribution_id' => $contribution->id,
            ]);

            return ['error' => 'Failed to reach payment gateway. Check your connection and try again.'];
        }
    }

    /**
     * Create a card payment directly
     */
    public function createCardPayment(Contribution $contribution)
    {
        try {
            $member = $contribution->member;
            $phone = $this->formatPhoneForSnippe($member->phone);
            
            if ($phone === '') {
                return ['error' => 'Please provide a valid phone number for card checkout.'];
            }

            $payload = [
                'payment_type' => 'card',
                'details' => [
                    'amount' => (int) $contribution->amount,
                    'currency' => 'TZS',
                    'redirect_url' => $this->postPaymentRedirectUrl,
                    'cancel_url' => $this->postPaymentRedirectUrl,
                ],
                'phone_number' => $phone,
                'customer' => $this->customerBlock($member),
                'metadata' => [
                    'contribution_id' => $contribution->id,
                    'receipt_number' => $contribution->receipt_number,
                ],
            ];
            
            if ($this->webhookUrl) {
                $payload['webhook_url'] = $this->webhookUrl;
            }

            $response = Http::connectTimeout(25)
                ->timeout(120)
                ->withHeaders([
                'Authorization' => 'Bearer ' . $this->snippeKey,
                'Content-Type' => 'application/json',
                'Idempotency-Key' => 'card-' . $contribution->id . '-' . time(),
            ])->post($this->baseUrl . '/v1/payments', $payload);

            $parsed = $this->parseV1PaymentCreateResponse($response);
            if (! $parsed['ok']) {
                Log::error('Snippe card payment failed', [
                    'contribution_id' => $contribution->id,
                    'message' => $parsed['message'] ?? null,
                    'response' => $parsed['raw'] ?? $response->body(),
                ]);

                return ['error' => $parsed['message'] ?? 'Payment gateway rejected the request.'];
            }

            $data = $parsed['data'];

            return [
                'payment_url' => $data['payment_url'] ?? null,
                'payment_token' => $data['payment_token'] ?? null,
                'reference' => $data['reference'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Snippe card payment error', [
                'error' => $e->getMessage(),
                'contribution_id' => $contribution->id,
            ]);

            return ['error' => 'Failed to reach payment gateway.'];
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

        $contribution->update([
            'is_verified' => true,
            'notes' => ($contribution->notes ?? '') . "\nPaid via Snipe. Reference: " . ($data['reference'] ?? 'N/A'),
        ]);

        $this->sendSuccessNotifications($contribution);

        return true;
    }

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

            if ($member->phone) {
                $smsMessage = "Dear {$member->full_name}, your payment of TZS {$amount} for {$type} has been CONFIRMED. Receipt: {$contribution->receipt_number}. Thank you!";
                $messagingService->sendSms($member->phone, $smsMessage);
            }

            if ($member->email) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.receipt_pdf', compact('contribution'))->output();
                \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\ContributionReceiptMailable($contribution, $pdf));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send success notifications: " . $e->getMessage());
        }
    }

    public function verifyWebhookSignature($payload, $signature)
    {
        if (!$signature || !$this->webhookSecret) {
            return false;
        }
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }

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

    protected function customerBlock(Member $member): array
    {
        $parts = explode(' ', trim($member->full_name));
        $first = $parts[0] ?? 'Customer';
        $last = count($parts) > 1 ? end($parts) : 'Member';

        return [
            'firstname' => $first,
            'lastname' => $last,
            'email' => $member->email ?? 'customer@example.com',
            'country' => 'TZ',
        ];
    }

    protected function parseV1PaymentCreateResponse(\Illuminate\Http\Client\Response $response): array
    {
        $raw = $response->body();
        $json = $response->json();
        
        if (! is_array($json)) {
            return ['ok' => false, 'data' => [], 'message' => 'Invalid response from payment gateway.', 'raw' => $raw];
        }

        $data = $json['data'] ?? $json; // Handle cases where data is at top level
        $status = $json['status'] ?? null;
        
        if ($response->successful() && $status !== 'error') {
            return ['ok' => true, 'data' => $data];
        }

        return [
            'ok' => false,
            'data' => $data,
            'message' => $json['message'] ?? 'Payment gateway rejected the request.',
            'raw' => $raw,
        ];
    }
}
