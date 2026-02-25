<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;

class UpdateGeneralSettingsAction
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function handle(Request $request): void
    {
        $textFields = ['app_name', 'app_phone', 'app_email', 'app_address', 'footer_text'];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                $this->settingService->set($field, $request->input($field));
            }
        }

        $imageFields = ['logo', 'favicon', 'footer_logo', 'login_page_logo'];
        $uploadPath = 'uploads/settings/';

        if (!File::exists(public_path($uploadPath))) {
            File::makeDirectory(public_path($uploadPath), 0755, true);
        }

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $image = $request->file($field);

                if (!$image->isValid()) {
                    throw new \Exception("Upload failed for {$field}. Check your PHP upload_max_filesize setting.");
                }

                $filename = $field . '-' . time() . '.' . $image->getClientOriginalExtension();
                $fullPath = public_path($uploadPath . $filename);

                try {
                    Image::read($image->getPathname())->save($fullPath);
                } catch (\Exception $e) {

                    $image->move(public_path($uploadPath), $filename);
                    Log::warning("Intervention Image could not decode {$field}. File moved directly. Error: " . $e->getMessage());
                }


                $oldPath = $this->settingService->get($field);
                if ($oldPath && File::exists(public_path($oldPath))) {
                    File::delete(public_path($oldPath));
                }

                $this->settingService->set($field, $uploadPath . $filename);
            }
        }
    }
}
