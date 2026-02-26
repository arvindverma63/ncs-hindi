<?php

namespace App\Repositories\Eloquent;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function findByUserId($userId)
    {
        return Profile::where('user_id', $userId)->firstOrFail();
    }

    public function updateProfile($userId, array $data)
    {
        return Profile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    public function incrementXP($userId, $amount)
    {
        $profile = $this->findByUserId($userId);
        $profile->increment('xp_count', $amount);

        // Logic to update Rank Title based on XP could go here
        return $profile;
    }

    public function getTopProducers($limit = 5)
    {
        return Profile::with('user')
            ->orderBy('xp_count', 'desc')
            ->take($limit)
            ->get();
    }
}
