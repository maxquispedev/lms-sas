<?php

namespace App\Providers\Filament;

use App\Support\Branding\BrandingRepository;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\CorreoCorporativoWidget;
use App\Filament\Widgets\SocioTecnologicoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->brandLogo(function (): ?string {
                /** @var BrandingRepository $brandingRepository */
                $brandingRepository = app(BrandingRepository::class);
                $settings = $brandingRepository->get();

                if (!$settings->logo_path) {
                    return null;
                }

                return $brandingRepository->urlFor($settings->logo_path);
            })
            ->darkModeBrandLogo(function (): ?string {
                /** @var BrandingRepository $brandingRepository */
                $brandingRepository = app(BrandingRepository::class);
                $settings = $brandingRepository->get();

                if ($settings->dark_logo_path) {
                    return $brandingRepository->urlFor($settings->dark_logo_path);
                }

                if ($settings->logo_path) {
                    return $brandingRepository->urlFor($settings->logo_path);
                }

                return null;
            })
            ->brandLogoHeight('2.25rem')
            ->brandName(function (): ?string {
                /** @var BrandingRepository $brandingRepository */
                $brandingRepository = app(BrandingRepository::class);
                $settings = $brandingRepository->get();

                return $settings->logo_path ? null : $settings->academy_name;
            })
            ->favicon(function (): ?string {
                /** @var BrandingRepository $brandingRepository */
                $brandingRepository = app(BrandingRepository::class);
                $settings = $brandingRepository->get();

                if (!$settings->favicon_path) {
                    return null;
                }

                return $brandingRepository->urlFor($settings->favicon_path);
            })
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CorreoCorporativoWidget::class,
                SocioTecnologicoWidget::class,
            ])
            ->navigationGroups([
                'Academia',
                'Órdenes y Alumnos',
                'Configuración',
                'Admin',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Admin')
                    ->navigationSort(999),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
