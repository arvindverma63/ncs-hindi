<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Like extends Model
{
    /**
     * Disable auto-incrementing since we use UUIDs.
     */
    public $incrementing = false;

    /**
     * Specify that the primary key type is a string.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    /**
     * Boot function to automatically generate a UUID when creating a Like.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the parent likeable model (Post or MusicStem).
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who owns the like.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
