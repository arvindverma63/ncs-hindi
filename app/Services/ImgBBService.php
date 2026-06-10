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
            // Fetch the API key from settings table
            $apiKey = Setting::where('key', 'imgbb_key')->first()?->value;

            if (!$apiKey) {
                throw new \Exception("ImgBB API key not found in settings.");
            }

            $path = $image->getRealPath();
            $info = @getimagesize($path);
            $srcImg = null;

            if ($info) {
                $mime = $info['mime'];
                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $srcImg = @imagecreatefromjpeg($path);
                } elseif ($mime === 'image/png') {
                    $srcImg = @imagecreatefrompng($path);
                } elseif ($mime === 'image/gif') {
                    $srcImg = @imagecreatefromgif($path);
                } elseif ($mime === 'image/webp') {
                    $srcImg = @imagecreatefromwebp($path);
                }
            }

            if ($srcImg) {
                ob_start();
                if (@imagewebp($srcImg, null, 80)) {
                    $webpData = ob_get_clean();
                    $imageData = base64_encode($webpData);
                } else {
                    ob_end_clean();
                    $imageData = base64_encode(file_get_contents($path));
                }
                @imagedestroy($srcImg);
            } else {
                $imageData = base64_encode(file_get_contents($path));
            }

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







