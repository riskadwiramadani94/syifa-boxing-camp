<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactMessage extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = ContactMessageResource::class;
}
