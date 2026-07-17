<?php

namespace App\Providers;

use App\Models\SiteSettings;
use Illuminate\Support\Facades\Storage;
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

        // Daftarkan Cloudinary sebagai filesystem driver
        Storage::extend('cloudinary', function ($app, $config) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => $config['cloud_name'],
                    'api_key'    => $config['api_key'],
                    'api_secret' => $config['api_secret'],
                ],
                'url' => [
                    'secure' => true,
                ],
            ]);
            return new \League\Flysystem\Filesystem(
                new \CloudinaryLabs\CloudinaryLaravel\CloudinaryStorageAdapter($cloudinary)
            );
        });

        // Share settings ke semua view (footer, navbar, dll)
        View::composer('*', function ($view) {
            // Hanya untuk view frontend, bukan filament
            if (!str_starts_with($view->getName(), 'filament')) {
                $view->with('siteSettings', [
                    'nama_sasana'         => SiteSettings::get('nama_sasana', 'Syifa Boxing Camp'),
                    'tagline'             => SiteSettings::get('tagline', 'Sasana Tinju Profesional'),
                    'deskripsi'           => SiteSettings::get('deskripsi'),
                    'deskripsi_tentang'   => SiteSettings::get('deskripsi_tentang'),
                    'tahun_berdiri'       => SiteSettings::get('tahun_berdiri', '1998'),
                    'whatsapp'            => SiteSettings::get('whatsapp', '6281234567890'),
                    'email'               => SiteSettings::get('email', 'info@syifaboxingcamp.com'),
                    'alamat'              => SiteSettings::get('alamat', 'GOR Padjadjaran, Kota Bandung'),
                    'instagram'           => SiteSettings::get('instagram'),
                    'tiktok'              => SiteSettings::get('tiktok'),
                    'facebook'            => SiteSettings::get('facebook'),
                    'nama_tempat_latihan' => SiteSettings::get('nama_tempat_latihan', 'GOR Padjadjaran, Kota Bandung, Jawa Barat'),
                    'maps_url'            => SiteSettings::get('maps_url'),
                ]);
            }
        });
    }
}
