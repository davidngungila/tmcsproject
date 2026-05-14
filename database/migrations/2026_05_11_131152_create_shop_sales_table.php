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
        Schema::create('shop_sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->json('items');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('change_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'mobile_money', 'bank_transfer']);
            $table->date('sale_date');
            $table->time('sale_time');
            $table->foreignId('sold_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('receipt_number');
            $table->index('sale_date');
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_sales');
    }
};
