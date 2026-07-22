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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Saat form dibuka untuk edit, pisahkan foto dan video dari array foto
        $allFiles  = is_array($data['foto'] ?? null) ? $data['foto'] : [];
        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

        $fotoFiles  = [];
        $videoFiles = [];
        $videoLinks = [];

        foreach ($allFiles as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $videoExts)) {
                $videoFiles[] = $file;
            } elseif (
                str_contains($file, 'youtube') ||
                str_contains($file, 'youtu.be') ||
                str_contains($file, 'instagram')
            ) {
                $videoLinks[] = ['url' => $file];
            } else {
                $fotoFiles[] = $file;
            }
        }

        $data['foto']        = $fotoFiles;
        $data['video_files'] = $videoFiles;
        $data['video_links'] = $videoLinks;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

        // is_video_only tidak dipakai lagi
        $data['is_video_only'] = false;

        return $data;
    }
}
