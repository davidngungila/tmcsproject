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
        $this->config = ApiConfig::find(1);
    }

    /**
     * Send SMS to one or more recipients
     * 
     * @param string|array $recipients
     * @param string $message
     * @return array
     */
    /**
     * Send SMS to one or more recipients
     */
    public function sendSms($recipients, string $message)
    {
        if (!$this->config) {
            return [
                'status' => 'error',
                'message' => 'No active SMS gateway configuration found.',
            ];
        }

        $recipients = is_array($recipients) ? $recipients : [$recipients];
        
        $formattedRecipients = array_map(function($phone) {
            return $this->formatPhoneNumber($phone);
        }, $recipients);

        $endpoint = $this->config->api_endpoint ?? 'https://messaging-service.co.tz/api/sms/v2/text/single';
        $apiKey = trim($this->config->api_key);

        try {
            // Log the attempt
            Log::info("Attempting to send SMS to: " . implode(', ', $formattedRecipients));

            // For v2 text/single, 'to' can be a string or array
            $payload = [
                'from' => $this->config->sender_id ?? 'TMCS MoCU',
                'to' => count($formattedRecipients) === 1 ? $formattedRecipients[0] : $formattedRecipients,
                'text' => $message,
            ];

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, $payload);

            if ($response->successful()) {
                Log::info("SMS Sent Successfully to: " . implode(', ', $formattedRecipients));
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            Log::error("Messaging Service SMS Failed", [
                'recipients' => $formattedRecipients,
                'response' => $response->json() ?? $response->body(),
                'status' => $response->status(),
                'endpoint' => $endpoint
            ]);

            return [
                'status' => 'error',
                'message' => 'SMS gateway returned error: ' . ($response->json()['message'] ?? 'Unknown error'),
                'code' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error("Messaging Service Exception", [
                'message' => $e->getMessage(),
                'recipients' => $formattedRecipients
            ]);

            return [
                'status' => 'error',
                'message' => 'Could not connect to SMS gateway: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Helper function to format phone number
     * Ensures the number starts with 255 and has no leading +
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters 
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); 
         
        // Remove leading 0 or +255 
        if (substr($phoneNumber, 0, 4) == '2557') { 
            return $phoneNumber; 
        } elseif (substr($phoneNumber, 0, 1) == '0') { 
            return '255' . substr($phoneNumber, 1); 
        } elseif (substr($phoneNumber, 0, 3) == '255') { 
            return $phoneNumber; 
        } else { 
            return '255' . $phoneNumber; 
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
                ->acceptJson()
                ->withToken(trim($this->config->api_key))
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
                ->acceptJson()
                ->withToken(trim($this->config->api_key))
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
