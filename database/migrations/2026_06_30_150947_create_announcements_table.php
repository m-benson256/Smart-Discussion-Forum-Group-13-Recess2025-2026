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
        Schema::create('announcements', function (Blueprint $table) {
             $table->id('ID'); 
             $table->string('Title', 200); 
             $table->timestamp('TimeOfAnnouncement')->useCurrent(); 
             $table->foreignId('QuizID')->constrained('quizzes', 'QuizID')->onDelete('cascade'); // Announcement about Quiz [cite: 28, 35]
             $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
