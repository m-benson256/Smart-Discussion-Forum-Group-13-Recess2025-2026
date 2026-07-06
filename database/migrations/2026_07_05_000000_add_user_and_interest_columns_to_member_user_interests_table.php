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
        Schema::table('member_user_interests', function (Blueprint $table) {
            if (! Schema::hasColumn('member_user_interests', 'UserID')) {
                $table->foreignId('UserID')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('member_user_interests', 'InterestID')) {
                $table->foreignId('InterestID')->nullable()->after('UserID')->constrained('user_interests', 'InterestID')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_user_interests', function (Blueprint $table) {
            if (Schema::hasColumn('member_user_interests', 'InterestID')) {
                $table->dropForeign(['InterestID']);
                $table->dropColumn('InterestID');
            }

            if (Schema::hasColumn('member_user_interests', 'UserID')) {
                $table->dropForeign(['UserID']);
                $table->dropColumn('UserID');
            }
        });
    }
};