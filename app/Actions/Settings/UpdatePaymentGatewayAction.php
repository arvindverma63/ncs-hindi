<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdatePaymentGatewayAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = ['client_id', 'client_secret', 'token'];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}