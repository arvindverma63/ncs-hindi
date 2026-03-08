<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgBBService
{
    public function upload($image)
    {
        try {
            // Fetch the API key from your settings table (id 66 in your screenshot)
            $apiKey = Setting::where('key', 'imgbb_key')->first()?->value;

            if (!$apiKey) {
                throw new \Exception("ImgBB API key not found in settings.");
            }

            // Convert image to base64 as required by ImgBB API
            $imageData = base64_encode(file_get_contents($image->getRealPath()));

            $response = Http::asForm()->post("https://api.imgbb.com/1/upload", [
                'key'   => $apiKey,
                'image' => $imageData,
            ]);

            if ($response->successful()) {
                // Return the direct image URL from the response
                return $response->json()['data']['url'];
            }

            Log::error("ImgBB Upload Failed", ['response' => $response->json()]);
            return null;
        } catch (\Exception $e) {
            Log::error("ImgBB Service Error: " . $e->getMessage());
            return null;
        }
    }
}
