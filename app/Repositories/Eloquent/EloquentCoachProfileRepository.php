<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\CoachProfile;
use App\Repositories\Contracts\CoachProfileRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentCoachProfileRepository implements CoachProfileRepositoryInterface
{
    public function getProfile(string $userId)
    {
        // Load user with their associated coach profile
        return User::with('coachProfile')->findOrFail($userId);
    }

    public function updateProfile(string $userId, array $userData, array $profileData)
    {
        return DB::transaction(function () use ($userId, $userData, $profileData) {
            $user = User::findOrFail($userId);
            
            // Update base User info (name, email, etc.)
            $user->update($userData);

            // Update or Create Coach Profile info (bio, designation, gender, etc.)
            // Uses 'user_id' as the foreign key from your model
            return $user->coachProfile()->updateOrCreate(
                ['user_id' => $userId],
                $profileData
            );
        });
    }
}