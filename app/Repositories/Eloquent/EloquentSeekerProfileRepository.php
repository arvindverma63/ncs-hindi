<?php

namespace App\Repositories\Eloquent;

use App\Models\SeekerProfile;
use App\Repositories\Contracts\SeekerProfileInterface;

class EloquentSeekerProfileRepository implements SeekerProfileInterface
{
    public function findByUserId(string $userId)
    {
        return SeekerProfile::where('user_id', $userId)->first();
    }

    public function updateOrCreate(string $userId, array $data)
    {
        return SeekerProfile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}