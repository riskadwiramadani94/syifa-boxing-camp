<?php

namespace App\Filament\Resources\Transaksis\Pages;

use App\Filament\Traits\HasSimpanAction;

use App\Filament\Resources\Transaksis\TransaksiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksi extends CreateRecord
{
    use HasSimpanAction;
    protected static string $resource = TransaksiResource::class;
}
