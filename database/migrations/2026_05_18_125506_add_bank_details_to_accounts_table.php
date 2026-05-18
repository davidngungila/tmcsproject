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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('name');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('branch_code')->nullable()->after('account_number');
            $table->boolean('is_default_income')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'branch_code', 'is_default_income']);
        });
    }
};
