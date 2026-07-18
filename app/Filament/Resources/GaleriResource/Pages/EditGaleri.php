<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\GaleriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGaleri extends EditRecord
{
    use HasSimpanAction;
    protected static string $resource = GaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Gabungkan URL video dari widget Cloudinary ke field foto yang sudah ada.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fotos = $data['foto'] ?? [];
        if (! is_array($fotos)) $fotos = [];

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
