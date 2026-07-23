<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends migration
{
    public function up(): void
{
    Schema::table('group_members', function (Blueprint $table) {
        if (!Schema::hasColumn('group_members', 'inactivity_warning_count')) {
            $table->unsignedTinyInteger('inactivity_warning_count')->default(0)->after('group_id');
        }
        if (!Schema::hasColumn('group_members', 'last_warned_at')) {
            $table->timestamp('last_warned_at')->nullable()->after('inactivity_warning_count');
        }
        if (!Schema::hasColumn('group_members', 'status')) {
            $table->enum('status', ['active', 'blacklisted'])->default('active')->after('last_warned_at');
        }
        if (!Schema::hasColumn('group_members', 'blacklisted_until')) {
            $table->timestamp('blacklisted_until')->nullable()->after('status');
        }
    });
}

public function down(): void
{
    Schema::table('group_members', function (Blueprint $table) {
        $table->dropColumn(['inactivity_warning_count', 'last_warned_at', 'status', 'blacklisted_until']);
    });
}
};
