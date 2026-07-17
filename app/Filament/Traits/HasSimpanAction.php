<?php

namespace App\Filament\Traits;

use Filament\Actions\Action;

trait HasSimpanAction
{
    // Tombol Simpan di Edit
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan')
            ->icon('fas-floppy-disk')
            ->color('info');
    }

    // Tombol Simpan di Create
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan')
            ->icon('fas-floppy-disk')
            ->color('info');
    }

    // Tombol "Create & create another" → "Simpan & Tambah Lagi"
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Tambah Lagi')
            ->icon('heroicon-o-plus')
            ->color('gray');
    }

    // Tombol Cancel → Batal
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
