// database/migrations/2026_07_05_101500_add_columns_to_quiz_attempts_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->foreignId('quiz_id')
                  ->after('id')
                  ->constrained('quizzes')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->after('quiz_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->unsignedInteger('score')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->unique(['quiz_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropUnique(['quiz_id', 'user_id']);
            $table->dropForeign(['quiz_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['quiz_id', 'user_id', 'score', 'started_at', 'submitted_at']);
        });
    }
};