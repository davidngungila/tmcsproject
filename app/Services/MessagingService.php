<?php

namespace App\Services;

use App\Models\ApiConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessagingService
{
    protected $config;

    public function __construct()
    {
        $this->config = ApiConfig::where('provider_type', 'SMS')->where('is_active', true)->first();
    }

    /**
     * Send SMS to one or more recipients
     * 
     * @param string|array $recipients
     * @param string $message
     * @return array
     */
    public function sendSms($recipients, string $message)
    {
        if (!$this->config) {
            return [
                'status' => 'error',
                'message' => 'No active SMS gateway configuration found.',
            ];
        }

        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        // Format recipients to ensure they are strings and have country code if needed
        $formattedRecipients = array_map(function($phone) {
            // Remove any non-numeric characters
            $phone = preg_replace('/[^0-9]/', '', $phone);
            // Ensure 255 prefix for Tanzania if it starts with 0 or 7
            if (str_starts_with($phone, '0')) {
                $phone = '255' . substr($phone, 1);
            } elseif (str_starts_with($phone, '7')) {
                $phone = '255' . $phone;
            }
            return $phone;
        }, $recipients);

        try {
            $endpoint = $this->config->api_endpoint ?? 'https://messaging-service.co.tz/api/sms/v2/text/single';
            
            // Remove /test/ from endpoint if it exists to ensure live delivery
            $endpoint = str_replace('/test/', '/', $endpoint);
            
            // Check if it's multiple recipients
            $isMultiple = count($formattedRecipients) > 1;
            
            if ($isMultiple && str_contains($endpoint, '/single')) {
                $endpoint = str_replace('/single', '/multi', $endpoint);
            }

            $payload = [
                'from' => $this->config->sender_id ?? 'TMCS MOCU',
                'text' => $message,
            ];

            if ($isMultiple) {
                $payload['to'] = $formattedRecipients;
            } else {
                $payload['to'] = $formattedRecipients[0];
            }

            $response = Http::withoutVerifying()
                ->withToken($this->config->api_key)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            Log::error('Messaging Service SMS Failed', [
                'recipients' => $formattedRecipients,
                'response' => $response->json(),
                'status' => $response->status(),
                'endpoint' => $endpoint
            ]);

            return [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Failed to send SMS (Status: ' . $response->status() . ')',
            ];

        } catch (\Exception $e) {
            Log::error('Messaging Service Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get SMS balance from Messaging Service
     * 
     * @return array
     */
    public function getBalance()
    {
        if (!$this->config) {
            return [
                'status' => 'error',
                'message' => 'No active SMS gateway configuration found.',
            ];
        }

        try {
            $endpoint = 'https://messaging-service.co.tz/api/v2/balance';
            
            $response = Http::withoutVerifying()
                ->withToken($this->config->api_key)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($endpoint);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to fetch balance (Status: ' . $response->status() . ')',
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get SMS logs from Messaging Service
     * 
     * @param array $filters
     * @return array
     */
    public function getLogs(array $filters = [])
    {
        if (!$this->config) {
            return [
                'status' => 'error',
                'message' => 'No active SMS gateway configuration found.',
            ];
        }

        try {
            $endpoint = 'https://messaging-service.co.tz/api/v2/logs';
            
            $response = Http::withoutVerifying()
                ->withToken($this->config->api_key)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($endpoint, $filters);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to fetch logs (Status: ' . $response->status() . ')',
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send WhatsApp message
     */
    public function sendWhatsApp($recipient, string $message)
    {
        return $this->sendSms($recipient, $message);
    }
}
