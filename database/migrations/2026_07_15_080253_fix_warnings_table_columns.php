<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warnings', function (Blueprint $table) {
            if (!Schema::hasColumn('warnings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            if (!Schema::hasColumn('warnings', 'warning_number')) {
                $table->integer('warning_number')->default(1);
            }
            if (!Schema::hasColumn('warnings', 'reason')) {
                $table->string('reason')->nullable();
            }
            if (!Schema::hasColumn('warnings', 'issued_at')) {
                $table->date('issued_at')->nullable();
            }
            if (!Schema::hasColumn('warnings', 'expires_at')) {
                $table->date('expires_at')->nullable();
            }
            if (!Schema::hasColumn('warnings', 'status')) {
                $table->enum('status', ['active', 'pending', 'resolved'])->default('active');
            }
        });

        // Foreign key needs its own guard — checking for an existing FK constraint
        // is trickier than columns, so we check via information_schema instead.
        $foreignKeyExists = collect(\DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'warnings'
            AND COLUMN_NAME = 'user_id'
            AND REFERENCED_TABLE_NAME = 'users'
        "))->isNotEmpty();

        if (!$foreignKeyExists) {
            Schema::table('warnings', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('warnings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'warning_number', 'reason', 'issued_at', 'expires_at', 'status']);
        });
    }
};