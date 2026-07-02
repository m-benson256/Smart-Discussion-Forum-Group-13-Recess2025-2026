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
<<<<<<< HEAD
             $table->id('ID'); // PK [cite: 28]
=======
           $table->id('ID'); // PK [cite: 28]
>>>>>>> 91f1542f290a84aadc81093b0d6628d3d48ee384
             $table->string('Title', 200); [cite: 28]
             $table->timestamp('TimeOfAnnouncement')->useCurrent(); [cite: 28]
             $table->foreignId('QuizID')->constrained('quizzes', 'QuizID')->onDelete('cascade'); // Announcement about Quiz [cite: 28, 35]
             $table->timestamps();
<<<<<<< HEAD
            
=======
>>>>>>> 91f1542f290a84aadc81093b0d6628d3d48ee384
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
