<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdateMailConfigAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = [
            'mailer_name', 'mail_host', 'mail_driver', 'mail_port', 
            'mail_username', 'mail_email_id', 'mail_encryption', 'mail_password'
        ];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}