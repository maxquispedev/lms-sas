<?php

namespace App\Support\Branding;

use App\Models\BrandingSetting;
use Illuminate\Support\Facades\Cache;

class BrandingRepository
{
    private const CACHE_KEY = 'branding_settings:v2';
    private const LEGACY_DEFAULT_ACADEMY_NAME = 'SEIA ACADEMIA';
    private const DEFAULT_PRIMARY_COLOR = '#4071e7';
    private const DEFAULT_LOGO_PATH = 'img/Logo_espacio_veloz.svg';
    private const DEFAULT_DARK_LOGO_PATH = 'img/Logo_espacio_veloz_blanco.svg';
    private const DEFAULT_FAVICON_PATH = 'img/isotipo-morado.svg';

    /**
     * Convierte una ruta guardada en BD a una URL pública.
     *
     * Soporta:
     * - Archivos subidos a `storage/app/public` (paths tipo `branding/...`)
     * - Assets públicos en `public/` (paths tipo `img/...`)
     * - URLs absolutas (http/https)
     */
    public function urlFor(?string $path): ?string
    {
        $value = trim((string) $path);

        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, 'img/') || str_starts_with($value, 'images/') || str_starts_with($value, '/')) {
            return asset(ltrim($value, '/'));
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }

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
                    'primary_color' => self::DEFAULT_PRIMARY_COLOR,
                    'logo_alt' => $defaultName,
                    'logo_path' => self::DEFAULT_LOGO_PATH,
                    'dark_logo_path' => self::DEFAULT_DARK_LOGO_PATH,
                    'certificate_background_path' => null,
                    'favicon_path' => self::DEFAULT_FAVICON_PATH,
                ],
            );
        });

        $academyName = trim((string) $settings->academy_name);
        $logoAlt = trim((string) $settings->logo_alt);
        $primaryColor = trim((string) ($settings->primary_color ?? ''));
        $logoPath = trim((string) ($settings->logo_path ?? ''));
        $darkLogoPath = trim((string) ($settings->dark_logo_path ?? ''));
        $faviconPath = trim((string) ($settings->favicon_path ?? ''));

        $shouldFixLegacyValues = $academyName === '' || $academyName === self::LEGACY_DEFAULT_ACADEMY_NAME;
        $shouldFixLogoAlt = $logoAlt === '' || $logoAlt === self::LEGACY_DEFAULT_ACADEMY_NAME;
        $shouldFixPrimaryColor = $primaryColor === '';
        $shouldFixLogoPath = $logoPath === '';
        $shouldFixDarkLogoPath = $darkLogoPath === '';
        $shouldFixFaviconPath = $faviconPath === '';

        if (
            $shouldFixLegacyValues
            || $shouldFixLogoAlt
            || $shouldFixPrimaryColor
            || $shouldFixLogoPath
            || $shouldFixDarkLogoPath
            || $shouldFixFaviconPath
        ) {
            if ($shouldFixLegacyValues) {
                $settings->academy_name = $defaultName;
            }

            if ($shouldFixLogoAlt) {
                $settings->logo_alt = $settings->academy_name !== ''
                    ? (string) $settings->academy_name
                    : $defaultName;
            }

            if ($shouldFixPrimaryColor) {
                $settings->primary_color = self::DEFAULT_PRIMARY_COLOR;
            }

            if ($shouldFixLogoPath) {
                $settings->logo_path = self::DEFAULT_LOGO_PATH;
            }

            if ($shouldFixDarkLogoPath) {
                $settings->dark_logo_path = self::DEFAULT_DARK_LOGO_PATH;
            }

            if ($shouldFixFaviconPath) {
                $settings->favicon_path = self::DEFAULT_FAVICON_PATH;
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

