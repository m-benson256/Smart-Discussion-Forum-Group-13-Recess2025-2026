<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('student_id', 50)->unique()->nullable();
            $table->string('department', 100)->nullable();
            $table->string('year_of_study', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('warning_count')->default(0);
            $table->boolean('is_blacklisted')->default(false);
            $table->timestamp('blacklisted_until')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};