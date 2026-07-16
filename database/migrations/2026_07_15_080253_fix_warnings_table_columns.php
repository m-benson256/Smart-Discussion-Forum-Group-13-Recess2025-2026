<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('warnings', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable();
        $table->integer('warning_number')->default(1);
        $table->string('reason')->nullable();
        $table->date('issued_at')->nullable();
        $table->date('expires_at')->nullable();
        $table->enum('status', ['active', 'pending', 'resolved'])->default('active');

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('warnings', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn(['user_id', 'warning_number', 'reason', 'issued_at', 'expires_at', 'status']);
    });
}
};