<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MusicStem;

class StemController extends Controller
{
    protected $stemRepo;

    public function __construct(StemRepositoryInterface $stemRepo)
    {
        $this->stemRepo = $stemRepo;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'category_id' => $request->category,
            'sort' => $request->sort ?? 'latest'
        ];

        $stems = $this->stemRepo->getLibraryStems($filters);

        return view('webapp.streams', compact('stems'));
    }

    public function show($id)
    {
        $stem = MusicStem::with('category')->findOrFail($id);

        if (Auth::check()) {
            $this->stemRepo->logInteraction($id, Auth::id(), 'view');
        }

        return view('webapp.stems_show', compact('stem'));
    }

    public function download($id)
    {
        $stem = MusicStem::findOrFail($id);

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

        $interaction = $this->stemRepo->logInteraction($id, Auth::id(), 'like');

        if (!$interaction) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        return response()->json(['message' => 'Added to your favorites'], 200);
    }

    public function store(Request $request, $threadId)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'stem_file'      => 'required|file|mimes:zip,wav,mp3|max:102400',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string',
        ]);

        $this->stemRepo->uploadStem($threadId, $data);

        return back()->with('success', 'Studio asset published to the Vault!');
    }
}
