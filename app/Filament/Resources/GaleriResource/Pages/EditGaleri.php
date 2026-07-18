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
     * Saat form dibuka untuk edit, pisahkan video dari array foto
     * ke field video_cloudinary supaya widget bisa menampilkannya.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
        $fotos     = is_array($data['foto'] ?? null) ? $data['foto'] : [];

        $onlyFotos  = array_values(array_filter($fotos, fn($f) => !preg_match('/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i', $f)));
        $onlyVideos = array_values(array_filter($fotos, fn($f) => preg_match('/\.(mp4|mov|avi|webm|mkv|wmv|flv)$/i', $f)));

        $data['foto']             = $onlyFotos;
        $data['video_cloudinary'] = $onlyVideos;

        return $data;
    }

    /**
     * Merge video kembali ke array foto sebelum disimpan.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->mergeCloudinaryVideos($data);
    }

    private function mergeCloudinaryVideos(array $data): array
    {
        $videos = [];

        if (!empty($data['video_cloudinary'])) {
            $raw = $data['video_cloudinary'];
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $videos  = is_array($decoded) ? $decoded : [];
            } elseif (is_array($raw)) {
                $videos = $raw;
            }
        }

        $fotos = is_array($data['foto'] ?? null) ? $data['foto'] : [];
        $data['foto'] = array_values(array_unique(array_merge($fotos, $videos)));

        unset($data['video_cloudinary']);

        return $data;
    }
}
