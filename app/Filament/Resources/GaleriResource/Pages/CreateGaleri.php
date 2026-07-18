<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\GaleriResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleri extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = GaleriResource::class;

    /**
     * Gabungkan URL video dari widget Cloudinary ke field foto sebelum disimpan.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fotos = $data['foto'] ?? [];
        if (! is_array($fotos)) $fotos = [];

        // Ambil video dari hidden input cloudinary_videos (dikirim via request)
        $videosJson = request()->input('cloudinary_videos', '[]');
        $videos = json_decode($videosJson, true) ?: [];

        foreach ($videos as $v) {
            if (! empty($v['url'])) {
                $fotos[] = $v['url'];
            }
        }

        $data['foto'] = array_values($fotos);
        return $data;
    }
}
