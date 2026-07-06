<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('quiz_attempts')
            ->whereNull('submitted_at')
            ->whereNotNull('score')
            ->update([
                'submitted_at' => DB::raw('COALESCE(updated_at, started_at, CURRENT_TIMESTAMP)'),
            ]);
    }

    public function down(): void
    {
        // Intentionally left blank. This backfill only restores missing timestamps.
    }
};
