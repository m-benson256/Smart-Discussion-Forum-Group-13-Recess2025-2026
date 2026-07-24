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
        
    Schema::table('quizzes', function (Blueprint $table) {
             $table->timestamp('broadcasted_at')->nullable()->after('start_time');

    //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('broadcasted_at');
 
        //
        });
    }
};
