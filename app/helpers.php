<?php

if (! function_exists('foto_url')) {
    /**
     * Kembalikan URL foto yang benar.
     * Jika sudah berupa URL lengkap (Cloudinary, http/https) → langsung kembalikan.
     * Jika berupa path lokal → pakai asset('storage/...')
     * Jika null → kembalikan fallback.
     */
    function foto_url(?string $foto, string $fallback = ''): string
    {
        if (! $foto) {
            return $fallback ?: asset('assets/logo/logo.jpg');
        }

        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            return $foto;
        }

        return asset('storage/' . $foto);
    }
}
