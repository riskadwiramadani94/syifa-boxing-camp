<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\AnggotaChartWidget;
use App\Filament\Widgets\AnggotaTerbaruWidget;
use App\Filament\Widgets\EventJadwalWidget;
use App\Filament\Widgets\KeuanganChartWidget;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#8B1A1A'),
            ])
            ->brandName('Admin Syifa')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('assets/images/polosan_logo_syifa.png'))
            ->darkMode(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Manajemen Utama'),
                NavigationGroup::make()
                    ->label('Keuangan'),
                NavigationGroup::make()
                    ->label('Media & Publikasi'),
                NavigationGroup::make()
                    ->label('Pengaturan'),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                KeuanganChartWidget::class,
                AnggotaChartWidget::class,
                AnggotaTerbaruWidget::class,
            ])
            ->renderHook(
                'panels::head.end',
                fn (): View => view('filament.dashboard-animations')
            )
            ->renderHook(
                'panels::body.start',
                fn (): View => view('filament.login-style')
            )
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
