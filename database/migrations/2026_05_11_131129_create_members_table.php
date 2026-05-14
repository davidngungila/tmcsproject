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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('member_type', ['student', 'non-student', 'employee', 'child']);
            $table->date('date_of_birth');
            $table->text('address');
            $table->string('baptismal_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('qr_code')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->date('registration_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('registration_number');
            $table->index('full_name');
            $table->index('member_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
