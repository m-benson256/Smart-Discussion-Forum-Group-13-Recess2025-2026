// database/migrations/2026_07_03_110000_add_columns_to_messages_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('topic_id')
                ->after('id')
                ->constrained('topics')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->after('topic_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('body');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['topic_id', 'user_id', 'body', 'deleted_at']);
        });
    }
};
