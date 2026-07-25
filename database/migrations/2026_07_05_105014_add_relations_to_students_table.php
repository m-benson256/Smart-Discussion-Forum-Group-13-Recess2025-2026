<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('students', 'CategoryID')) {
                $table->unsignedBigInteger('CategoryID')->after('user_id');
            }
        });

        $this->addForeignKeyIfMissing('students', 'user_id', 'users', 'id');
        $this->addForeignKeyIfMissing('students', 'CategoryID', 'categories', 'CategoryID');
    }

    public function down(): void
    {
        $this->dropForeignKeyIfExists('students', 'user_id');
        $this->dropForeignKeyIfExists('students', 'CategoryID');

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('students', 'CategoryID')) {
                $table->dropColumn('CategoryID');
            }
        });
    }

    private function addForeignKeyIfMissing(string $tableName, string $columnName, string $referenceTable, string $referenceColumn): void
    {
        if (! Schema::hasColumn($tableName, $columnName)) {
            return;
        }

        $constraintName = sprintf('%s_%s_foreign', $tableName, $columnName);

        try {
            Schema::table($tableName, function (Blueprint $table) use ($columnName, $referenceTable, $referenceColumn) {
                $table->foreign($columnName)->references($referenceColumn)->on($referenceTable)->cascadeOnDelete();
            });
        } catch (\Throwable $e) {
            // Constraint already exists — safe to ignore.
        }
    }

    private function dropForeignKeyIfExists(string $tableName, string $columnName): void
    {
        $constraintName = sprintf('%s_%s_foreign', $tableName, $columnName);

        try {
            Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        } catch (\Throwable $e) {
            // No matching constraint — safe to ignore.
        }
    }
};