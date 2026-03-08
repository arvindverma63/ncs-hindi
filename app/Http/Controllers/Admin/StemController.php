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
            // Using the direct count columns for better performance
            $stems = MusicStem::with(['category'])
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
        $categories = Category::where('is_active', 1)->get();
        return view('admin.stems.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Core Details
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'artist_name'      => 'nullable|string|max:255',
            'album_movie_name' => 'nullable|string|max:255',
            'language'         => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'tags_keywords'    => 'nullable|string',

            // File Validation (Restricted to .mp3 and Images)
            'stem_file'        => 'required|file|mimes:mp3|max:51200', // 50MB limit
            'featured_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',

            // Musical Properties
            'bpm'              => 'nullable|integer',
            'music_key'        => 'nullable|string|max:10',
            'is_public'        => 'boolean',

            // SEO Fields
            'seo_title'        => 'nullable|string|max:70',
            'seo_description'  => 'nullable|string|max:160',
        ]);

        try {
            // Pass the category_id and validated data to the repository
            $this->stemRepo->uploadStem($data['category_id'], $data);

            Log::info('Admin Stem Uploaded', [
                'title' => $data['title'],
                'artist' => $data['artist_name'] ?? 'N/A',
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.stems.index')
                ->with('success', 'Music asset published successfully.');
        } catch (\Exception $e) {
            Log::error('Admin Stem Upload Failed', [
                'error' => $e->getMessage(),
                'data' => $request->except(['stem_file', 'featured_image'])
            ]);
            return back()->withInput()->with('error', 'Failed to publish asset: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->stemRepo->deleteStem($id);
            Log::warning('Admin Stem Deleted', ['stem_id' => $id, 'admin_id' => auth()->id()]);

            return back()->with('success', 'Asset removed from the database.');
        } catch (\Exception $e) {
            Log::error('Admin Stem Deletion Failed', ['stem_id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete asset.');
        }
    }
}
