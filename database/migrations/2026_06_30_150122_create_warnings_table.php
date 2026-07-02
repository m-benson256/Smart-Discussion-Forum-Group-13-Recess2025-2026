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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id('WarningID'); // PK [cite: 27]
            $table->integer('WarningNumber'); [cite: 27]
            $table->foreignId('UserID')->constrained('members', 'UserID')->onDelete('cascade'); // Given to Member [cite: 27, 35]
            $table->foreignId('IssuedBy')->constrained('administrators', 'AdminID')->onDelete('cascade'); // Issued by Admin [cite: 27, 35]
            $table->timestamp('IssuedAt')->useCurrent(); [cite: 27]
            $table->dateTime('Deadline'); [cite: 27]
            $table->enum('Status', ['pending', 'resolved', 'expired'])->default('pending'); [cite: 27]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};
