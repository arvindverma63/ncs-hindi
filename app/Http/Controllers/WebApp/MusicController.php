<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\MusicInteraction;
use App\Repositories\Contracts\MusicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Music;
use App\Models\MusicComment;

class MusicController extends Controller
{
    protected $musicRepo;

    public function __construct(MusicRepositoryInterface $musicRepo)
    {
        $this->musicRepo = $musicRepo;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'category_id' => $request->category,
            'sort' => $request->sort ?? 'latest'
        ];

        $music = $this->musicRepo->getLibraryMusic($filters);

        return view('webapp.streams', compact('music'));
    }

    public function genre(Request $request, $slug)
    {
        $category = \App\Models\Category::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        $filters = [
            'search' => $request->search,
            'category_id' => $category->id,
            'sort' => $request->sort ?? 'latest'
        ];

        $music = $this->musicRepo->getLibraryMusic($filters);

        return view('webapp.streams', compact('music', 'category'));
    }

    public function show($slug)
    {
        $music = Music::with('category')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // Log the view interaction (works for both guests and authenticated users now)
        $this->musicRepo->logInteraction($music->id, Auth::id(), 'view');

        // Load top-level approved comments with replies, user, and reactions
        $comments = MusicComment::where('music_id', $music->id)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with([
                'user',
                'reactions',
                'replies.user',
                'replies.reactions',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $userReactions = [];
        if (Auth::check()) {
            $commentIds = $comments->pluck('id')
                ->merge($comments->flatMap(fn($c) => $c->replies->pluck('id')))
                ->unique();
            \App\Models\MusicCommentReaction::whereIn('music_comment_id', $commentIds)
                ->where('user_id', Auth::id())
                ->get()
                ->each(function ($r) use (&$userReactions) {
                    $userReactions[$r->music_comment_id] = $r->type;
                });
        }

        return view('webapp.music_show', compact('music', 'comments', 'userReactions'));
    }

    public function download($id)
    {
        \Illuminate\Support\Facades\Log::info("Download route hit for ID: $id");
        $music = Music::findOrFail($id);

        $this->musicRepo->logInteraction($id, Auth::id(), 'download');

        if ($music->mega_link && filter_var($music->mega_link, FILTER_VALIDATE_URL)) {
            return redirect()->away($music->mega_link);
        }

        if ($music->file_path && filter_var($music->file_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($music->file_path);
        }

        if (!$music->file_path) {
            abort(404);
        }

        $downloadName = $music->file_name ?: basename($music->file_path);

        return Storage::disk('public')->download($music->file_path, $downloadName);
    }

    public function incrementDownload($id)
    {
        \Illuminate\Support\Facades\Log::info("Increment download hit for ID: $id");
        $music = Music::findOrFail($id);
        $this->musicRepo->logInteraction($id, Auth::id(), 'download');
        return response()->json([
            'success' => true,
            'count' => $music->download_count
        ]);
    }

    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        return DB::transaction(function () use ($id) {
            $userId = Auth::id();
            $music = Music::lockForUpdate()->findOrFail($id);

            $interaction = MusicInteraction::where('user_id', $userId)
                ->where('stem_id', $id)
                ->where('type', 'like')
                ->first();

            if ($interaction) {
                $interaction->delete();

                if ($music->like_count > 0) {
                    $music->decrement('like_count');
                }

                $music->refresh();

                return response()->json([
                    'liked' => false,
                    'count' => $music->like_count,
                    'message' => 'Removed from your likes.',
                ]);
            }

            MusicInteraction::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $id,
                'type' => 'like',
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);

            $music->increment('like_count');
            $music->refresh();

            return response()->json([
                'liked' => true,
                'count' => $music->like_count,
                'message' => 'Added to your likes.',
            ]);
        });
    }

    public function store(Request $request, $threadId)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'music_file'      => 'required|file|mimes:zip,wav,mp3|max:102400',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string',
        ]);

        $this->musicRepo->uploadMusic($threadId, $data);

        return back()->with('success', 'Studio asset published to the Vault!');
    }
}







