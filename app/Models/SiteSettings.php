<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Gunakan cache agar tidak query berulang.
     */
    public static function get(string $key, string $default = ''): string
    {
        return Cache::remember("site_settings_{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Simpan atau update nilai setting.
     * Otomatis hapus cache setelah update.
     */
    public static function set(string $key, string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_settings_{$key}");
    }

    /**
     * Hapus semua cache settings.
     */
    public static function clearCache(): void
    {
        $keys = ['nama_sasana', 'tagline', 'deskripsi', 'tahun_berdiri',
                 'whatsapp', 'email', 'alamat', 'maps_embed', 'instagram', 'tiktok', 'facebook',
                 'foto_profil', 'foto_tentang', 'foto_beranda',
                 'hero_badge', 'hero_judul', 'hero_desc',
                 'stat_member', 'stat_tahun', 'stat_prestasi', 'stat_medali'];

        foreach ($keys as $key) {
            Cache::forget("site_settings_{$key}");
        }
        Cache::forget('site_settings_all');
    }
}
