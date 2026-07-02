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
        Schema::create('quiz_attempts', function (Blueprint $table) {
<<<<<<< HEAD
             $table->id('ID'); // PK [cite: 33]
=======
           $table->id('ID'); // PK [cite: 33]
>>>>>>> 91f1542f290a84aadc81093b0d6628d3d48ee384
             $table->float('Score')->nullable(); [cite: 33]
             $table->dateTime('SubmissionTime')->nullable(); [cite: 33]
             $table->foreignId('StudentID')->constrained('students', 'StudentID')->onDelete('cascade'); // Made by student [cite: 33, 35]
             $table->foreignId('QuizID')->constrained('quizzes', 'QuizID')->onDelete('cascade'); // Made for Quiz [cite: 33, 35]
             $table->boolean('AutoSubmitted')->default(false); [cite: 33]
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
