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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('chairperson_id')->nullable()->after('type')->constrained('members')->nullOnDelete();
            $table->foreignId('secretary_id')->nullable()->after('chairperson_id')->constrained('members')->nullOnDelete();
            $table->foreignId('accountant_id')->nullable()->after('secretary_id')->constrained('members')->nullOnDelete();
            $table->string('meeting_day')->nullable()->after('accountant_id');
            $table->decimal('regular_contribution_amount', 15, 2)->default(0)->after('meeting_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['chairperson_id']);
            $table->dropForeign(['secretary_id']);
            $table->dropForeign(['accountant_id']);
            $table->dropColumn(['chairperson_id', 'secretary_id', 'accountant_id', 'meeting_day', 'regular_contribution_amount']);
        });
    }
};
