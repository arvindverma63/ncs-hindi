<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ForumThread;
use App\Models\Music;
use App\Models\MusicInteraction;
use App\Models\User;
use App\Models\NcsCreditHistory;
use App\Repositories\Contracts\ForumRepositoryInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\Contracts\MusicRepositoryInterface;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PageController extends Controller
{
    protected $forumRepo;
    protected $musicRepo;
    protected $profileRepo;
    protected $settingService;

    public function __construct(
        ForumRepositoryInterface $forumRepo,
        MusicRepositoryInterface $musicRepo,
        ProfileRepositoryInterface $profileRepo,
        SettingService $settingService
    ) {
        $this->forumRepo = $forumRepo;
        $this->musicRepo = $musicRepo;
        $this->profileRepo = $profileRepo;
        $this->settingService = $settingService;
    }

    public function index()
    {
        $posts = $this->forumRepo->getAllThreads(5);
        $trendingSongs = $this->musicRepo->getTrendingMusic(['sort' => 'downloads', 'per_page' => 6]);
        return view('webapp.index', compact('posts', 'trendingSongs'));
    }

    public function trending(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category_id' => $request->get('category'),
            'sort' => $request->get('sort', 'downloads'),
            'per_page' => $request->get('per_page', 20),
        ];

        $trendingStems = $this->musicRepo->getTrendingMusic($filters);
        $featuredStem = \App\Models\Music::where('is_public', true)->latest()->first();
        $topCreators = $this->musicRepo->getTrendingCreators($filters);
        $trendingStats = $this->musicRepo->getTrendingStats($filters);
        $categories = Category::where('is_active', 1)
            ->withCount([
                'music as stems_count' => function ($query) use ($filters) {
                    $query->where('is_public', true);
                    if (!empty($filters['search'])) {
                        $search = $filters['search'];
                        $query->where(function ($q) use ($search) {
                            $q->where('title', 'LIKE', "%{$search}%")
                                ->orWhere('artist_name', 'LIKE', "%{$search}%")
                                ->orWhere('album_movie_name', 'LIKE', "%{$search}%")
                                ->orWhere('tags_keywords', 'LIKE', "%{$search}%");
                        });
                    }
                },
                'music as music_count' => function ($query) use ($filters) {
                    $query->where('is_public', true);
                    if (!empty($filters['search'])) {
                        $search = $filters['search'];
                        $query->where(function ($q) use ($search) {
                            $q->where('title', 'LIKE', "%{$search}%")
                                ->orWhere('artist_name', 'LIKE', "%{$search}%")
                                ->orWhere('album_movie_name', 'LIKE', "%{$search}%")
                                ->orWhere('tags_keywords', 'LIKE', "%{$search}%");
                        });
                    }
                }
            ])
            ->orderBy('name')
            ->get();

        return view('webapp.trending', compact(
            'trendingStems',
            'featuredStem',
            'topCreators',
            'trendingStats',
            'categories',
            'filters'
        ));
    }

    public function streams()
    {
        $music = $this->musicRepo->getLibraryMusic();
        return view('webapp.streams', compact('music'));
    }

    public function profile()
    {
        $user = User::with('profile')->findOrFail(auth()->id());
        $profile = $user->profile;

        $likedSongs = $this->getUserStemActivity($user->id, 'like', 6);
        $viewedSongs = $this->getUserStemActivity($user->id, 'view', 6);
        $downloadedSongs = $this->getUserStemActivity($user->id, 'download', 6);

        $profileStats = [
            'liked' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'like')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'viewed' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'view')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'downloaded' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'download')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'uploads' => ForumThread::query()->where('user_id', $user->id)->count(),
        ];

        return view('webapp.profile.index', compact(
            'user',
            'profile',
            'likedSongs',
            'viewedSongs',
            'downloadedSongs',
            'profileStats'
        ));
    }

    public function faq()
    {
        $page = [
            'title' => $this->settingService->get('faq_page_title', 'FAQ / Legal Guides'),
            'intro' => $this->settingService->get('faq_page_intro', 'Answers to common questions and the rules that apply when using NCS Hindi music.'),
            'faq_content' => $this->settingService->get('faq_page_content', ''),
            'legal_title' => $this->settingService->get('legal_page_title', 'Legal Guides'),
            'legal_intro' => $this->settingService->get('legal_page_intro', 'Simple usage guidance for creators, brands, and community members.'),
            'legal_content' => $this->settingService->get('legal_page_content', ''),
        ];

        return view('webapp.faq', compact('page'));
    }

    public function editProfile()
    {
        $profile = $this->profileRepo->findByUserId(auth()->id());
        return view('webapp.profile.edit', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'studio_name'   => 'required|string|max:255',
            'bio'           => 'nullable|string|max:1000',
            'website_url'   => 'nullable|url',
            'instagram_url' => 'nullable|string',
        ]);

        $this->profileRepo->updateProfile(auth()->id(), $data);

        return redirect()->route('webapp.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function show($slug)
    {
        // Eager load 'author' and the author's 'profile' relationship
        $post = ForumThread::with(['author.profile'])
            ->where('slug', $slug)
            ->firstOrFail(); // Automatically throws a 404 if not found

        return view('webapp.forum.show', compact('post'));
    }

    public function showForum($id)
    {
        $post = $this->forumRepo->findThreadById($id);

        return view('webapp.forum.show', compact('post'));
    }
    public function createThread()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('webapp.forum.create', compact('categories'));
    }
    public function storeThread(Request $request, \App\Services\ImgBBService $imgBBService)
    {
        $validData = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content'     => 'required|string',
            'featured_image'=> 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('featured_image')) {
            $url = $imgBBService->upload($request->file('featured_image'));
            if ($url) {
                $validData['content'] = '<img src="' . $url . '" alt="' . $validData['title'] . '" class="w-full rounded-xl mb-4" />' . $validData['content'];
            }
        }

        $thread = $this->forumRepo->storeThread($validData);
        return redirect()->route('home')->with('success', 'Post published to the Vault!');
    }

    public function uploadEditorImage(Request $request, \App\Services\ImgBBService $imgBBService)
    {
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            $url = $imgBBService->upload($request->file('file'));

            if ($url) {
                return response()->json([
                    'location' => $url
                ]);
            }
        }

        return response()->json(['error' => 'Image upload failed.'], 400);
    }

    public function game()
    {
        $seo = [
            'title' => 'NCS Rhythm Tapper | Play Online Free Music Game',
            'description' => 'Play the highly addictive NCS Rhythm Tapper! A free, online, neon-styled music rhythm game. Tap the beats, score combos, and test your reflexes with royalty-free soundtracks.',
            'keywords' => 'music game online, rhythm tapper, piano tiles alternative, ncs hindi games, play music games free, royalty free beats game, neon rhythm'
        ];
        return view('webapp.game', compact('seo'));
    }

    public function gamesList()
    {
        $user = auth()->user();
        $credits = $user ? $user->ncs_credits : 0;
        $history = $user ? $user->creditHistories()->take(20)->get() : collect();

        $seo = [
            'title' => 'NCS Arcade Center | Play Music Games & Earn Credits',
            'description' => 'Browse free online games, earn exclusive NCS Credits, and track your gaming points history.',
            'keywords' => 'music games, ncs credits, rhythm game, play games free, points history'
        ];

        return view('webapp.games_list', compact('credits', 'history', 'seo'));
    }

    public function game2048()
    {
        $seo = [
            'title' => 'NCS 2048 | Play Sliding Puzzle Music Game',
            'description' => 'Play the open-source puzzle game 2048 styled with premium NCS visualizers. Slide tiles to reach 2048 and earn credits!',
            'keywords' => '2048 game, sliding puzzle, ncs hindi puzzle, play 2048 online'
        ];
        return view('webapp.game_2048', compact('seo'));
    }

    public function gameNeonSerpent()
    {
        $seo = [
            'title' => 'NCS Neon Serpent | Retro Arcade Music Snake Game',
            'description' => 'Play the glowing retro 2D snake game Neon Serpent with neon lights and audio feedback. Score 150 points to earn rewards!',
            'keywords' => 'snake game, retro game, neon snake, html5 canvas snake, play snake online'
        ];
        return view('webapp.game_neon_serpent', compact('seo'));
    }

    public function awardCredits(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'score' => 'required|integer',
            'game' => 'nullable|string',
        ]);

        $score = $request->score;
        $gameName = $request->input('game', 'NCS Rhythm Tapper');
        
        $minScore = 1000;
        if ($gameName === '2048') {
            $minScore = 2048;
        } elseif ($gameName === 'Neon Serpent') {
            $minScore = 150;
        }

        if ($score < $minScore) {
            return response()->json(['success' => false, 'message' => "Score must be at least {$minScore} points to earn credits."]);
        }

        $amount = 50;
        $user = auth()->user();
        $user->increment('ncs_credits', $amount);

        NcsCreditHistory::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'description' => "Scored {$score} points in {$gameName}",
        ]);

        return response()->json([
            'success' => true,
            'message' => "Awarded {$amount} NCS Credits!",
            'credits' => $user->ncs_credits
        ]);
    }

    private function getUserStemActivity(string $userId, string $type, int $limit = 6): Collection
    {
        $stemIds = MusicInteraction::query()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->selectRaw('stem_id, MAX(created_at) as last_activity_at')
            ->groupBy('stem_id')
            ->orderByDesc('last_activity_at')
            ->limit($limit)
            ->pluck('stem_id');

        if ($stemIds->isEmpty()) {
            return collect();
        }

        $music = Music::query()
            ->with('category')
            ->whereIn('id', $stemIds)
            ->get()
            ->keyBy('id');

        return $stemIds->map(fn ($musicId) => $music->get($musicId))->filter()->values();
    }
}







