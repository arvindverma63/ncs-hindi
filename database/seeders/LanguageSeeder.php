<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Str;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'Hindi',
            'English',
            'Punjabi',
            'Bengali',
            'Tamil',
            'Telugu',
            'Marathi',
            'Gujarati',
            'Kannada',
            'Malayalam',
            'Urdu',
            'Instrumental',
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['slug' => Str::slug($language)],
                ['name' => $language, 'is_active' => true]
            );
        }
    }
}
