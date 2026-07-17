<?php

namespace App\Filament\Resources\PendaftaranMemberResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\PendaftaranMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaranMember extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = PendaftaranMemberResource::class;
}
