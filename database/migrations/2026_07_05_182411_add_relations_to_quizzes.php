// database/migrations/2026_07_05_100000_add_columns_to_quizzes_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('created_by')
                  ->after('id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->nullable()
                  ->after('created_by')
                  ->constrained('categories', 'CategoryID')
                  ->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->unsignedInteger('total_marks')->default(100);
            $table->unsignedTinyInteger('passing_score')->default(70);
            $table->boolean('shuffle_questions')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'created_by', 'category_id', 'title', 'description',
                'start_time', 'duration_minutes', 'total_marks',
                'passing_score', 'shuffle_questions', 'status'
            ]);
        });
    }
};