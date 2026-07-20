<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriResource\Pages;
use App\Models\Event;
use App\Models\Galeri;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function getNavigationLabel(): string
    {
        return 'Galeri Foto';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Media & Publikasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    protected static ?string $pluralModelLabel = 'Galeri Foto';

    protected static ?string $modelLabel = 'Galeri Foto';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Kolom kiri: Informasi Galeri + Daftar Atlet Juara
            Section::make('Informasi Galeri')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Galeri')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'latihan'      => 'Latihan',
                            'event'        => 'Event',
                            'pertandingan' => 'Pertandingan',
                        ])
                        ->required()
                        ->default('latihan')
                        ->live(),

                    Forms\Components\Select::make('event_id')
                        ->label('Tautkan ke Event')
                        ->placeholder('— Pilih event (opsional) —')
                        ->options(
                            Event::orderBy('tanggal_mulai', 'desc')
                                ->get()
                                ->mapWithKeys(fn ($e) => [$e->id => $e->judul . ' (' . ($e->tanggal_mulai?->format('Y') ?? '-') . ')'])
                        )
                        ->searchable()
                        ->nullable(),

                    Forms\Components\TextInput::make('tahun')
                        ->label('Tahun')
                        ->required()
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(now()->year),

                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('Tulis deskripsi atau penjelasan singkat tentang galeri ini...')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('juara')
                        ->label('Rekap Medali Cepat (pisah koma)')
                        ->placeholder('Contoh: 1,1,3  →  2 Emas + 1 Perunggu')
                        ->helperText('Isi angka juara dipisah koma. Gunakan ini untuk sisa medali yang tidak diinput detail atletnya.')
                        ->rules(['nullable', 'regex:/^[0-9,\s]*$/'])
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kategori'), ['pertandingan', 'event']))
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('juara_umum')
                        ->label('Juara Umum')
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kategori'), ['pertandingan', 'event']))
                        ->default(false),

                    Forms\Components\Toggle::make('petinju_terbaik')
                        ->label('Petinju Terbaik')
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kategori'), ['pertandingan', 'event']))
                        ->default(false),

                    // Daftar Atlet Juara tetap di kolom kiri
                    Forms\Components\Group::make([
                        Forms\Components\Repeater::make('daftar_juara')
                            ->label('Daftar Atlet Juara')
                            ->addActionLabel('+ Tambah Atlet')
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Atlet')
                                    ->placeholder('Contoh: Muhammad Rizky')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('juara_ke')
                                    ->label('Juara Ke-')
                                    ->options([
                                        '1' => '🥇 Juara 1 (Emas)',
                                        '2' => '🥈 Juara 2 (Perak)',
                                        '3' => '🥉 Juara 3 (Perunggu)',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['nama'] ?? 'Atlet') . (isset($state['juara_ke']) ? ' — Juara ' . $state['juara_ke'] : '')
                            )
                            ->defaultItems(0)
                            ->nullable(),
                    ])
                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kategori'), ['pertandingan', 'event']))
                    ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(1),

            // Kolom kanan: Foto Galeri
            Section::make('Foto Galeri')
                ->description('Upload foto dokumentasi galeri. Format: JPG, PNG, WEBP. Bisa pilih banyak sekaligus.')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Upload Foto')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                        ->disk('cloudinary')
                        ->directory('media/galeri')
                        ->panelLayout('grid')
                        ->imagePreviewHeight('150')
                        ->nullable(),
                ])
                ->columnSpan(1),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Media')
                    ->disk('cloudinary')
                    ->getStateUsing(function ($record) {
                        if (is_array($record->foto)) {
                            foreach ($record->foto as $file) {
                                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                                    return $file;
                                }
                            }
                            return null;
                        }
                        return $record->foto;
                    })
                    ->defaultImageUrl(url('assets/logo/logo.jpg'))
                    ->width(60)
                    ->height(60),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->description(function ($record) {
                        $files = is_array($record->foto) ? $record->foto : [];
                        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                        $videoCount = 0;
                        $fotoCount  = 0;
                        foreach ($files as $file) {
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($ext, $videoExts)) $videoCount++;
                            else $fotoCount++;
                        }
                        if ($fotoCount > 0 && $videoCount > 0) return $fotoCount . ' foto | ' . $videoCount . ' video';
                        if ($fotoCount > 0)  return $fotoCount . ' foto';
                        if ($videoCount > 0) return $videoCount . ' video';
                        return '0 file';
                    }),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'latihan'      => 'info',
                        'event'        => 'warning',
                        'pertandingan' => 'success',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'latihan'      => 'Latihan',
                        'event'        => 'Event',
                        'pertandingan' => 'Pertandingan',
                        default        => $state,
                    }),

                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('juara')
                    ->label('Prestasi')
                    ->getStateUsing(function ($record) {
                        $parts = [];
                        $medali = $record->jumlahMedali();
                        if ($medali > 0) $parts[] = $medali . ' medali';
                        if ($record->juara_umum)      $parts[] = 'Juara Umum';
                        if ($record->petinju_terbaik) $parts[] = 'Petinju Terbaik';
                        return count($parts) ? implode(' | ', $parts) : '-';
                    })
                    ->badge()
                    ->color(fn ($state) => $state === '-' ? 'gray' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'latihan'      => 'Latihan',
                        'event'        => 'Event',
                        'pertandingan' => 'Pertandingan',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGaleris::route('/'),
            'create' => Pages\CreateGaleri::route('/create'),
            'edit'   => Pages\EditGaleri::route('/{record}/edit'),
        ];
    }
}
