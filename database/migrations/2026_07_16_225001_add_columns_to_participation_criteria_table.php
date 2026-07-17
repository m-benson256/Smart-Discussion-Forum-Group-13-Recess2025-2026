<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participation_criteria', function (Blueprint $table) {
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->integer('points_per_message')->default(1);
            $table->integer('points_per_reaction_given')->default(0);
            $table->integer('max_score')->default(100);
        });
    }

    public function down(): void
    {
        Schema::table('participation_criteria', function (Blueprint $table) {
            $table->dropForeign(['lecturer_id']);
            $table->dropColumn(['lecturer_id', 'points_per_message', 'points_per_reaction_given', 'max_score']);
        });
    }
};