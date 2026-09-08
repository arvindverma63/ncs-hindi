<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_music', function (Blueprint $table) {
            $table->uuid('music_id');
            $table->uuid('category_id');

            $table->primary(['music_id', 'category_id']);
            
            $table->foreign('music_id')
                ->references('id')
                ->on('music_stems')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });

        // Migrate existing category_id references into category_music
        $existingMusic = DB::table('music_stems')
            ->whereNotNull('category_id')
            ->select('id', 'category_id')
            ->get();

        foreach ($existingMusic as $item) {
            $categoryExists = DB::table('categories')->where('id', $item->category_id)->exists();
            if ($categoryExists) {
                DB::table('category_music')->insertOrIgnore([
                    'music_id' => $item->id,
                    'category_id' => $item->category_id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_music');
    }
};
