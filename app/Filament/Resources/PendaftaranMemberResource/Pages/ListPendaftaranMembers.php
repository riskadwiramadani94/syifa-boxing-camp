<?php

namespace App\Filament\Resources\PendaftaranMemberResource\Pages;

use App\Filament\Resources\PendaftaranMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranMembers extends ListRecords
{
    protected static string $resource = PendaftaranMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Member')
                ->icon('heroicon-o-plus'),
        ];
    }
}
