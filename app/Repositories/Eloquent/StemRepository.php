<?php

namespace App\Repositories\Eloquent;

use App\Models\MusicStem;
use App\Models\StemInteraction;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StemRepository implements StemRepositoryInterface
{
    public function uploadStem($categoryId, array $data)
    {
        try {
            $audioFile = $data['stem_file'];
            $imageFile = $data['featured_image'] ?? null;

            // Handle Audio Upload
            $audioName = time() . '_' . Str::slug($data['title']) . '.' . $audioFile->getClientOriginalExtension();
            $audioPath = $audioFile->storeAs('uploads/stems', $audioName, 'public');

            // Handle Cover Image Upload
            $imagePath = null;
            if ($imageFile) {
                $imageName = time() . '_cover.' . $imageFile->getClientOriginalExtension();
                $imagePath = $imageFile->storeAs('uploads/stems/covers', $imageName, 'public');
            }

            $stem = MusicStem::create([
                'category_id'      => $categoryId,
                'title'            => $data['title'],
                'artist_name'      => $data['artist_name'] ?? null,
                'album_movie_name' => $data['album_movie_name'] ?? null,
                'language'         => $data['language'] ?? null,
                'description'      => $data['description'] ?? null,
                'tags_keywords'    => $data['tags_keywords'] ?? null,
                'file_name'        => $audioFile->getClientOriginalName(),
                'file_path'        => $audioPath,
                'featured_image'   => $imagePath,
                'file_size'        => $this->formatBytes($audioFile->getSize()),
                'bpm'              => $data['bpm'] ?? null,
                'music_key'        => $data['music_key'] ?? null,
                'seo_title'        => $data['seo_title'] ?? $data['title'],
                'seo_description'  => $data['seo_description'] ?? Str::limit($data['description'] ?? '', 150),
                'is_public'        => $data['is_public'] ?? true,
            ]);

            Log::info("Stem uploaded successfully", ['id' => $stem->id, 'title' => $stem->title]);
            return $stem;
        } catch (\Exception $e) {
            Log::error("Failed to upload stem", ['error' => $e->getMessage(), 'category_id' => $categoryId]);
            throw $e;
        }
    }
    public function updateStem($stemId, array $data)
    {
        try {
            $stem = MusicStem::findOrFail($stemId);

            // 1. Handle Audio File Update
            if (isset($data['stem_file'])) {
                // Delete old audio file
                if ($stem->file_path) {
                    Storage::disk('public')->delete($stem->file_path);
                }

                $audioFile = $data['stem_file'];
                $audioName = time() . '_' . Str::slug($data['title']) . '.' . $audioFile->getClientOriginalExtension();
                $audioPath = $audioFile->storeAs('uploads/stems', $audioName, 'public');

                $stem->file_name = $audioFile->getClientOriginalName();
                $stem->file_path = $audioPath;
                $stem->file_size = $this->formatBytes($audioFile->getSize());
            }

            // 2. Handle Cover Image Update
            if (isset($data['featured_image'])) {
                // Delete old image
                if ($stem->featured_image) {
                    Storage::disk('public')->delete($stem->featured_image);
                }

                $imageFile = $data['featured_image'];
                $imageName = time() . '_cover.' . $imageFile->getClientOriginalExtension();
                $imagePath = $imageFile->storeAs('uploads/stems/covers', $imageName, 'public');

                $stem->featured_image = $imagePath;
            }

            // 3. Update Text Metadata
            $stem->update([
                'category_id'      => $data['category_id'] ?? $stem->category_id,
                'title'            => $data['title'] ?? $stem->title,
                'artist_name'      => $data['artist_name'] ?? $stem->artist_name,
                'album_movie_name' => $data['album_movie_name'] ?? $stem->album_movie_name,
                'language'         => $data['language'] ?? $stem->language,
                'description'      => $data['description'] ?? $stem->description,
                'tags_keywords'    => $data['tags_keywords'] ?? $stem->tags_keywords,
                'bpm'              => $data['bpm'] ?? $stem->bpm,
                'music_key'        => $data['music_key'] ?? $stem->music_key,
                'seo_title'        => $data['seo_title'] ?? $stem->seo_title,
                'seo_description'  => $data['seo_description'] ?? $stem->seo_description,
                'is_public'        => isset($data['is_public']) ? (bool)$data['is_public'] : $stem->is_public,
                'slug'             => isset($data['title']) ? Str::slug($data['title']) : $stem->slug,
            ]);

            Log::info("Stem updated successfully", ['id' => $stem->id]);
            return $stem;
        } catch (\Exception $e) {
            Log::error("Failed to update stem", ['error' => $e->getMessage(), 'id' => $stemId]);
            throw $e;
        }
    }

    public function getLibraryStems($filters = [])
    {
        $query = MusicStem::with('category');

        if (!empty($filters['search'])) {
            $query->where('title', 'LIKE', '%' . $filters['search'] . '%')
                ->orWhere('artist_name', 'LIKE', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (($filters['sort'] ?? '') === 'popular') {
            $query->orderBy('download_count', 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate(20);
    }

    public function logInteraction($stemId, $userId, $type)
    {
        try {
            $interaction = StemInteraction::create([
                'id'      => (string) Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $stemId,
                'type'    => $type, // 'like', 'download', 'view', 'share'
            ]);

            // Increment the counter directly on the music_stems table for fast retrieval
            $column = $type . '_count'; // e.g., view_count, like_count
            MusicStem::where('id', $stemId)->increment($column);

            Log::debug("Stem interaction logged and counter incremented", [
                'stem_id' => $stemId,
                'type'    => $type
            ]);

            return $interaction;
        } catch (\Exception $e) {
            Log::error("Failed to log stem interaction", ['error' => $e->getMessage(), 'stem_id' => $stemId]);
            return null;
        }
    }

    public function deleteStem($stemId)
    {
        try {
            $stem = MusicStem::findOrFail($stemId);

            if ($stem->file_path) Storage::disk('public')->delete($stem->file_path);
            if ($stem->featured_image) Storage::disk('public')->delete($stem->featured_image);

            $stem->delete();
            Log::info("Stem deleted successfully", ['id' => $stemId]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete stem", ['error' => $e->getMessage(), 'id' => $stemId]);
            return false;
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
