<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\VideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVideo extends EditRecord
{
    use HasSimpanAction;
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Gabungkan URL video baru ke file yang sudah ada saat edit
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fotos = is_array($data['foto'] ?? null) ? $data['foto'] : [];

        $videosJson = request()->input('cloudinary_videos', '[]');
        $videos     = json_decode($videosJson, true) ?: [];
        foreach ($videos as $v) {
            if (! empty($v['url'])) $fotos[] = $v['url'];
        }

        $links = $data['video_links'] ?? [];
        foreach ($links as $link) {
            if (! empty($link['url'])) $fotos[] = $link['url'];
        }

        unset($data['video_links']);
        $data['foto'] = array_values($fotos);
        return $data;
    }
}
