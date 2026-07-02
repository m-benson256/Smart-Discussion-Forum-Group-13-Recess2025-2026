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
        Schema::create('students', function (Blueprint $table) {
            $table->foreignId('StudentID')->primary()->constrained('members', 'UserID')->onDelete('cascade'); // PK & FK [cite: 16]
            $table->string('Category', 100); [cite: 16]
          // Added nullable placeholder for category_id relationship reference until you build the standalone Categories table
            $table->foreignId('CategoryID')->nullable(); [cite: 16]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
