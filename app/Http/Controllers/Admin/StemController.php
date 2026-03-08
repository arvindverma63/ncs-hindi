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

    public function index(Request $request)
    {
        try {
            $query = MusicStem::with(['category']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('artist_name', 'LIKE', "%{$search}%")
                        ->orWhere('album_movie_name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $stems = $query->latest()->paginate(15)->withQueryString();
            $categories = Category::where('is_active', 1)->get();

            return view('admin.stems.index', compact('stems', 'categories'));
        } catch (\Exception $e) {
            Log::error('Admin Stem Index Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Could not load studio assets.');
        }
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.stems.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'artist_name'      => 'nullable|string|max:255',
            'album_movie_name' => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'tags_keywords'    => 'nullable|string',
            'stem_file'        => 'required|file|mimes:mp3|max:51200',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bpm'              => 'nullable|integer',
            'music_key'        => 'nullable|string|max:10',
            'is_public'        => 'boolean',
            'seo_title'        => 'nullable|string|max:70',
            'seo_description'  => 'nullable|string|max:160',
        ]);

        try {
            $this->stemRepo->uploadStem($data['category_id'], $data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Published successfully.']);
            }

            return redirect()->route('admin.stems.index')->with('success', 'Published successfully.');
        } catch (\Exception $e) {
            Log::error('Upload Failed', ['error' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $stem = MusicStem::findOrFail($id);
            $categories = Category::where('is_active', 1)->get();
            return view('admin.stems.edit', compact('stem', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('admin.stems.index')->with('error', 'Asset not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'artist_name'      => 'nullable|string|max:255',
            'album_movie_name' => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'tags_keywords'    => 'nullable|string',
            'stem_file'        => 'nullable|file|mimes:mp3|max:51200',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bpm'              => 'nullable|integer',
            'music_key'        => 'nullable|string|max:10',
            'is_public'        => 'boolean',
            'seo_title'        => 'nullable|string|max:70',
            'seo_description'  => 'nullable|string|max:160',
        ]);

        try {
            $this->stemRepo->updateStem($id, $data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Updated successfully.']);
            }

            return redirect()->route('admin.stems.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            Log::error('Update Failed', ['error' => $e->getMessage()]);
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->stemRepo->deleteStem($id);
            return back()->with('success', 'Asset removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete.');
        }
    }
}
