<?php

namespace App\Filament\Resources\JadwalLatihanResource\Pages;

use App\Filament\Resources\JadwalLatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalLatihans extends ListRecords
{
    protected static string $resource = JadwalLatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Jadwal')
                ->icon('heroicon-o-plus')
                ->color('info'),
        ];
    }
}
