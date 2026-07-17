<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('participation_criteria', function (Blueprint $table) {
        if (!Schema::hasColumn('participation_criteria', 'lecturer_id')) {
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
        }
        if (!Schema::hasColumn('participation_criteria', 'points_per_message')) {
            $table->integer('points_per_message')->default(1);
        }
        if (!Schema::hasColumn('participation_criteria', 'points_per_reaction_given')) {
            $table->integer('points_per_reaction_given')->default(0);
        }
        if (!Schema::hasColumn('participation_criteria', 'max_score')) {
            $table->integer('max_score')->default(100);
        }
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