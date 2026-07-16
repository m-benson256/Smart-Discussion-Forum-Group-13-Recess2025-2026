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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->enum('role', ['student', 'lecturer', 'admin'])
                ->default('student')
                ->after('email');

            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->after('role');

            $table->timestamp('last_activity_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['role', 'status', 'last_activity_at']);
        });
    }
};
