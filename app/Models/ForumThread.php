<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'user_id', 'category_id', 'title', 'slug',
        'content', 'is_sticky', 'is_verified'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

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
        return $this->belongsTo(Category::class);
    }

    public function stems()
    {
        return $this->hasMany(MusicStem::class, 'thread_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }
}
