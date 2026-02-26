<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MusicStem extends Model
{
    protected $fillable = [
        'id',
        'thread_id',
        'title',
        'description',
        'file_name',
        'file_path',
        'featured_image',
        'file_size',
        'bpm',
        'music_key'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    // Relationships
    public function interactions()
    {
        return $this->hasMany(StemInteraction::class, 'stem_id');
    }

    public function likes()
    {
        return $this->interactions()->where('type', 'like');
    }

    public function downloadLogs()
    {
        return $this->interactions()->where('type', 'download');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
