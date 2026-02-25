<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdatePageSettingsAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = ['home_page_title', 'about_page_title', 'contact_page_title'];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}