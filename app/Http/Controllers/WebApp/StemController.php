<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StemController extends Controller
{
    protected $stemRepo;

    public function __construct(StemRepositoryInterface $stemRepo)
    {
        $this->stemRepo = $stemRepo;
    }

    public function store(Request $request, $threadId)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'stem_file'      => 'required|file|mimes:zip,wav,mp3|max:102400', // 100MB
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string',
        ]);

        $this->stemRepo->uploadStem($threadId, $data);

        return back()->with('success', 'Studio asset published to the Vault!');
    }

    public function download($id)
    {
        $stem = \App\Models\MusicStem::findOrFail($id);

        // Log the download interaction for the authenticated producer
        if (Auth::check()) {
            $this->stemRepo->logInteraction($id, Auth::id(), 'download');
        }

        return Storage::disk('public')->download($stem->file_path, $stem->file_name);
    }

    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        // Use the repository to log the 'like' interaction
        $interaction = $this->stemRepo->logInteraction($id, Auth::id(), 'like');

        if (!$interaction) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        return response()->json(['message' => 'Added to your favorites'], 200);
    }
}
