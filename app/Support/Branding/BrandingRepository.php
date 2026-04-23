<?php

namespace App\Support\Branding;

use App\Models\BrandingSetting;
use Illuminate\Support\Facades\Cache;

class BrandingRepository
{
    private const CACHE_KEY = 'branding_settings:v2';
    private const LEGACY_DEFAULT_ACADEMY_NAME = 'SEIA ACADEMIA';

    public function get(): BrandingSetting
    {
        /** @var string $defaultName */
        $defaultName = (string) config('app.name', 'LMS');

        /** @var BrandingSetting $settings */
        $settings = Cache::rememberForever(self::CACHE_KEY, function () use ($defaultName): BrandingSetting {
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

        $academyName = trim((string) $settings->academy_name);
        $logoAlt = trim((string) $settings->logo_alt);

        $shouldFixLegacyValues = $academyName === '' || $academyName === self::LEGACY_DEFAULT_ACADEMY_NAME;
        $shouldFixLogoAlt = $logoAlt === '' || $logoAlt === self::LEGACY_DEFAULT_ACADEMY_NAME;

        if ($shouldFixLegacyValues || $shouldFixLogoAlt) {
            if ($shouldFixLegacyValues) {
                $settings->academy_name = $defaultName;
            }

            if ($shouldFixLogoAlt) {
                $settings->logo_alt = $settings->academy_name !== ''
                    ? (string) $settings->academy_name
                    : $defaultName;
            }

            $settings->save();
            $this->forgetCache();
        }

        return $settings;
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

