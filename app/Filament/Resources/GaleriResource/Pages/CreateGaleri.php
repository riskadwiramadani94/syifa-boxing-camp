<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use App\Filament\Traits\HasSimpanAction;
use App\Filament\Resources\GaleriResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleri extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = GaleriResource::class;
}
