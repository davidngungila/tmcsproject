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
        Schema::table('contributions', function (Blueprint $table) {
            $table->string('contribution_type')->change();
            $table->string('payment_method')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('contribution_type', ['almsgiving', 'offering', 'tithe', 'special_donation'])->change();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'card', 'dynamic-qr'])->change();
        });
    }
};
