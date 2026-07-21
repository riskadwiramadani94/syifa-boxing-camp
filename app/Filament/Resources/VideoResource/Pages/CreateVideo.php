<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\VideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVideo extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = VideoResource::class;

    /**
     * Gabungkan URL video dari widget Cloudinary + link repeater ke field foto
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fotos = is_array($data['foto'] ?? null) ? $data['foto'] : [];

        // Dari repeater link video — gabungkan ke array foto
        $links = $data['video_links'] ?? [];
        foreach ($links as $link) {
            if (! empty($link['url'])) $fotos[] = $link['url'];
        }

        unset($data['video_links']);
        $data['foto'] = array_values($fotos);
        
        // Set is_video_only = true agar hanya tampil di halaman Video, tidak di Galeri
        $data['is_video_only'] = true;
        
        return $data;
    }
}
