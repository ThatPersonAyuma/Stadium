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
            $table->dropColumn(['experience', 'rank_id', 'energy', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('energy', false, true)->default(10);
            $table->smallInteger('key', false, true)->default(0);
            $table->integer('experience')->default(0); // default 0 agar tidak null
            $table->foreignId('rank_id')
                ->nullable()
                ->constrained('ranks') // pastikan tabel ini ada
                ->onUpdate('cascade')
                ->onDelete('set null'); // lebih aman daripada cascade delete user
        });
    }
};
