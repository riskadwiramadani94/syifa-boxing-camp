<?php

namespace App\Providers;

use App\Models\SiteSettings;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS di production (Render pakai reverse proxy)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Share settings ke semua view (footer, navbar, dll)
        View::composer('*', function ($view) {
            // Hanya untuk view frontend, bukan filament
            if (!str_starts_with($view->getName(), 'filament')) {
                $view->with('siteSettings', [
                    'nama_sasana'   => SiteSettings::get('nama_sasana', 'Syifa Boxing Camp'),
                    'tagline'       => SiteSettings::get('tagline', 'Sasana Tinju Profesional'),
                    'deskripsi'     => SiteSettings::get('deskripsi'),
                    'tahun_berdiri' => SiteSettings::get('tahun_berdiri', '1998'),
                    'whatsapp'      => SiteSettings::get('whatsapp', '6281234567890'),
                    'email'         => SiteSettings::get('email', 'info@syifaboxingcamp.com'),
                    'alamat'        => SiteSettings::get('alamat', 'GOR Padjadjaran, Kota Bandung'),
                    'instagram'     => SiteSettings::get('instagram'),
                    'tiktok'        => SiteSettings::get('tiktok'),
                    'facebook'      => SiteSettings::get('facebook'),
                ]);
            }
        });
    }
}
