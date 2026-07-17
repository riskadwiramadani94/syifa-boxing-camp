<?php

if (! function_exists('foto_url')) {
    /**
     * Kembalikan URL foto yang benar.
     * - Jika sudah URL lengkap (http/https) → langsung kembalikan.
     * - Jika FILESYSTEM_DISK=cloudinary → pakai Storage::disk('cloudinary')->url()
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
            return \Illuminate\Support\Facades\Storage::disk('cloudinary')->url($foto);
        }

        return asset('storage/' . $foto);
    }
}
