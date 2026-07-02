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
        Schema::create('group_members', function (Blueprint $table) {
<<<<<<< HEAD
            $table->id();
=======
             $table->id();
>>>>>>> 91f1542f290a84aadc81093b0d6628d3d48ee384
            $table->foreignId('member_id')->constrained('members', 'UserID')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups', 'GroupID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
