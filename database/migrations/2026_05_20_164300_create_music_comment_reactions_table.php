<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('music_comment_reactions')) {
            return;
        }

        Schema::create('music_comment_reactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('music_comment_id', 36)->index();
            $table->string('user_id', 36)->nullable()->index();
            $table->string('ip_address')->nullable();
            $table->string('type'); // like, love, haha, wow, sad, angry
            $table->timestamps();

            $table->foreign('music_comment_id')->references('id')->on('music_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_comment_reactions');
    }
};
