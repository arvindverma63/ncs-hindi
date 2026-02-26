<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StemInteraction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'user_id',
        'stem_id',
        'type', // 'like' or 'download'
    ];

    /**
     * Use UUIDs for the primary key.
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Boot function to handle UUID generation.
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
     * The user who performed the interaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The stem being interacted with.
     */
    public function stem()
    {
        return $this->belongsTo(MusicStem::class, 'stem_id');
    }
}
