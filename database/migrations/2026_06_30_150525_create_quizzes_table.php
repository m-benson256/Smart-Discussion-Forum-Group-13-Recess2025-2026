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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->integer('Duration'); // minutes [cite: 31]
            $table->string('Topic', 200); 
            $table->dateTime('StartTime'); 
            $table->foreignId('CategoryID')->constrained('categories');
            $table->foreignId('LecturerID')->constrained('lecturers', 'LecturerID')->onDelete('cascade'); // Posted by Lecturer [cite: 31, 35]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
