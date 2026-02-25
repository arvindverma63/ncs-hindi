<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MediaGalleryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaGalleryController extends Controller
{
    public function __construct(
        protected MediaGalleryInterface $mediaRepo
    ) {}

    /**
     * Display a listing of the coach's own media items.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        
        // Force filter to only show files uploaded by the authenticated coach
        $filters['uploaded_by'] = Auth::id();

        $items = $this->mediaRepo->getAll($filters);
        
        return view('coach.media.index', compact('items'));
    }

    /**
     * Handle the file upload process for the coach.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            // The repository handles assigning Auth::id() to 'uploaded_by'
            $this->mediaRepo->store($request->all(), $request->file('file'));
            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified media item after verifying ownership.
     */
    public function destroy($id)
    {
        try {
            $media = $this->mediaRepo->findById($id);

            // Security Check: Ensure the coach owns this file
            if ($media->uploaded_by !== Auth::id()) {
                abort(403, 'You do not have permission to delete this file.');
            }

            $this->mediaRepo->delete($id);
            return back()->with('success', 'Media item deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}