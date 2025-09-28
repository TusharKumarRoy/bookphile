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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn')->nullable()->unique();
            $table->text('description')->nullable();
            $table->date('publication_date')->nullable();
            $table->integer('page_count')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('language', 10)->default('en');
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('ratings_count')->default(0);
            $table->timestamps();

            // Add indexes for common searches
            $table->index(['title']);
            $table->index(['average_rating']);
            $table->index(['publication_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};