<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('Title', 200)->nullable()->change();
            $table->foreignId('QuizID')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('Title', 200)->nullable(false)->change();
            $table->foreignId('QuizID')->nullable(false)->change();
        });
    }
};