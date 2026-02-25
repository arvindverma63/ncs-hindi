<?php

namespace App\Actions\User;

use App\Models\User;
use App\Services\MediaUploadService;
use Illuminate\Support\Facades\DB;

class UpdateUserProfileAction
{
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    public function handle(User $user, array $data, $profileImage = null): void
    {
        DB::transaction(function () use ($user, $data, $profileImage) {
            $user->fill([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();

            if ($profileImage) {
                $this->mediaService->uploadImage($user, $profileImage, 'profile');
            }
        });
    }
}