<?php

namespace App\Filament\Resources\PelatihResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\PelatihResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePelatih extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = PelatihResource::class;
}
