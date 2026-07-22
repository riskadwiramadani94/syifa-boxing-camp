<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\GaleriResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleri extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = GaleriResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Foto dari FileUpload foto (gambar)
        $fotos = is_array($data['foto'] ?? null) ? $data['foto'] : [];

        // File video dari FileUpload video_files — gabung ke array foto
        $videoFiles = is_array($data['video_files'] ?? null) ? $data['video_files'] : [];
        foreach ($videoFiles as $vf) {
            if (!empty($vf)) $fotos[] = $vf;
        }
        unset($data['video_files']);

        // Link YouTube/eksternal dari repeater video_links — gabung ke array foto
        $links = $data['video_links'] ?? [];
        foreach ($links as $link) {
            if (!empty($link['url'])) $fotos[] = $link['url'];
        }
        unset($data['video_links']);

        $data['foto'] = array_values($fotos);

        // is_video_only tidak dipakai lagi, tapi tetap set false agar tidak ada konflik
        $data['is_video_only'] = false;

        return $data;
    }
}
