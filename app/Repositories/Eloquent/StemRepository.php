<?php

namespace App\Repositories\Eloquent;

use App\Models\MusicStem;
use App\Models\StemInteraction;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StemRepository implements StemRepositoryInterface
{
    public function uploadStem($categoryId, array $data)
    {
        $audioFile = $data['stem_file'];
        $imageFile = $data['featured_image'] ?? null;

        // Store Audio file
        $audioName = time() . '_' . $audioFile->getClientOriginalName();
        $audioPath = $audioFile->storeAs('uploads/stems', $audioName, 'public');

        // Store Featured Image if provided
        $imagePath = null;
        if ($imageFile) {
            $imageName = time() . '_cover.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('uploads/stems/covers', $imageName, 'public');
        }

        return MusicStem::create([
            'id' => (string) Str::uuid(),
            'category_id' => $categoryId, // Updated from thread_id
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_name' => $audioFile->getClientOriginalName(),
            'file_path' => $audioPath,
            'featured_image' => $imagePath,
            'file_size' => $this->formatBytes($audioFile->getSize()),
            'bpm' => $data['bpm'] ?? null,
            'music_key' => $data['music_key'] ?? null,
        ]);
    }

    public function getLibraryStems($filters = [])
    {
        // Now eager loads the category instead of the thread
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
        return StemInteraction::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'stem_id' => $stemId,
            'type' => $type,
        ]);
    }

    public function deleteStem($stemId)
    {
        $stem = MusicStem::findOrFail($stemId);

        if ($stem->file_path) Storage::disk('public')->delete($stem->file_path);
        if ($stem->featured_image) Storage::disk('public')->delete($stem->featured_image);

        return $stem->delete();
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
