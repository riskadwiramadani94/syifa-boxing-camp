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
     * Merge video dari CloudinaryVideoUpload ke dalam array foto sebelum disimpan.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeCloudinaryVideos($data);
    }

    private function mergeCloudinaryVideos(array $data): array
    {
        $videos = [];

        // Ambil dari field video_cloudinary (JSON string atau array)
        if (!empty($data['video_cloudinary'])) {
            $raw = $data['video_cloudinary'];
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $videos  = is_array($decoded) ? $decoded : [];
            } elseif (is_array($raw)) {
                $videos = $raw;
            }
        }

        // Merge ke array foto
        $fotos = is_array($data['foto'] ?? null) ? $data['foto'] : [];
        $data['foto'] = array_values(array_unique(array_merge($fotos, $videos)));

        // Hapus field sementara
        unset($data['video_cloudinary']);

        return $data;
    }
}
