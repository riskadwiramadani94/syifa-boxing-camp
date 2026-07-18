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

        // Dari widget direct upload Cloudinary
        $videosJson = request()->input('cloudinary_videos', '[]');
        $videos     = json_decode($videosJson, true) ?: [];
        foreach ($videos as $v) {
            if (! empty($v['url'])) $fotos[] = $v['url'];
        }

        // Dari repeater link video
        $links = $data['video_links'] ?? [];
        foreach ($links as $link) {
            if (! empty($link['url'])) $fotos[] = $link['url'];
        }

        unset($data['video_links']);
        $data['foto'] = array_values($fotos);
        return $data;
    }
}
