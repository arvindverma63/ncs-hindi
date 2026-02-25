<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{

    public function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }


    public function set(string $key, $value = null): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting.{$key}");
    }

    public function getImageUrl(string $key): string
    {
        $path = $this->get($key);
        
        if ($path && file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('assets/images/no-image.png');
    }
}