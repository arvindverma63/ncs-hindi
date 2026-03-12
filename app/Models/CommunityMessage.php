<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CommunityMessage extends Model
{
    use SoftDeletes;

    /**
     * Set UUID configuration
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'channel_id',
        'parent_id',
        'message',
        'type',
        'metadata',
        'is_edited',
        'is_pinned'
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'is_pinned' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Auto-generate UUID on creation.
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
     * Relationship: Message Author
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: The Channel it belongs to
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(CommunityChannel::class);
    }

    /**
     * Relationship: Parent message (if this is a reply)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CommunityMessage::class, 'parent_id');
    }

    /**
     * Relationship: Replies to this message
     */
    public function replies(): HasMany
    {
        return $this->hasMany(CommunityMessage::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Scope: Only fetch pinned messages
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}
