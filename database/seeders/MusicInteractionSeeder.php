<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Music;
use App\Models\MusicInteraction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MusicInteractionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure we have some categories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $catData = [
                ['name' => 'Electronic EDM', 'slug' => 'electronic-edm', 'icon_class' => 'mdi:music-box-outline', 'is_active' => true],
                ['name' => 'Chill Lo-Fi', 'slug' => 'chill-lo-fi', 'icon_class' => 'mdi:coffee-outline', 'is_active' => true],
                ['name' => 'Cinematic Orchestral', 'slug' => 'cinematic-orchestral', 'icon_class' => 'mdi:movie-roll', 'is_active' => true],
                ['name' => 'Hip Hop Beats', 'slug' => 'hip-hop-beats', 'icon_class' => 'mdi:microphone-variant', 'is_active' => true],
            ];
            foreach ($catData as $data) {
                Category::create($data);
            }
            $categories = Category::all();
        }

        // 2. Ensure we have some users
        $users = User::all();
        if ($users->isEmpty()) {
            // Create a default admin/user if none exists
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@ncshindi.com',
                'password' => bcrypt('password'),
                'role' => '1',
            ]);
            User::create([
                'name' => 'Regular Listener',
                'email' => 'listener@ncshindi.com',
                'password' => bcrypt('password'),
                'role' => '0',
            ]);
            $users = User::all();
        }

        // 3. Ensure we have music stems
        $stems = Music::all();
        if ($stems->isEmpty()) {
            $musicData = [
                [
                    'title' => 'Infinite Sky',
                    'artist_name' => 'DJ Aero',
                    'album_movie_name' => 'Cloud Nine',
                    'language' => 'English',
                    'description' => 'A high-energy electronic dance track with soaring synthesizers.',
                    'bpm' => 128,
                    'music_key' => 'F Minor',
                    'featured_image' => 'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?w=400&q=80',
                    'is_public' => true,
                ],
                [
                    'title' => 'Midnight Coffee',
                    'artist_name' => 'Lofi Dreamer',
                    'album_movie_name' => 'Urban Cafe Sessions',
                    'language' => 'Instrumental',
                    'description' => 'Relaxing jazz-infused lo-fi beats perfect for studying or working.',
                    'bpm' => 80,
                    'music_key' => 'C Major',
                    'featured_image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=400&q=80',
                    'is_public' => true,
                ],
                [
                    'title' => 'Epic Quest',
                    'artist_name' => 'Symphonic Force',
                    'album_movie_name' => 'Fantasy Chronicles',
                    'language' => 'Instrumental',
                    'description' => 'Orchestral masterpiece with pounding percussion and heroic brass.',
                    'bpm' => 110,
                    'music_key' => 'D Minor',
                    'featured_image' => 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=400&q=80',
                    'is_public' => true,
                ],
                [
                    'title' => 'Neon Streets',
                    'artist_name' => 'RetroRunner',
                    'album_movie_name' => 'Synthwave Odyssey',
                    'language' => 'Instrumental',
                    'description' => '80s retro synthwave track capturing the mood of a driving night.',
                    'bpm' => 120,
                    'music_key' => 'A Minor',
                    'featured_image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=400&q=80',
                    'is_public' => true,
                ],
                [
                    'title' => 'Desi Vibe',
                    'artist_name' => 'Raga Beatmaker',
                    'album_movie_name' => 'Folk Fusion',
                    'language' => 'Hindi',
                    'description' => 'A modern hip-hop beat fused with classical Indian instruments.',
                    'bpm' => 95,
                    'music_key' => 'G Major',
                    'featured_image' => 'https://images.unsplash.com/photo-1487180144351-b8472da7a4c3?w=400&q=80',
                    'is_public' => true,
                ],
            ];

            foreach ($musicData as $index => $data) {
                $data['category_id'] = $categories[$index % $categories->count()]->id;
                $data['view_count'] = 0;
                $data['download_count'] = 0;
                $data['like_count'] = 0;
                $data['slug'] = Music::uniqueSlug($data['title']);
                Music::create($data);
            }
            $stems = Music::all();
        }

        // 4. Clean up any existing interaction records
        MusicInteraction::truncate();

        // 5. Generate interaction records over the last 30 days
        $types = ['view', 'view', 'view', 'download', 'like']; // Weight views higher
        $ips = [];
        for ($i = 0; $i < 50; $i++) {
            $ips[] = '192.168.1.' . rand(1, 254);
        }

        // Past 30 days
        for ($day = 30; $day >= 0; $day--) {
            $currentDate = Carbon::now()->subDays($day);
            
            // Random number of events per day (higher on weekends)
            $isWeekend = $currentDate->isWeekend();
            $baseEvents = $isWeekend ? rand(30, 60) : rand(15, 35);

            // If it is "today", let's distribute it hourly
            if ($day === 0) {
                // Today: generate hourly events
                for ($hour = 0; $hour < Carbon::now()->hour; $hour++) {
                    $eventsPerHour = rand(2, 8);
                    for ($e = 0; $e < $eventsPerHour; $e++) {
                        $stem = $stems->random();
                        $type = $types[array_rand($types)];
                        $ip = $ips[array_rand($ips)];
                        $user = rand(1, 10) > 7 ? $users->random() : null;
                        
                        $createdAt = Carbon::now()->startOfDay()->addHours($hour)->addMinutes(rand(0, 59));

                        MusicInteraction::create([
                            'user_id' => $user ? $user->id : null,
                            'stem_id' => $stem->id,
                            'type' => $type,
                            'ip_address' => $ip,
                            'created_at' => $createdAt,
                        ]);
                    }
                }
            } else {
                // Past days: generate daily events
                for ($e = 0; $e < $baseEvents; $e++) {
                    $stem = $stems->random();
                    $type = $types[array_rand($types)];
                    $ip = $ips[array_rand($ips)];
                    $user = rand(1, 10) > 7 ? $users->random() : null;
                    
                    $createdAt = (clone $currentDate)->startOfDay()
                        ->addHours(rand(0, 23))
                        ->addMinutes(rand(0, 59))
                        ->addSeconds(rand(0, 59));

                    MusicInteraction::create([
                        'user_id' => $user ? $user->id : null,
                        'stem_id' => $stem->id,
                        'type' => $type,
                        'ip_address' => $ip,
                        'created_at' => $createdAt,
                    ]);
                }
            }
        }

        // 6. Sync cumulative counts on the music stems table
        foreach ($stems as $stem) {
            $stem->view_count = MusicInteraction::where('stem_id', $stem->id)->where('type', 'view')->count();
            $stem->download_count = MusicInteraction::where('stem_id', $stem->id)->where('type', 'download')->count();
            $stem->like_count = MusicInteraction::where('stem_id', $stem->id)->where('type', 'like')->count();
            $stem->save();
        }
    }
}
