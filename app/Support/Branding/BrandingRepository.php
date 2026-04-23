<?php

namespace App\Support\Branding;

use App\Models\BrandingSetting;
use Illuminate\Support\Facades\Cache;

class BrandingRepository
{
    private const CACHE_KEY = 'branding_settings:v2';

    public function get(): BrandingSetting
    {
        /** @var string $defaultName */
        $defaultName = (string) config('app.name', 'LMS');

        /** @var BrandingSetting $settings */
        $settings = Cache::rememberForever(self::CACHE_KEY, function (): BrandingSetting {
            return BrandingSetting::query()->firstOrCreate(
                ['id' => 1],
                [
                    'academy_name' => $defaultName,
                    'primary_color' => null,
                    'logo_alt' => $defaultName,
                    'logo_path' => null,
                    'dark_logo_path' => null,
                    'certificate_background_path' => null,
                    'favicon_path' => null,
                ],
            );
        });

        return $settings;
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

