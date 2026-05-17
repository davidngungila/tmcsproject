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
        Schema::create('saved_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('type'); // mobile_money, card
            $table->string('provider')->nullable(); // M-Pesa, TigoPesa, etc.
            $table->string('identifier'); // phone number or masked card
            $table->string('label')->nullable(); // e.g. "My M-Pesa", "Wife's Phone"
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_payment_methods');
    }
};
