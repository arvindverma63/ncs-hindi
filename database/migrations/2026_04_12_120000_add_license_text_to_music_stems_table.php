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
        if (Schema::hasColumn('music_stems', 'license_text')) {
            return;
        }

        Schema::table('music_stems', function (Blueprint $table) {
            $table->text('license_text')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('music_stems', function (Blueprint $table) {
            $table->dropColumn('license_text');
        });
    }
};







