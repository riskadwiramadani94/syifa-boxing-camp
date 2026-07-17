<?php

namespace App\Filament\Resources\PelatihResource\Pages;

use App\Filament\Resources\PelatihResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPelatih extends ListRecords
{
    protected static string $resource = PelatihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
