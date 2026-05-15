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
        Schema::table('group_meetings', function (Blueprint $table) {
            $table->integer('present_count')->default(0)->after('notes');
            $table->integer('absent_count')->default(0)->after('present_count');
            $table->integer('apology_count')->default(0)->after('absent_count');
            $table->integer('guest_count')->default(0)->after('apology_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_meetings', function (Blueprint $table) {
            $table->dropColumn(['present_count', 'absent_count', 'apology_count', 'guest_count']);
        });
    }
};
