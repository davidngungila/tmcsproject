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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_otp')->nullable()->after('phone');
            $table->timestamp('phone_otp_expires_at')->nullable()->after('phone_otp');
            $table->boolean('phone_verified')->default(false)->after('phone_otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_otp', 'phone_otp_expires_at', 'phone_verified']);
        });
    }
};
