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
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('contribution_type'); // Changed from enum to string for flexibility
            $table->string('payment_method'); // Changed from enum to string for flexibility
            $table->date('contribution_date');
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_qr_code')->unique()->nullable();
            $table->boolean('is_verified')->default(true);
            $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('receipt_number');
            $table->index('member_id');
            $table->index('contribution_date');
            $table->index('contribution_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
