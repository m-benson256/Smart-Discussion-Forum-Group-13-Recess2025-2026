// database/migrations/2026_07_06_100000_add_interest_id_to_topics_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->foreignId('interest_id')
                  ->nullable()
                  ->after('category_id')
                  ->constrained('user_interests', 'InterestID')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign(['interest_id']);
            $table->dropColumn('interest_id');
        });
    }
};