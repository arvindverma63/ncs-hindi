<?php

namespace App\Models;

// 1. Remove the custom trait import
// use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Keep this (Standard Laravel UUIDs)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // 2. Add this for Spatie Roles

class User extends Authenticatable
{
    // 3. Remove 'HasUuid' and add 'HasRoles'
    use HasUuids, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'otp',
        'otp_expires_at',
        'user_type',
        'profile_image',
        'status',
        'ncs_credits',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => 'integer',
        ];
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    // public function getProfileImageAttribute($value)
    // {
    //     // 1. Check if the direct database column has a value
    //     if ($value && file_exists(public_path($value))) {
    //         return asset($value);
    //     }

    //     // 2. Fallback to your media relationship logic (if still needed)
    //     $media = $this->media()->where('collection_name', 'profile')->latest()->first();
    //     if ($media && file_exists(public_path($media->file_name))) {
    //         return asset($media->file_name);
    //     }

    //     // 3. Final fallback to default
    //     return asset('assets/images/users/user-1.jpg');
    // }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get all interactions performed by the user.
     */
    public function interactions()
    {
        return $this->hasMany(MusicInteraction::class);
    }

    /**
     * Specifically get the user's liked music.
     */
    public function likedStems()
    {
        return $this->belongsToMany(Music::class, 'stem_interactions', 'user_id', 'stem_id')
            ->wherePivot('type', 'like');
    }

    public function routeNotificationForFCM($notification = null)
    {
        return $this->fcmTokens()->latest('last_used_at')->value('token');
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function bugReports()
    {
        return $this->hasMany(BugReport::class);
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'user_id');
    }

    public function creditHistories()
    {
        return $this->hasMany(NcsCreditHistory::class)->orderBy('created_at', 'desc');
    }
}







