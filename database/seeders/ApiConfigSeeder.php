<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiConfig;

class ApiConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SMS Configuration (Messaging Service)
        ApiConfig::updateOrCreate(
            ['provider_type' => 'SMS', 'name' => 'Messaging Service'],
            [
                'api_key' => '8b4d46ca83411b8457e0fb8c3a77d02a',
                'api_secret' => null,
                'api_endpoint' => 'https://messaging-service.co.tz/api/sms/v2/text/single',
                'sender_id' => 'TMCS MOCU',
                'is_active' => true,
            ]
        );

        // Payment Configuration (Snippe)
        ApiConfig::updateOrCreate(
            ['provider_type' => 'Payment', 'name' => 'Snippe Payment'],
            [
                'api_key' => 'snp_9c0da68172ec6a536ce3b7623eb959e540fb5985a097011239ad970c0cf25b3d',
                'api_endpoint' => 'https://api.snippe.sh/v1/payments',
                'is_active' => true,
                'extra_config' => [
                    'webhook_url' => 'https://yourapp.com/webhooks/snippe',
                    'webhook_secret' => 'whsec_...'
                ]
            ]
        );
    }
}
