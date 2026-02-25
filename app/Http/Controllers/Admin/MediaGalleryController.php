<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MediaGalleryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MediaGalleryController extends Controller
{
    public function __construct(
        protected MediaGalleryInterface $mediaRepo
    ) {}

    /**
     * Display a listing of the media items.
     */
    public function index(Request $request)
    {
        // Check for 'media.view' permission
        abort_if(Gate::denies('media.view'), 403);

        $items = $this->mediaRepo->getAll($request->all());
        
        return view('admin.media.index', compact('items'));
    }

    /**
     * Handle the file upload process.
     */
    public function upload(Request $request)
    {
        // Check for 'media.upload' permission
        abort_if(Gate::denies('media.upload'), 403);

        $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $this->mediaRepo->store($request->all(), $request->file('file'));
            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified media item from storage.
     */
    public function destroy($id)
    {
        // Check for 'media.delete' permission
        abort_if(Gate::denies('media.delete'), 403);

        try {
            $this->mediaRepo->delete($id);
            return back()->with('success', 'Media item deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}