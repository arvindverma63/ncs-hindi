<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoachProfile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'designation',
        'city',
        'state',
        'country',
        'bio',
        'linkedin_url',
        'website_url',
        'experience_years',
        'approval_status',
        'is_visible',
        'is_featured',
        'ranking_score',
        'current_rank',
        'profile_views',
        'gender', 
        'total_interactions',
        'show_personal_details',
    ];

    // Casts for data types
    protected $casts = [
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'ranking_score' => 'integer',
        'profile_views' => 'integer',
        'total_interactions' => 'integer',
    ];

    /**
     * Relationship: A Coach Profile belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A Coach belongs to many Categories (Pivot table).
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coach_category');
    }
}