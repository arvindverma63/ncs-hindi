<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaGallery extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'file_path',
        'file_name',
        'extension',
        'mime_type',
        'file_type',
        'file_size',
        'uploaded_by',
        'category_id',
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

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getReadableSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconAttribute()
    {
        return match($this->file_type) {
            'image'    => 'mdi-image',
            'book'     => 'mdi-book-open-page-variant',
            'document' => 'mdi-file-document',
            'video'    => 'mdi-video',
            default    => 'mdi-file',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}