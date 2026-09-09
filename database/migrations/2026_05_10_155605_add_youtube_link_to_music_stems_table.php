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
        if (Schema::hasColumn('music_stems', 'youtube_link')) {
            return;
        }

        Schema::table('music_stems', function (Blueprint $table) {
            $table->string('youtube_link')->nullable()->after('mega_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('music_stems', function (Blueprint $table) {
            $table->dropColumn('youtube_link');
        });
    }
};







