<?php 

namespace App\Repositories\Eloquent;

use App\Models\MediaGallery;
use App\Repositories\Contracts\MediaGalleryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class EloquentMediaGalleryRepository implements MediaGalleryInterface
{
    public function getAll(array $filters = [])
    {
        return MediaGallery::query()
            // Eager load the category relationship for the badges in the UI
            ->with('category')
            ->when(isset($filters['uploaded_by']), fn($q) => $q->where('uploaded_by', $filters['uploaded_by']))
            ->when(isset($filters['type']), fn($q) => $q->where('file_type', $filters['type']))
            // NEW: Filter by Category ID
            ->when(isset($filters['category_id']), fn($q) => $q->where('category_id', $filters['category_id']))
            ->when(isset($filters['search']), fn($q) => $q->where('title', 'like', '%' . $filters['search'] . '%'))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function findById(string $id)
    {
        return MediaGallery::findOrFail($id);
    }

    public function store(array $data, UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();
        $path = $file->store('media', 'public');

        return MediaGallery::create([
            'title'       => $data['title'],
            // NEW: Save the category selected in the modal
            'category_id' => $data['category_id'] ?? null,
            'file_path'   => $path,
            'file_name'   => $file->getClientOriginalName(),
            'extension'   => $extension,
            'mime_type'   => $mime,
            'file_type'   => $this->determineFileType($extension, $mime),
            'file_size'   => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);
    }

    public function delete(string $id)
    {
        $media = $this->findById($id);
        Storage::disk('public')->delete($media->file_path);
        return $media->delete();
    }

    private function determineFileType($extension, $mime): string
    {
        if (str_contains($mime, 'image')) return 'image';
        if (in_array($extension, ['pdf', 'epub', 'mobi'])) return 'book';
        if (str_contains($mime, 'video')) return 'video';
        return 'document';
    }
}