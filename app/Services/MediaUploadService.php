<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class MediaUploadService
{
    /**
     * Upload a file (Image or Document) and attach it to a model.
     */
    public function uploadImage(Model $model, UploadedFile $file, string $collectionName = 'default'): void
    {
        // 1. Capture File Details FIRST (Before moving the file)
        // We must do this now because after $file->move(), the temp file is gone.
        $fileSize = $file->getSize();
        $mimeType = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // 2. Prepare Paths
        $filename = $collectionName . '-' . time() . '-' . rand(1000, 9999) . '.' . $extension;
        $uploadPath = 'uploads/website-images/';
        $fullPath = public_path($uploadPath);

        // 3. Create Directory if not exists
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // 4. Handle Old Media (Delete previous if exists in this collection)
        if (in_array($collectionName, ['profile', 'medical_docs'])) {
            $oldMedia = $model->media()->where('collection_name', $collectionName)->first();
            if ($oldMedia) {
                if (File::exists(public_path($oldMedia->file_name))) {
                    File::delete(public_path($oldMedia->file_name));
                }
                $oldMedia->delete();
            }
        }

        // 5. Save File
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        
        if (in_array($extension, $imageExtensions)) {
            try {
                // It's an image, try to optimize/save with Intervention
                Image::read($file->getPathname())->save($fullPath . $filename);
            } catch (\Exception $e) {
                Log::warning("Image processing failed, using fallback move. Error: " . $e->getMessage());
                $file->move($fullPath, $filename);
            }
        } else {
            // It's a PDF or Doc, just move it directly
            $file->move($fullPath, $filename);
        }

        // 6. Save to Database (Using the captured variables)
        $model->media()->create([
            'collection_name' => $collectionName,
            'name' => $filename,
            'file_name' => $uploadPath . $filename,
            'mime_type' => $mimeType, // Use captured variable
            'size' => $fileSize,      // Use captured variable
            'disk' => 'public',
        ]);
    }
}