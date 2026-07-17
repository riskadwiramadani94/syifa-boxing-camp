<?php

namespace App\Filament\Resources\Transaksis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class TransaksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pemasukan' => 'success',
                        'Pengeluaran' => 'danger',
                        default => 'gray',
                    })
                    ->description(fn (\App\Models\Transaksi $record): ?string => $record->tipe_pemasukan),

                \Filament\Tables\Columns\TextColumn::make('rincian')
                    ->label('Rincian')
                    ->getStateUsing(function (\App\Models\Transaksi $record) {
                        if ($record->jenis === 'Pemasukan') {
                            return 'Dari: ' . $record->nama_pemberi;
                        }
                        return 'Untuk: ' . $record->keperluan;
                    })
                    ->description(fn (\App\Models\Transaksi $record): ?string => $record->keterangan_tambahan)
                    ->searchable(['nama_pemberi', 'keperluan']),

                \Filament\Tables\Columns\TextColumn::make('nilai')
                    ->label('Nominal / Barang')
                    ->getStateUsing(function (\App\Models\Transaksi $record) {
                        if ($record->tipe_pemasukan === 'Barang') {
                            return $record->jumlah_barang . ' x ' . $record->nama_barang;
                        }
                        return 'Rp ' . number_format($record->nominal, 0, ',', '.');
                    })
                    ->color(fn (\App\Models\Transaksi $record): string => $record->jenis === 'Pemasukan' && $record->tipe_pemasukan === 'Barang' ? 'info' : 'gray'),

                \Filament\Tables\Columns\ImageColumn::make('bukti_foto')
                    ->label('Bukti')
                    ->circular(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
