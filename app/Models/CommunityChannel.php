<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommunityChannel extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'slug', 'description', 'icon', 'sort_order', 'is_private'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }

    public function messages()
    {
        return $this->hasMany(CommunityMessage::class, 'channel_id')->whereNull('parent_id');
    }
}
