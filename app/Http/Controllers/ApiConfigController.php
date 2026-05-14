<?php

namespace App\Http\Controllers;

use App\Models\ApiConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiConfigController extends Controller
{
    public function index()
    {
        $configs = ApiConfig::paginate(10);
        return view('api_configs.index', compact('configs'));
    }

    /**
     * Test the connection for a specific API configuration.
     */
    public function testConnection(Request $request, ApiConfig $apiConfig)
    {
        $phone = $request->input('test_phone', '0622239304');
        $amount = $request->input('test_amount', 500);

        // Normalize phone to 255XXXXXXXXX format
        $normalizedPhone = preg_replace('/^0/', '255', $phone);
        if (!str_starts_with($normalizedPhone, '255')) {
            $normalizedPhone = '255' . $normalizedPhone;
        }

        try {
            if ($apiConfig->provider_type === 'SMS') {
                // Test Messaging Service / NextSMS connection
                $endpoint = $apiConfig->api_endpoint ?? 'https://messaging-service.co.tz/api/sms/v2/text/single';
                $endpoint = str_replace('/test/', '/', $endpoint);

                $response = Http::withoutVerifying()
                    ->withToken($apiConfig->api_key)
                    ->post($endpoint, [
                        'from' => $apiConfig->sender_id ?? 'TMCS MOCU',
                        'to' => $normalizedPhone,
                        'text' => 'TMCS Test Message: Connection verified successfully.'
                    ]);
                
                if ($response->successful()) {
                    return back()->with('success', 'SMS Sent Successfully to ' . $phone . '! Provider: ' . $apiConfig->name);
                }
            } elseif ($apiConfig->provider_type === 'Payment') {
                // Test Snippe Payment connection (Initiate Mobile Payment)
                $response = Http::withoutVerifying()
                    ->withToken($apiConfig->api_key)
                    ->withHeaders([
                        'Idempotency-Key' => 'test_' . time() . '_' . $apiConfig->id
                    ])
                    ->post($apiConfig->api_endpoint ?? 'https://api.snippe.sh/v1/payments', [
                        'type' => 'mobile',
                        'amount' => [
                            'value' => (int)$amount,
                            'currency' => 'TZS',
                        ],
                        'customer' => [
                            'phone' => $normalizedPhone,
                            'name' => 'Test User',
                        ],
                        'reference' => 'TEST_' . time(),
                        'callback_url' => url('/finance/callback'),
                        'metadata' => [
                            'test_mode' => true,
                            'reason' => 'API Connection Test'
                        ]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return back()->with('success', 'Payment Initiated! USSD Prompt sent to ' . $phone . '. Reference: ' . ($data['data']['reference'] ?? 'N/A'));
                }
            }
            
            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? 'Connection Failed (Status: ' . $response->status() . ')';
            return back()->with('error', 'API Error: ' . $errorMessage);
        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('api_configs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|string|in:SMS,Email,WhatsApp,Payment',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'api_endpoint' => 'nullable|url',
            'sender_id' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_active') && $validated['is_active']) {
            // Deactivate others of same type
            ApiConfig::where('provider_type', $validated['provider_type'])->update(['is_active' => false]);
        }

        ApiConfig::create($validated);

        return redirect()->route('api-configs.index')->with('success', 'API Configuration created successfully');
    }

    public function show(ApiConfig $apiConfig)
    {
        return view('api_configs.show', compact('apiConfig'));
    }

    public function edit(ApiConfig $apiConfig)
    {
        return view('api_configs.edit', compact('apiConfig'));
    }

    public function update(Request $request, ApiConfig $apiConfig)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|string|in:SMS,Email,WhatsApp,Payment',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'api_endpoint' => 'nullable|url',
            'sender_id' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_active') && $validated['is_active']) {
            // Deactivate others of same type
            ApiConfig::where('provider_type', $validated['provider_type'])
                ->where('id', '!=', $apiConfig->id)
                ->update(['is_active' => false]);
        }

        $apiConfig->update($validated);

        return redirect()->route('api-configs.index')->with('success', 'API Configuration updated successfully');
    }

    public function destroy(ApiConfig $apiConfig)
    {
        $apiConfig->delete();
        return redirect()->route('api-configs.index')->with('success', 'API Configuration deleted successfully');
    }
}
