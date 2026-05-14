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
        Schema::create('api_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Twilio, Mailgun, WhatsApp API
            $table->string('provider_type'); // SMS, Email, WhatsApp
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('api_endpoint')->nullable();
            $table->string('sender_id')->nullable(); // e.g., TMCS_CHURCH
            $table->boolean('is_active')->default(false);
            $table->json('extra_config')->nullable(); // For any other provider-specific fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_configs');
    }
};
