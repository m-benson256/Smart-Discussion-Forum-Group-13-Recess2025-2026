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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->foreignId('LecturerID')->primary()->constrained('members', 'UserID')->onDelete('cascade'); // PK & FK [cite: 17]
            $table->string('Department', 100); [cite: 17]
            $table->string('DegreeType', 50); [cite: 17]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
