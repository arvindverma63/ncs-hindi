<?php

namespace App\Repositories\Contracts;

interface CoachProfileRepositoryInterface
{
    public function getProfile(string $userId);
    public function updateProfile(string $userId, array $userData, array $profileData);
}