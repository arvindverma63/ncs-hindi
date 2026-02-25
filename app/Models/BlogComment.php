<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogComment extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'status',
        'ip_address',
        'user_agent'
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

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

 
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }


    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('status', 'approved');
    }
}