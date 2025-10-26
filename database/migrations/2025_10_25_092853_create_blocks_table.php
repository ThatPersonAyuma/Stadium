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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['text', 'image', 'code', 'quiz', 'gif', 'video'])->default('text'); // quiz consist of question and choice
            $table->jsonb('data');
            $table->unsignedSmallInteger('order_index');
            $table->foreignId('card_id')->constrained('cards')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
