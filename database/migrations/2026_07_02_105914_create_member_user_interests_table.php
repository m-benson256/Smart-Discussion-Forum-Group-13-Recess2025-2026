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
        Schema::create('member_user_interests', function (Blueprint $table) {
            $table->foreignId('UserID')->constrained('users')->onDelete('cascade');
            $table->foreignId('InterestID')->constrained('user_interests', 'InterestID')->onDelete('cascade');
            $table->timestamps(); // optional — remove if you don't need to track when interest was added
            $table->primary(['UserID', 'InterestID']); // composite PK, prevents duplicate pairings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_user_interests');
    }
};
