<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert FeedTan API settings
        $settings = [
            [
                'key' => 'feedtan.base_url',
                'value' => 'https://pay.feedtancmg.org/api/ecommerce',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'FeedTan Base URL',
                'description' => 'Base URL for FeedTan e-commerce API'
            ],
            [
                'key' => 'feedtan.api_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'FeedTan API Key',
                'description' => 'API key for FeedTan payment integration'
            ],
            [
                'key' => 'feedtan.enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payment',
                'display_name' => 'Enable FeedTan Payments',
                'description' => 'Enable FeedTan as a payment provider'
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            //
        });
    }
};
