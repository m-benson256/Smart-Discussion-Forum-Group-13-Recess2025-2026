<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            if (!Schema::hasColumn('topics', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('topics', 'group_id')) {
                $table->foreignId('group_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('groups')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('topics', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('group_id')
                    ->constrained('categories', 'CategoryID')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('topics', 'title')) {
                $table->string('title');
            }

            if (!Schema::hasColumn('topics', 'content')) {
                $table->text('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['user_id', 'group_id', 'category_id', 'title', 'content']);
        });
    }
};