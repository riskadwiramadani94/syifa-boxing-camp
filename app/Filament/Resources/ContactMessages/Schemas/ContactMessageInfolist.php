<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;

class ContactMessageInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Pengirim')
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama')
                            ->weight('bold'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('created_at')
                            ->label('Waktu Kirim')
                            ->dateTime('d M Y, H:i'),
                        IconEntry::make('is_read')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-clock')
                            ->trueColor('success')
                            ->falseColor('warning'),
                    ])
                    ->columns(2),
                Section::make('Isi Pesan')
                    ->schema([
                        TextEntry::make('pesan')
                            ->label('')
                            ->prose(),
                    ]),
            ]);
    }
}
