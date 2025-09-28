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
        Schema::create('reading_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['want_to_read', 'currently_reading', 'finished_reading'])->default('want_to_read');
            $table->date('started_reading')->nullable();
            $table->date('finished_reading')->nullable();
            $table->integer('current_page')->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            // Ensure each user can only have one status per book
            $table->unique(['user_id', 'book_id']);
            
            // Add indexes for common queries
            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_statuses');
    }
};