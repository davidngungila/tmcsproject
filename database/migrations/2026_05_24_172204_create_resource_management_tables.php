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
        Schema::create('resource_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('resource_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, etc.
            $table->integer('file_size');
            $table->integer('page_count')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->json('metadata')->nullable(); // TOC, excerpt, etc.
            $table->timestamps();
        });

        Schema::create('user_resource_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->boolean('is_bookmarked')->default(false);
            $table->integer('last_page_read')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->text('personal_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_resource_interactions');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('resource_categories');
    }
};
