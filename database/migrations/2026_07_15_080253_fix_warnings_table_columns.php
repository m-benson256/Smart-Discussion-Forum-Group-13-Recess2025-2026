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

        // Try to add the foreign key; safe to ignore failure if it already exists.
        try {
            Schema::table('warnings', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            // Constraint already exists — safe to ignore.
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