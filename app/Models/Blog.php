<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'view_count'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get only top-level approved comments for the blog.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')
                    ->whereNull('parent_id')
                    ->where('status', 'approved')
                    ->latest();
    }

    /**
     * Get all comments (including pending and replies) for admin moderation.
     */
    public function allComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
}