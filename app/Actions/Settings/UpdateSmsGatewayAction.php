<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdateSmsGatewayAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = ['sms_gateway', 'twilio_sid', 'twilio_token'];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}