<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Contracts\ForumRepositoryInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $forumRepo;
    protected $stemRepo;
    protected $profileRepo;

    public function __construct(
        ForumRepositoryInterface $forumRepo,
        StemRepositoryInterface $stemRepo,
        ProfileRepositoryInterface $profileRepo
    ) {
        $this->forumRepo = $forumRepo;
        $this->stemRepo = $stemRepo;
        $this->profileRepo = $profileRepo;
    }

    public function index()
    {
        $posts = $this->forumRepo->getAllThreads();
        return view('webapp.index', compact('posts'));
    }

    public function trending()
    {
        $trendingTracks = $this->forumRepo->getTrendingThreads();
        return view('webapp.trending', compact('trendingTracks'));
    }

    public function streams()
    {
        $stems = $this->stemRepo->getLibraryStems();
        return view('webapp.streams', compact('stems'));
    }

    public function profile()
    {
        $user = User::with('profile')->findOrFail(auth()->id());

        $recent_uploads = [];

        return view('webapp.profile.index', compact('user', 'recent_uploads'));
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
            ->with('success', 'Studio updated successfully!');
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
    public function storeThread(Request $request)
    {
        $validData = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content'     => 'required|string',
        ]);
        $thread = $this->forumRepo->storeThread($validData);
        return redirect()->route('home')->with('success', 'Post published to the Vault!');
    }
}
