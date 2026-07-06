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
        $this->repointForeignKey('students', 'StudentID', 'users', 'id');
        $this->repointForeignKey('lecturers', 'LecturerID', 'users', 'id');
        $this->repointForeignKey('administrators', 'AdminID', 'users', 'id');
        $this->repointForeignKey('topics', 'UserID', 'users', 'id');
        $this->repointForeignKey('warnings', 'UserID', 'users', 'id');

        Schema::dropIfExists('members');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Name', 100);
            $table->string('Email', 100)->unique();
            $table->string('Password', 255);
            $table->enum('role', ['student', 'lecturer', 'admin'])->default('student');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('StudentID')->references('UserID')->on('members')->cascadeOnDelete();
        });

        Schema::table('lecturers', function (Blueprint $table) {
            $table->foreign('LecturerID')->references('UserID')->on('members')->cascadeOnDelete();
        });

        Schema::table('administrators', function (Blueprint $table) {
            $table->foreign('AdminID')->references('UserID')->on('members')->cascadeOnDelete();
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->foreign('UserID')->references('UserID')->on('members')->cascadeOnDelete();
        });

        Schema::table('warnings', function (Blueprint $table) {
            $table->foreign('UserID')->references('UserID')->on('members')->cascadeOnDelete();
        });
    }

    private function repointForeignKey(string $tableName, string $columnName, string $referenceTable, string $referenceColumn): void
    {
        if (! Schema::hasColumn($tableName, $columnName)) {
            return;
        }

        $constraintName = sprintf('%s_%s_foreign', $tableName, strtolower($columnName));

        $constraintExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();

        if ($constraintExists) {
            Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        }

        Schema::table($tableName, function (Blueprint $table) use ($columnName, $referenceTable, $referenceColumn) {
            $table->foreign($columnName)->references($referenceColumn)->on($referenceTable)->cascadeOnDelete();
        });
    }
};