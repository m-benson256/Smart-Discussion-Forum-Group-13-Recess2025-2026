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
        Schema::table('Topics', function (Blueprint $table) {
             $table->foreignId('user_id')
                  ->after('id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('group_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('groups')
                  ->nullOnDelete();

            $table->foreignId('category_id')
                  ->nullable()
                  ->after('group_id')
                  ->constrained('categories', 'CategoryID')
                  ->nullOnDelete();

            $table->string('title');
            $table->text('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Topics', function (Blueprint $table) {
            //
             $table->dropForeign(['user_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['user_id', 'group_id', 'category_id', 'title', 'content']);
        
        });
    }
};
