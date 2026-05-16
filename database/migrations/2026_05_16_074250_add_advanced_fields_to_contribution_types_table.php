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
        Schema::table('contribution_types', function (Blueprint $table) {
            $table->string('code')->unique()->after('id');
            $table->string('gl_account')->nullable()->after('description');
            $table->decimal('min_amount', 15, 2)->default(0)->after('gl_account');
            $table->boolean('is_mandatory')->default(false)->after('min_amount');
            $table->string('frequency')->default('one-time')->after('is_mandatory'); // one-time, weekly, monthly, annual
            $table->string('color')->default('blue')->after('frequency');
            $table->string('icon')->default('cash')->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contribution_types', function (Blueprint $table) {
            $table->dropColumn(['code', 'gl_account', 'min_amount', 'is_mandatory', 'frequency', 'color', 'icon']);
        });
    }
};
