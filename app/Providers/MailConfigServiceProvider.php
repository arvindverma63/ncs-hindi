<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class MailConfigServiceProvider extends ServiceProvider
{

    public function register(): void {}

    public function boot(): void
    {

        if (!Schema::hasTable('settings')) {
            return;
        }

        try {

            $settings = Setting::whereIn('key', [
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_email_id',
                'mailer_name',
            ])->pluck('value', 'key');

            if ($settings->isNotEmpty()) {


                if ($settings->get('mail_driver')) {
                    $driver = $settings->get('mail_driver');
                    Config::set('mail.default', $driver);

                    if ($driver === 'smtp') {
                        Config::set('mail.mailers.smtp.host', $settings->get('mail_host'));
                        Config::set('mail.mailers.smtp.port', $settings->get('mail_port'));
                        Config::set('mail.mailers.smtp.username', $settings->get('mail_username'));
                        Config::set('mail.mailers.smtp.password', $settings->get('mail_password'));

                        $encryption = $settings->get('mail_encryption');
                        if ($encryption === 'none' || $encryption === 'null') {
                            Config::set('mail.mailers.smtp.encryption', null);
                        } else {
                            Config::set('mail.mailers.smtp.encryption', $encryption);
                        }


                        Config::set('mail.mailers.smtp.context', [
                            'ssl' => [
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true,
                            ],
                        ]);
                    }
                }

                if ($settings->get('mail_email_id')) {
                    Config::set('mail.from.address', $settings->get('mail_email_id'));
                }


                if ($settings->get('mailer_name')) {
                    Config::set('mail.from.name', $settings->get('mailer_name'));
                }
            }
        } catch (\Exception $e) {
        }
    }
}
