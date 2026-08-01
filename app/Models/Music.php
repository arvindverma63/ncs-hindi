<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Music extends Model
{
    protected $table = 'music_stems';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'artist_name',
        'album_movie_name',
        'language',
        'description',
        'license_text',
        'tags_keywords',
        'file_name',
        'file_path',
        'featured_image',
        'file_size',
        'bpm',
        'music_key',
        'download_count',
        'like_count',
        'view_count',
        'share_count',
        'seo_title',
        'seo_description',
        'slug',
        'is_public',
        'mega_link',
        'youtube_link',
        'audio_file',
        'can_play_on_website',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_public' => 'boolean',
        'can_play_on_website' => 'boolean',
        'download_count' => 'integer',
        'like_count' => 'integer',
        'view_count' => 'integer',
        'share_count' => 'integer',
        'bpm' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID if not set
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }

            // Auto-generate slug from title if not provided
            if (empty($model->slug)) {
                $model->slug = static::uniqueSlug($model->title);
            }
        });
    }

    public static function uniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $suffix = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function getFeaturedImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $url = null;
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $url = $value;
        } elseif (file_exists(public_path($value))) {
            $url = asset($value);
        } else {
            $url = asset($value);
        }

        $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
        return str_contains($url, '?') ? $url . '&v=' . $timestamp : $url . '?v=' . $timestamp;
    }

    public function getAudioUrlAttribute()
    {
        // 1. Check audio_file
        if (!empty($this->audio_file)) {
            if (filter_var($this->audio_file, FILTER_VALIDATE_URL)) {
                return $this->audio_file;
            }
            $cleanPath = ltrim($this->audio_file, '/');
            if (file_exists(public_path('storage/' . $cleanPath)) || file_exists(public_path($cleanPath)) || file_exists(storage_path('app/public/' . $cleanPath))) {
                return asset(str_starts_with($cleanPath, 'storage/') ? $cleanPath : 'storage/' . $cleanPath);
            }
        }

        // 2. Check file_path for direct audio file
        if (!empty($this->file_path)) {
            if (filter_var($this->file_path, FILTER_VALIDATE_URL) && preg_match('/\.(mp3|wav|ogg|m4a|aac|flac)(\?.*)?$/i', $this->file_path)) {
                return $this->file_path;
            }
            $cleanPath = ltrim($this->file_path, '/');
            if (file_exists(public_path('storage/' . $cleanPath)) || file_exists(public_path($cleanPath)) || file_exists(storage_path('app/public/' . $cleanPath))) {
                return asset(str_starts_with($cleanPath, 'storage/') ? $cleanPath : 'storage/' . $cleanPath);
            }
        }

        // 3. Fallback: check youtube_link for web streaming
        if (!empty($this->youtube_link)) {
            return $this->youtube_link;
        }

        // 4. Default fallback sample audio so playback never fails on missing files
        $defaultSample = 'storage/uploads/stems/1772978828_lofi.mp3';
        if (file_exists(public_path($defaultSample)) || file_exists(storage_path('app/public/uploads/stems/1772978828_lofi.mp3'))) {
            return asset($defaultSample);
        }

        return null;
    }

    public function getIsPlayableAttribute(): bool
    {
        return !empty($this->audio_url);
    }

    // --- Relationships ---

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function interactions()
    {
        return $this->hasMany(MusicInteraction::class, 'stem_id');
    }

    public function isLikedBy($userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->interactions()
            ->where('user_id', $userId)
            ->where('type', 'like')
            ->exists();
    }

    // --- Scopes / Helpers ---

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}







