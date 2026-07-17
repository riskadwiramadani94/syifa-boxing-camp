<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = EventResource::class;
}
