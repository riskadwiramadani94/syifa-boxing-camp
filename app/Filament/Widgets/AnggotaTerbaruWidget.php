<?php

namespace App\Filament\Widgets;

use App\Models\PendaftaranMember;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AnggotaTerbaruWidget extends BaseWidget
{
    protected static ?string $heading = 'Anggota Terbaru';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected static ?int $defaultPaginationPageOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranMember::query()
                    ->where('aktif', true)
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->weight('bold')
                    ->limit(20)
                    ->extraAttributes([
                        'class' => 'transition-colors duration-200',
                    ]),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (string $state): string => match(true) {
                        str_contains($state, 'U15')    => 'U15',
                        str_contains($state, 'U17')    => 'U17',
                        str_contains($state, 'U19')    => 'U19',
                        str_contains($state, 'Senior') => 'Senior',
                        default                        => $state,
                    }),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Laki-laki' ? 'info' : 'pink'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->since()
                    ->color('gray'),
            ])
            ->paginated(false)
            ->striped();
    }
}
