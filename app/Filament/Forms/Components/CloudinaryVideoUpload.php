<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class CloudinaryVideoUpload extends Field
{
    protected string $view = 'filament.forms.components.cloudinary-video-upload';

    /**
     * Cloud name dari env CLOUDINARY_URL (cloudinary://key:secret@cloud_name)
     */
    public function getCloudName(): string
    {
        $url = config('filesystems.disks.cloudinary.url', '');
        return parse_url($url, PHP_URL_HOST) ?: '';
    }

    /**
     * Upload preset (unsigned) — buat di Cloudinary dashboard:
     * Settings → Upload → Upload presets → Add upload preset → Signing mode: Unsigned
     */
    public function getUploadPreset(): string
    {
        return config('cloudinary.upload_preset', env('CLOUDINARY_UPLOAD_PRESET', 'syifa_videos'));
    }

    /**
     * Folder tujuan di Cloudinary
     */
    public function getFolder(): string
    {
        return 'media/galeri';
    }
}
