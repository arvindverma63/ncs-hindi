<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MusicStem;

class StemController extends Controller
{
    protected $stemRepo;

    public function __construct(StemRepositoryInterface $stemRepo)
    {
        $this->stemRepo = $stemRepo;
    }

    /**
     * Display a listing of all official stems.
     */
    public function index()
    {
        $stems = MusicStem::with(['thread.author'])->latest()->paginate(15);
        return view('admin.stems.index', compact('stems'));
    }

    /**
     * Show the form for creating a new official stem release.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();

        // Then pass them to the view
        return view('admin.stems.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'thread_id'      => 'required|exists:forum_threads,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'stem_file'      => 'required|file|mimes:zip,wav,mp3|max:512000', // 500MB for Admin
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string|max:10',
            'is_public'      => 'boolean'
        ]);

        $this->stemRepo->uploadStem($data['thread_id'], $data);

        return redirect()->route('admin.stems.index')
            ->with('success', 'Official release published successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->stemRepo->deleteStem($id);
        return back()->with('success', 'Asset removed from the Vault.');
    }
}
