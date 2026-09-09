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
        try {
            Schema::table('stem_interactions', function (Blueprint $table) {
                $table->char('user_id', 36)->nullable()->change();
                $table->enum('type', ['like', 'download', 'view'])->change();
            });
        } catch (\Throwable $e) {
            // Ignore if already changed or doctrine DBAL restriction
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stem_interactions', function (Blueprint $table) {
            $table->char('user_id', 36)->nullable(false)->change();
            $table->enum('type', ['like', 'download'])->change();
        });
    }
};







