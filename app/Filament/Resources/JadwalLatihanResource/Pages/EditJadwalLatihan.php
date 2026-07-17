<?php

namespace App\Filament\Resources\JadwalLatihanResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\JadwalLatihanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalLatihan extends EditRecord
{
    use HasSimpanAction;
    protected static string $resource = JadwalLatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
