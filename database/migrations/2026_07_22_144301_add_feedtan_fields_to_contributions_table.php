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
            $table->string('feedtan_order_reference')->nullable()->after('transaction_reference');
            $table->string('feedtan_transaction_id')->nullable()->after('feedtan_order_reference');
            $table->string('feedtan_payment_method')->nullable()->after('feedtan_transaction_id');
            $table->enum('feedtan_status', ['PROCESSING', 'PENDING', 'SUCCESS', 'SETTLED', 'FAILED', 'DECLINED', 'CANCELLED'])->nullable()->after('feedtan_payment_method');
            $table->timestamp('feedtan_paid_at')->nullable()->after('feedtan_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn([
                'feedtan_order_reference',
                'feedtan_transaction_id',
                'feedtan_payment_method',
                'feedtan_status',
                'feedtan_paid_at'
            ]);
        });
    }
};
