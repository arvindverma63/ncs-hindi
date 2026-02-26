<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MusicStem;
use App\Models\Category;

class StemController extends Controller
{
    protected $stemRepo;

    public function __construct(StemRepositoryInterface $stemRepo)
    {
        $this->stemRepo = $stemRepo;
    }

    public function index()
    {
        try {
            $stems = MusicStem::with(['category'])
                ->withCount([
                    'interactions as likes_count' => fn($q) => $q->where('type', 'like'),
                    'interactions as downloads_count' => fn($q) => $q->where('type', 'download')
                ])
                ->latest()
                ->paginate(15);

            return view('admin.stems.index', compact('stems'));
        } catch (\Exception $e) {
            Log::error('Admin Stem Index Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Could not load studio assets.');
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.stems.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'stem_file'      => 'required|file|mimes:zip,wav,mp3|max:512000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string|max:10',
            'is_public'      => 'boolean'
        ]);

        try {
            $this->stemRepo->uploadStem($data['category_id'], $data);
            Log::info('Admin Stem Uploaded', ['title' => $data['title'], 'admin_id' => auth()->id()]);

            return redirect()->route('admin.stems.index')
                ->with('success', 'Official release published successfully.');
        } catch (\Exception $e) {
            Log::error('Admin Stem Upload Failed', ['error' => $e->getMessage(), 'data' => $request->except(['stem_file', 'featured_image'])]);
            return back()->withInput()->with('error', 'Failed to publish asset.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->stemRepo->deleteStem($id);
            Log::warning('Admin Stem Deleted', ['stem_id' => $id, 'admin_id' => auth()->id()]);

            return back()->with('success', 'Asset removed from the Vault.');
        } catch (\Exception $e) {
            Log::error('Admin Stem Deletion Failed', ['stem_id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete asset.');
        }
    }
}
