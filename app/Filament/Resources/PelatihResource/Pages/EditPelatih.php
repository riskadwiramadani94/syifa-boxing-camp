<?php

namespace App\Filament\Resources\PelatihResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\PelatihResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPelatih extends EditRecord
{
    use HasSimpanAction;
    protected static string $resource = PelatihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
