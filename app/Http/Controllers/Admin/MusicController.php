<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MusicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Music;
use App\Models\Category;
use App\Models\Language;

class MusicController extends Controller
{
    protected $musicRepo;

    public function __construct(MusicRepositoryInterface $musicRepo)
    {
        $this->musicRepo = $musicRepo;
    }

    public function index(Request $request)
    {
        try {
            $query = Music::with(['category']);

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

            $music = $query->latest()->paginate(15)->withQueryString();
            $categories = Category::where('is_active', 1)->get();

            return view('admin.music.index', compact('music', 'categories'));
        } catch (\Exception $e) {
            Log::error('Admin music Index Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Could not load studio assets.');
        }
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $languages = Language::where('is_active', 1)->orderBy('name')->pluck('name')->toArray();
        return view('admin.music.create', compact('categories', 'languages'));
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
            'license_text'     => 'nullable|string',
            'tags_keywords'    => 'nullable|string',
            'source_type'      => 'nullable|string|in:file,mega',
            'music_file'        => 'nullable|string',
            'mega_link'        => 'nullable|url',
            'youtube_link'     => 'nullable|string|max:255',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'audio_file'       => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,flac|max:51200',
            'can_play_on_website' => 'nullable|boolean',
            'music_key'        => 'nullable|string|max:10',
            'is_public'        => 'boolean',
            'seo_title'        => 'nullable|string|max:70',
            'seo_description'  => 'nullable|string|max:160',
            // bpm removed from validation as it's hidden/removed from UI
        ]);

        try {
            // The Repository should handle saving the string URL into the file path column
            $this->musicRepo->uploadMusic($data['category_id'], $data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Published successfully.']);
            }

            return redirect()->route('admin.music.index')->with('success', 'Published successfully.');
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
            $music = Music::findOrFail($id);
            $categories = Category::where('is_active', 1)->get();
            $languages = Language::where('is_active', 1)->orderBy('name')->pluck('name')->toArray();
            return view('admin.music.edit', compact('music', 'categories', 'languages'));
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('error', 'Asset not found.');
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
            'license_text'     => 'nullable|string',
            'tags_keywords'    => 'nullable|string',
            // UPDATED: stem_file is now an optional string/url for updates
            'music_file'        => 'nullable|string',
            'mega_link'        => 'nullable|url',
            'youtube_link'     => 'nullable|string|max:255',
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'audio_file'       => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,flac|max:51200',
            'can_play_on_website' => 'nullable|boolean',
            'music_key'        => 'nullable|string|max:10',
            'is_public'        => 'boolean',
            'seo_title'        => 'nullable|string|max:70',
            'seo_description'  => 'nullable|string|max:160',
            // bpm removed
        ]);

        try {
            $this->musicRepo->updateMusic($id, $data);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Updated successfully.']);
            }

            return redirect()->route('admin.music.index')->with('success', 'Updated successfully.');
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
            $this->musicRepo->deleteMusic($id);
            return back()->with('success', 'Asset removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete.');
        }
    }
}







