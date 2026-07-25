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
        Schema::table('music_stems', function (Blueprint $table) {
            $table->string('audio_file')->nullable()->after('file_path');
            $table->boolean('can_play_on_website')->default(true)->after('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('music_stems', function (Blueprint $table) {
            $table->dropColumn(['audio_file', 'can_play_on_website']);
        });
    }
};
