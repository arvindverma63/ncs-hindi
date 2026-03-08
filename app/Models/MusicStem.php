<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MusicStem extends Model
{
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
        'is_public'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_public' => 'boolean',
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
                $model->slug = Str::slug($model->title);
            }
        });
    }

    // --- Relationships ---

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function interactions()
    {
        return $this->hasMany(StemInteraction::class, 'stem_id');
    }

    // --- Scopes / Helpers ---

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
