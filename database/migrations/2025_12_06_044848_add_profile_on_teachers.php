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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('phone_number', 255);
            $table->string('social_media', 255);
            $table->enum('social_media_type', ['instagram', 'github', 'linkedin', 'other']);
            $table->string('institution', 255);
            $table->enum('status', ['waiting', 'accepted', 'rejected'])->default('waiting');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'social_media', 'social_media_type', 'institution', 'statu']);
        });
    }
};
