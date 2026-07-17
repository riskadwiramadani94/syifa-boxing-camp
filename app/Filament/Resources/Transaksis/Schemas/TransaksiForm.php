<?php

namespace App\Filament\Resources\Transaksis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class TransaksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Transaksi')->schema([
                    Grid::make(2)->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now()),

                        Select::make('jenis')
                            ->label('Jenis Transaksi')
                            ->options([
                                'Pemasukan' => 'Pemasukan (Uang Masuk / Barang Masuk)',
                                'Pengeluaran' => 'Pengeluaran (Uang Keluar)',
                            ])
                            ->required()
                            ->live(),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('tipe_pemasukan')
                            ->label('Bentuk Pemasukan')
                            ->options([
                                'Uang' => 'Berupa Uang (Rp)',
                                'Barang' => 'Berupa Barang / Aset',
                            ])
                            ->visible(fn (Get $get) => $get('jenis') === 'Pemasukan')
                            ->required(fn (Get $get) => $get('jenis') === 'Pemasukan')
                            ->live(),

                        TextInput::make('nama_pemberi')
                            ->label('Nama Pemberi / Sumber')
                            ->placeholder('Contoh: Donatur, Sponsor, Hamba Allah')
                            ->visible(fn (Get $get) => $get('jenis') === 'Pemasukan'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('keperluan')
                            ->label('Keperluan / Untuk apa?')
                            ->placeholder('Contoh: Beli sarung tinju, Bayar listrik')
                            ->visible(fn (Get $get) => $get('jenis') === 'Pengeluaran')
                            ->required(fn (Get $get) => $get('jenis') === 'Pengeluaran'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('nominal')
                            ->label('Nominal Rupiah')
                            ->prefix('Rp')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('jenis') === 'Pengeluaran' || ($get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Uang'))
                            ->required(fn (Get $get) => $get('jenis') === 'Pengeluaran' || ($get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Uang')),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Contoh: Samsak Tinju 10kg')
                            ->visible(fn (Get $get) => $get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Barang')
                            ->required(fn (Get $get) => $get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Barang'),
                            
                        TextInput::make('jumlah_barang')
                            ->label('Jumlah Barang')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Barang')
                            ->required(fn (Get $get) => $get('jenis') === 'Pemasukan' && $get('tipe_pemasukan') === 'Barang'),
                    ]),

                ]),

                Section::make('Dokumentasi & Catatan')->schema([
                    FileUpload::make('bukti_foto')
                        ->label('Bukti Foto / Struk / Nota')
                        ->image()
                        ->disk('cloudinary')
                        ->directory('keuangan/transaksi')
                        ->columnSpanFull(),

                    Textarea::make('keterangan_tambahan')
                        ->label('Keterangan Tambahan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
            ]);
    }
}
