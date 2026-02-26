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

            $audioName = time() . '_' . $audioFile->getClientOriginalName();
            $audioPath = $audioFile->storeAs('uploads/stems', $audioName, 'public');

            $imagePath = null;
            if ($imageFile) {
                $imageName = time() . '_cover.' . $imageFile->getClientOriginalExtension();
                $imagePath = $imageFile->storeAs('uploads/stems/covers', $imageName, 'public');
            }

            $stem = MusicStem::create([
                'id' => (string) Str::uuid(),
                'category_id' => $categoryId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'file_name' => $audioFile->getClientOriginalName(),
                'file_path' => $audioPath,
                'featured_image' => $imagePath,
                'file_size' => $this->formatBytes($audioFile->getSize()),
                'bpm' => $data['bpm'] ?? null,
                'music_key' => $data['music_key'] ?? null,
            ]);

            Log::info("Stem uploaded successfully", ['id' => $stem->id, 'title' => $stem->title]);
            return $stem;
        } catch (\Exception $e) {
            Log::error("Failed to upload stem", ['error' => $e->getMessage(), 'category_id' => $categoryId]);
            throw $e;
        }
    }

    public function getLibraryStems($filters = [])
    {
        return MusicStem::with('category')
            ->withCount([
                'interactions as likes_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'interactions as downloads_count' => function ($query) {
                    $query->where('type', 'download');
                }
            ])
            ->latest()
            ->get();
    }

    public function logInteraction($stemId, $userId, $type)
    {
        try {
            $interaction = StemInteraction::create([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $stemId,
                'type' => $type,
            ]);

            Log::debug("Stem interaction logged", ['user_id' => $userId, 'stem_id' => $stemId, 'type' => $type]);
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
