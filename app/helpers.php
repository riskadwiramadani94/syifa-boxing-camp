<?php

if (! function_exists('foto_url')) {
    /**
     * Kembalikan URL foto yang benar tanpa hit API Cloudinary.
     * - Jika sudah URL lengkap (http/https) → langsung kembalikan.
     * - Jika FILESYSTEM_DISK=cloudinary → build URL Cloudinary manual dari cloud_name.
     * - Jika disk lokal → pakai asset('storage/...')
     * - Jika null/kosong → kembalikan fallback.
     */
    function foto_url(?string $foto, string $fallback = ''): string
    {
        if (! $foto) {
            return $fallback ?: asset('assets/logo/logo.jpg');
        }

        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            return $foto;
        }

        if (config('filesystems.default') === 'cloudinary') {
            // Ambil cloud_name dari CLOUDINARY_URL (format: cloudinary://api_key:api_secret@cloud_name)
            $cloudinaryUrl = config('filesystems.disks.cloudinary.url', '');
            $cloudName = parse_url($cloudinaryUrl, PHP_URL_HOST) ?: '';

            if ($cloudName) {
                return "https://res.cloudinary.com/{$cloudName}/image/upload/{$foto}";
            }
        }

        return asset('storage/' . $foto);
    }
}
