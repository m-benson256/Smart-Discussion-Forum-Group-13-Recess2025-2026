<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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
        $constraintName = sprintf('%s_%s_foreign', $tableName, $columnName);

        $constraintExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();

        if ($constraintExists) {
            return;
        }

        if (! Schema::hasColumn($tableName, $columnName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($columnName, $referenceTable, $referenceColumn) {
            $table->foreign($columnName)->references($referenceColumn)->on($referenceTable)->cascadeOnDelete();
        });
    }

    private function dropForeignKeyIfExists(string $tableName, string $columnName): void
    {
        $constraintName = sprintf('%s_%s_foreign', $tableName, $columnName);

        $constraintExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();

        if (! $constraintExists) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
            $table->dropForeign($constraintName);
        });
    }
};
