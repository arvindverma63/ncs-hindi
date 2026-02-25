<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $posts = [
            [
                'id' => 1,
                'title' => '"Tum Mile" (NCS Hindi Edition) - Official Stems Release',
                'author' => 'NCS_Official',
                'category' => 'Announcements',
                'likes' => '2.8k',
                'replies' => '342',
                'time' => '4h ago',
                'is_verified' => true
            ],
            [
                'id' => 2,
                'title' => 'Mixing Desi Vocals: Mastering the Sitar Layering technique',
                'author' => 'Producer_Vibe',
                'category' => 'Tutorials',
                'likes' => '1.1k',
                'replies' => '84',
                'time' => '12h ago',
                'is_verified' => false
            ]
        ];

        return view('webapp.index', compact('posts'));
    }

    public function trending()
    {
        $trendingTracks = [
            [
                'rank' => 1,
                'title' => 'Galliyan Lofi Flip',
                'artist' => 'Ritik_Beats',
                'downloads' => '12.4k',
                'color' => 'amber',
                'image' => 'https://images.unsplash.com/photo-1493225255756-d9584f8606e9?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'rank' => 2,
                'title' => 'Mumbai Underground 808',
                'artist' => 'Dharavi_Don',
                'downloads' => '8.2k',
                'color' => 'red',
                'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'rank' => 3,
                'title' => 'Sitar Trap Stems',
                'artist' => 'Pandit_Flow',
                'downloads' => '5.1k',
                'color' => 'amber',
                'image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=400&q=80'
            ]
        ];

        return view('webapp.trending', compact('trendingTracks'));
    }

    public function streams()
    {
        $stems = [
            [
                'name' => 'Dil Se Vocal Chop Pack',
                'category' => 'Vocals',
                'bpm' => '128',
                'key' => 'Am',
                'size' => '42MB',
                'downloads' => '2.1k'
            ],
            [
                'name' => 'Varanasi Sitar Resonance',
                'category' => 'Acoustic',
                'bpm' => '90',
                'key' => 'Cm',
                'size' => '120MB',
                'downloads' => '1.4k'
            ],
            [
                'name' => 'Desi Drill Basslines Vol 1',
                'category' => 'Bass',
                'bpm' => '142',
                'key' => 'F#m',
                'size' => '88MB',
                'downloads' => '3.9k'
            ]
        ];

        return view('webapp.streams', compact('stems'));
    }
    public function showForum($id)
    {
        // Logic to fetch a specific post and its replies
        $post = [
            'id' => $id,
            'title' => '"Tum Mile" (NCS Hindi Edition) - Official Stems Release',
            'author' => 'NCS_Official',
            'category' => 'Announcements',
            'content' => 'The highly anticipated stems for "Tum Mile" are now available for the community. Use these for your remixes, lofi edits, or live sets. No copyright strikes when using the provided license key in the download folder.',
            'likes' => '2.8k',
            'replies_count' => 3,
            'time' => '4h ago'
        ];

        $replies = [
            ['author' => 'Ritik_Beats', 'time' => '2h ago', 'text' => 'The sub-bass in this stem is incredible. Can\'t wait to flip this!', 'likes' => '42'],
            ['author' => 'Desi_Producer', 'time' => '1h ago', 'text' => 'Is there a MIDI file included for the melody?', 'likes' => '12'],
            ['author' => 'NCS_Official', 'time' => '30m ago', 'text' => '@Desi_Producer Yes, check the "Extra" folder in the zip.', 'likes' => '5'],
        ];

        return view('webapp.forum-show', compact('post', 'replies'));
    }

    public function profile()
    {
        $user = [
            'name' => 'Aaryan_Producer',
            'rank' => 'Level 12 Artist',
            'xp' => 75,
            'bio' => 'Music Producer from Mumbai. Specializing in Hindi Trap and Lo-Fi. NCS Hindi contributor since 2024.',
            'stats' => [
                'uploads' => 24,
                'downloads' => '1.2k',
                'likes' => '5.6k'
            ]
        ];

        $recent_uploads = [
            ['title' => 'Monsoon Melodies Loop Kit', 'date' => '2 days ago', 'type' => 'Stems'],
            ['title' => 'Mumbai Subway 808', 'date' => '1 week ago', 'type' => 'Bass'],
        ];

        return view('webapp.profile.index', compact('user', 'recent_uploads'));
    }
}
