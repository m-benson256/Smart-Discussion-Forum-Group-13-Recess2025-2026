<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        if (DB::getDriverName() === 'pgsql') {
            try {
                DB::statement("ALTER TABLE users DROP CONSTRAINT users_status_check");
            } catch (\Throwable $e) {
                // Constraint may not exist yet, or have a different name — safe to ignore.
            }

            DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('active', 'inactive', 'blocked'))");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive', 'blocked') NOT NULL DEFAULT 'active'");
        }
    }

    public function down()
    {
        if (DB::getDriverName() === 'pgsql') {
            try {
                DB::statement("ALTER TABLE users DROP CONSTRAINT users_status_check");
            } catch (\Throwable $e) {
                //
            }

            DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('active', 'inactive'))");
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");
        }
    }
};