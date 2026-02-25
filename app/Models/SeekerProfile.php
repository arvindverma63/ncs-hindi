<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeekerProfile extends Model
{
    use HasFactory, HasUuids,SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_domain',
        'company_name',
        'city',
        'state',
        'notification_preferences',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'notification_preferences' => 'array', // Automatically converts JSON to Array
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: A Seeker Profile belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}