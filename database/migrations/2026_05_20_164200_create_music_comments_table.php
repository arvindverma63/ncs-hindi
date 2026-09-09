<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('music_comments')) {
            return;
        }

        Schema::create('music_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('music_id', 36)->index();
            $table->string('user_id', 36)->nullable()->index();
            $table->string('parent_id', 36)->nullable()->index();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->longText('comment');
            $table->string('status')->default('approved');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('music_id')->references('id')->on('music_stems')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('music_comments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_comments');
    }
};
