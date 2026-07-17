<?php

namespace App\Filament\Traits;

use Filament\Actions\Action;

trait HasSimpanAction
{
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan')
            ->color('info');
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan')
            ->color('info');
    }
}
