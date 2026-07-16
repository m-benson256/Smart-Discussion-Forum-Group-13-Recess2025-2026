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
        Schema::table('lecturers', function (Blueprint $table) {
            // 1. Adds the foreign key right after the primary 'id'
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 2. Contact number (using string to preserve leading zeros safely)
            $table->string('contact', 15)->after('user_id');

            // 3. Degree Type (Undergraduate, Masters, PhD)
            $table->string('DegreeType', 50)->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            // Drop the foreign key relationship first, then drop the columns
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'contact', 'DegreeType']);
        });
    }
};
