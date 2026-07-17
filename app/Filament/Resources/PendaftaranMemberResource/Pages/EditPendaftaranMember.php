<?php

namespace App\Filament\Resources\PendaftaranMemberResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\PendaftaranMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaranMember extends EditRecord
{
    use HasSimpanAction;
    protected static string $resource = PendaftaranMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
