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
        Schema::table('members', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->change();
        });

        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'card', 'dynamic-qr'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('qr_code')->nullable(false)->change();
        });

        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money'])->change();
        });
    }
};
