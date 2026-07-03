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
        Schema::create('quiz_submission_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('submission_type', ['manual', 'auto_time_expired'])->default('manual');
            $table->decimal('score_before_submission', 5, 2)->default(0);
            $table->integer('answered_questions')->default(0);
            $table->integer('total_questions')->default(0);
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('attempt_id');
            $table->index('quiz_id');
            $table->index('student_id');
            $table->index('submission_type');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_submission_logs');
    }
};