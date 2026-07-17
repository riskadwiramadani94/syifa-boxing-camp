<?php

namespace App\Filament\Resources\JadwalLatihanResource\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\JadwalLatihanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalLatihan extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = JadwalLatihanResource::class;
}
