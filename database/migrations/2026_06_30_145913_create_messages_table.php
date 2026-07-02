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
             $table->id('PostID'); // PK [cite: 23]
             $table->text('Content'); [cite: 23]
             $table->foreignId('TopicID')->constrained('topics', 'TopicID')->onDelete('cascade'); // Belongs to topic [cite: 35]
             $table->foreignId('UserID')->constrained('members', 'UserID')->onDelete('cascade'); // Sent by Member [cite: 35]
             $table->timestamp('DateOfPost')->useCurrent(); [cite: 23]
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


