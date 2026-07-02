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
        Schema::create('groups', function (Blueprint $table) {
<<<<<<< HEAD
            $table->id('GroupID'); // PK [cite: 21]
=======
             $table->id('GroupID'); // PK [cite: 21]
>>>>>>> 91f1542f290a84aadc81093b0d6628d3d48ee384
            $table->string('Name', 100); [cite: 21]
            $table->text('Description')->nullable(); [cite: 21]
            $table->integer('No_of_members')->default(0); [cite: 21]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
