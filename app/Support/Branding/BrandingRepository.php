<?php

namespace App\Support\Branding;

use App\Models\BrandingSetting;
use Illuminate\Support\Facades\Cache;

class BrandingRepository
{
    private const CACHE_KEY = 'branding_settings:v2';

    public function get(): BrandingSetting
    {
        /** @var BrandingSetting $settings */
        $settings = Cache::rememberForever(self::CACHE_KEY, function (): BrandingSetting {
            return BrandingSetting::query()->firstOrCreate(
                ['id' => 1],
                [
                    'academy_name' => 'SEIA ACADEMIA',
                    'logo_alt' => 'SEIA ACADEMIA',
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

