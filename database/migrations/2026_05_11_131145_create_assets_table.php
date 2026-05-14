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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->string('category');
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 10, 2);
            $table->decimal('current_value', 10, 2)->nullable();
            $table->string('location');
            $table->foreignId('assigned_to')->nullable()->constrained('members')->onDelete('set null');
            $table->enum('status', ['available', 'in-use', 'maintenance', 'lost'])->default('available');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('asset_name');
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
