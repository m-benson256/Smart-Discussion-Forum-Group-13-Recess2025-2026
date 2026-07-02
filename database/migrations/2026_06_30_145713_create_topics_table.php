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
        Schema::create('topics', function (Blueprint $table) {
            $table->id('TopicID'); // PK [cite: 25]
            $table->string('Title', 200); [cite: 25]
            $table->string('Category', 100)->nullable(); // ML-classified category [cite: 8, 25]
            $table->foreignId('GroupID')->constrained('groups', 'GroupID')->onDelete('cascade'); // Relationship group has topics [cite: 35]
            $table->foreignId('UserID')->constrained('members', 'UserID')->onDelete('cascade'); // Created by Member [cite: 35]
            $table->timestamp('DateOfCreation')->useCurrent(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
