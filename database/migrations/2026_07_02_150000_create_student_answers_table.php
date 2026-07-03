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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            //$table->foreignId('selected_option_id')->nullable()->constrained('question_options')->onDelete('set null');
            $table->unsignedBigInteger('selected_option_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            
            // Ensure one answer per question per attempt
            $table->unique(['attempt_id', 'question_id'], 'unique_attempt_question');
            
            // Indexes for performance
            $table->index('attempt_id');
            $table->index('question_id');
            $table->index('is_correct');
            $table->index(['attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};