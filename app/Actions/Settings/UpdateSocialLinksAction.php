<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdateSocialLinksAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = [
            'facebook_url', 'x_url', 'instagram_url', 
            'whatsapp_url', 'youtube_url', 'linkedin_url'
        ];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}