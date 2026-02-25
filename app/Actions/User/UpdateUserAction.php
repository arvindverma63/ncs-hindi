<?php

namespace App\Actions\User;

use App\Models\User;
use App\Services\MediaUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __construct(protected MediaUploadService $mediaService) {}

    public function handle(User $user, array $data, $profileImage = null, $medicalFile = null): User
    {
        return DB::transaction(function () use ($user, $data, $profileImage, $medicalFile) {
            
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ];
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }
            $user->update($userData);

            if ($profileImage && $profileImage->isValid()) {
                $this->mediaService->uploadImage($user, $profileImage, 'profile');
            }

            return $user;
        });
    }
}