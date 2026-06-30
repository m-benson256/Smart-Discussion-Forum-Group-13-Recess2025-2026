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
        Schema::create('members', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Name', 100);
            $table->string('Email', 100)->unique();
            $table->string('Password', 255);
            $table->enum('role', ['student', 'lecturer', 'admin'])->default('student');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_activity_at')->nullable();
            $table->string('user_interests', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
