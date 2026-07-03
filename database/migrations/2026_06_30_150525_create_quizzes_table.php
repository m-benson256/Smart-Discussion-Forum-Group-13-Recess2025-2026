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
        // In the up() method
Schema::create('quizzes', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->unsignedBigInteger('lecturer_id')->nullable();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->dateTime('start_date');
    $table->dateTime('end_date');
    $table->integer('duration_minutes');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    // NO FOREIGN KEYS AT ALL
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
