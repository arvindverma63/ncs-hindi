<?php

namespace App\Repositories\Eloquent;

use App\Models\Music;
use App\Models\FcmToken;
use App\Models\MusicInteraction;
use App\Notifications\MusicPublishedNotification;
use App\Services\ImgBBService;
use App\Repositories\Contracts\MusicRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MusicRepository implements MusicRepositoryInterface
{
    protected $imgBB;

    public function __construct(ImgBBService $imgBB)
    {
        $this->imgBB = $imgBB;
    }

    private function buildTrendingQuery(array $filters = []): Builder
    {
        $query = Music::with('category')->where('is_public', true);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('artist_name', 'LIKE', "%{$search}%")
                    ->orWhere('album_movie_name', 'LIKE', "%{$search}%")
                    ->orWhere('tags_keywords', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $catVal = $filters['category_id'];
            if (\Illuminate\Support\Str::isUuid($catVal)) {
                $query->where('category_id', $catVal);
            } else {
                $query->whereHas('category', function ($q) use ($catVal) {
                    $q->where('slug', $catVal);
                });
            }
        }

        return $query;
    }

    private function applyTrendingSort(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'likes' => $query->orderByDesc('like_count')->orderByDesc('download_count'),
            'views' => $query->orderByDesc('view_count')->orderByDesc('download_count'),
            'newest', 'latest' => $query->latest(),
            default => $query->orderByDesc('download_count')->orderByDesc('like_count'),
        };
    }

    public function getTrendingMusic($filters = [])
    {
        $perPage = max(6, min((int) ($filters['per_page'] ?? 20), 24));
        $sort = $filters['sort'] ?? 'downloads';

        $query = $this->applyTrendingSort($this->buildTrendingQuery($filters), $sort);

        return $query->paginate($perPage)->withQueryString();
    }

    public function getTrendingSpotlight($filters = [])
    {
        $query = $this->applyTrendingSort($this->buildTrendingQuery($filters), $filters['sort'] ?? 'downloads');

        return $query->first();
    }

    public function getTrendingCreators($filters = [], $limit = 6)
    {
        $query = $this->buildTrendingQuery($filters);

        return $query
            ->selectRaw('artist_name, COUNT(*) as releases, SUM(download_count) as downloads, SUM(like_count) as likes, SUM(view_count) as views, MAX(featured_image) as avatar')
            ->groupBy('artist_name')
            ->orderByDesc('downloads')
            ->limit($limit)
            ->get()
            ->map(function ($creator) {
                $creator->creator_name = blank($creator->artist_name) ? 'Unknown Artist' : $creator->artist_name;
                return $creator;
            });
    }

    public function getTrendingStats($filters = [])
    {
        $query = $this->buildTrendingQuery($filters);

        return [
            'tracks' => (clone $query)->count(),
            'downloads' => (clone $query)->sum('download_count'),
            'likes' => (clone $query)->sum('like_count'),
            'views' => (clone $query)->sum('view_count'),
        ];
    }

    public function uploadMusic($categoryId, array $data)
    {
        try {
            // Since jQuery sends the Mega Link as a string in 'stem_file'
            $audioPath = $data['music_file'];

            $imageUrl = null;
            if (isset($data['featured_image']) && $data['featured_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imageUrl = $this->imgBB->upload($data['featured_image']);
            }

            $music = Music::create([
                'id'               => (string) Str::uuid(), // Ensure UUID is generated if not in boot
                'category_id'      => $categoryId,
                'title'            => $data['title'],
                'artist_name'      => $data['artist_name'] ?? null,
                'album_movie_name' => $data['album_movie_name'] ?? null,
                'language'         => $data['language'] ?? null,
                'description'      => $data['description'] ?? null,
                'license_text'     => $data['license_text'] ?? null,
                'tags_keywords'    => $data['tags_keywords'] ?? null,
                'file_name'        => 'External Link',
                'file_path'        => $audioPath, // This saves the Mega Link
                'featured_image'   => $imageUrl,
                'file_size'        => '0 KB', // URL doesn't have a local file size
                'bpm'              => $data['bpm'] ?? null,
                'music_key'        => $data['music_key'] ?? null,
                'mega_link'        => $data['mega_link'] ?? $audioPath,
                'youtube_link'     => $data['youtube_link'] ?? null,
                'seo_title'        => $data['seo_title'] ?? $data['title'],
                'seo_description'  => $data['seo_description'] ?? Str::limit($data['description'] ?? '', 150),
                'is_public'        => $data['is_public'] ?? true,
                'slug'             => Music::uniqueSlug($data['title']),
            ]);

            Log::info("music uploaded successfully", ['id' => $music->id]);

            if ($music->is_public) {
                $this->notifyMusicPublished($music);
            }

            return $music;
        } catch (\Exception $e) {
            Log::error("Failed to upload music", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateMusic($musicId, array $data)
    {
        try {
            $music = Music::findOrFail($musicId);
            $wasPublic = (bool) $music->is_public;

            // Update Audio path if a new link/string is provided
            if (isset($data['music_file'])) {
                $music->file_path = $data['music_file'];
                $music->mega_link = $data['mega_link'] ?? $data['music_file'];
            }

            if (isset($data['featured_image']) && $data['featured_image'] instanceof \Illuminate\Http\UploadedFile) {
                $imageUrl = $this->imgBB->upload($data['featured_image']);
                if ($imageUrl) {
                    $music->featured_image = $imageUrl;
                }
            }

            $music->update([
                'category_id'      => $data['category_id'] ?? $music->category_id,
                'title'            => $data['title'] ?? $music->title,
                'artist_name'      => $data['artist_name'] ?? $music->artist_name,
                'album_movie_name' => $data['album_movie_name'] ?? $music->album_movie_name,
                'language'         => $data['language'] ?? $music->language,
                'description'      => $data['description'] ?? $music->description,
                'license_text'     => array_key_exists('license_text', $data) ? $data['license_text'] : $music->license_text,
                'tags_keywords'    => $data['tags_keywords'] ?? $music->tags_keywords,
                'music_key'        => $data['music_key'] ?? $music->music_key,
                'youtube_link'     => $data['youtube_link'] ?? $music->youtube_link,
                'seo_title'        => $data['seo_title'] ?? $music->seo_title,
                'seo_description'  => $data['seo_description'] ?? $music->seo_description,
                'is_public'        => isset($data['is_public']) ? (bool)$data['is_public'] : $music->is_public,
                'slug'             => isset($data['title']) ? Music::uniqueSlug($data['title'], $music->id) : $music->slug,
            ]);

            Log::info("music updated successfully", ['id' => $music->id]);

            $isNowPublic = array_key_exists('is_public', $data)
                ? (bool) $data['is_public']
                : $wasPublic;

            if (!$wasPublic && $isNowPublic) {
                $this->notifyMusicPublished($music);
            }

            return $music;
        } catch (\Exception $e) {
            Log::error("Failed to update music", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function getLibraryMusic($filters = [])
    {
        $query = Music::with('category')->where('is_public', true);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('artist_name', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $catVal = $filters['category_id'];
            if (\Illuminate\Support\Str::isUuid($catVal)) {
                $query->where('category_id', $catVal);
            } else {
                $query->whereHas('category', function ($q) use ($catVal) {
                    $q->where('slug', $catVal);
                });
            }
        }

        if (($filters['sort'] ?? '') === 'popular') {
            $query->orderBy('download_count', 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate(9);
    }

    public function logInteraction($musicId, $userId, $type)
    {
        try {
            $ipAddress = request()->ip();

            $query = MusicInteraction::where('stem_id', $musicId)
                ->where('type', $type);

            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->whereNull('user_id')
                      ->where('ip_address', $ipAddress);
            }

            if ($type === 'like') {
                if ($query->exists()) {
                    return null;
                }
            } else {
                // For views and downloads, only block duplicates if they occurred in the last hour
                if ($query->where('created_at', '>=', now()->subHour())->exists()) {
                    return null;
                }
            }

            $interaction = MusicInteraction::create([
                'id'      => (string) Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $musicId,
                'type'    => $type,
                'ip_address' => $ipAddress,
                'created_at' => now(),
            ]);

            $column = $type . '_count';
            Music::where('id', $musicId)->increment($column);

            return $interaction;
        } catch (\Exception $e) {
            Log::error("Failed to log interaction", ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function deleteMusic($musicId)
    {
        try {
            $music = Music::findOrFail($musicId);

            if ($music->file_path && !filter_var($music->file_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($music->file_path);
            }

            $music->delete();
            Log::info("music deleted successfully", ['id' => $musicId]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete music", ['error' => $e->getMessage()]);
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

    private function notifyMusicPublished(Music $music): void
    {
        try {
            $tokens = FcmToken::query()
                ->whereNotNull('token')
                ->where('token', '!=', '')
                ->pluck('token')
                ->filter()
                ->unique()
                ->values()
                ->all();

            Log::info('Preparing music publish notification', [
                'stem_id' => $music->id,
                'slug' => $music->slug,
                'token_count' => count($tokens),
            ]);

            if (empty($tokens)) {
                Log::info('Skipping music publish notification because no FCM tokens exist', [
                    'stem_id' => $music->id,
                ]);
                return;
            }

            foreach (array_chunk($tokens, 500) as $chunk) {
                $notifiable = new class($chunk) {
                    public function __construct(private array $tokens) {}

                    public function routeNotificationForFCM($notification = null)
                    {
                        return $this->tokens;
                    }
                };

                Notification::sendNow($notifiable, new MusicPublishedNotification($music));
            }

            Log::info('Music publish notification dispatched', [
                'stem_id' => $music->id,
                'token_count' => count($tokens),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch music publish notification', [
                'stem_id' => $music->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}







