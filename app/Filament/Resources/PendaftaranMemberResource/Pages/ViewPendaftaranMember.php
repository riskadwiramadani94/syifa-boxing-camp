<?php

namespace App\Filament\Resources\PendaftaranMemberResource\Pages;

use App\Filament\Resources\PendaftaranMemberResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPendaftaranMember extends ViewRecord
{
    protected static string $resource = PendaftaranMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
