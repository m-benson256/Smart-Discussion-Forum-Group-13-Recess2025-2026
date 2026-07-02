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
        Schema::create('messages', function (Blueprint $table) {
             $table->id('PostID'); 
             $table->text('Content'); 
             $table->foreignId('TopicID')->constrained('topics', 'TopicID')->onDelete('cascade'); 
             $table->foreignId('UserID')->constrained('members', 'UserID')->onDelete('cascade'); 
             $table->timestamp('DateOfPost')->useCurrent(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};


