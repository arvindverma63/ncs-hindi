<?php

namespace App\Actions\User;

use App\Models\User;
use App\Services\MediaUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(protected MediaUploadService $mediaService) {}

    public function handle(array $data, $profileImage = null): User
    {
        return DB::transaction(function () use ($data, $profileImage) {
            
            // 1. Create the User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null, // Handle if phone is missing
                'password' => Hash::make($data['password']),
                'user_type' => 1, // Or you can map this based on role if needed
                'email_verified_at' => now(),
            ]);

            // 2. Assign Spatie Role
            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            }

            // 3. Upload Profile Image
            if ($profileImage && $profileImage->isValid()) {
                $this->mediaService->uploadImage($user, $profileImage, 'profile');
            }

            return $user;
        });
    }
}