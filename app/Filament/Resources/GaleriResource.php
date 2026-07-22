<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriResource\Pages;
use App\Models\Event;
use App\Models\Galeri;
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
        return 'Galeri Foto & Video';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Media & Publikasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    protected static ?string $pluralModelLabel = 'Galeri Foto & Video';
    protected static ?string $modelLabel       = 'Galeri';

    /**
     * Tampilkan semua record — tidak filter lagi berdasarkan is_video_only.
     * Semua galeri (foto, video, foto+video) dikelola dari sini.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            // ── Kolom kiri: Informasi + Prestasi ─────────────────────────────
            Section::make('Informasi Galeri')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    // Dropdown tipe konten — menentukan field upload yang muncul
                    Forms\Components\Select::make('tipe_konten')
                        ->label('Tipe Konten')
                        ->options([
                            'foto'       => '🖼️  Foto saja',
                            'video'      => '🎬  Video saja',
                            'foto_video' => '🖼️🎬  Foto & Video',
                        ])
                        ->required()
                        ->default('foto')
                        ->live()
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
                        ->placeholder('Tulis deskripsi atau penjelasan singkat...')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),

                    // Prestasi — hanya tampil saat kategori pertandingan/event
                    Forms\Components\TextInput::make('juara')
                        ->label('Rekap Medali Cepat (pisah koma)')
                        ->placeholder('Contoh: 1,1,3  →  2 Emas + 1 Perunggu')
                        ->helperText('Isi angka juara dipisah koma.')
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

                    // Daftar Atlet Juara
                    Section::make('Daftar Atlet Juara')
                        ->description('Opsional: Tambahkan nama atlet beserta juara yang diraih.')
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('kategori'), ['pertandingan', 'event']))
                        ->schema([
                            Forms\Components\Repeater::make('daftar_juara')
                                ->label('')
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
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(1),

            // ── Kolom kanan: Upload media (kondisional) ───────────────────────
            Section::make('Media')
                ->schema([

                    // ── Upload FOTO (tampil saat tipe = foto atau foto_video) ──
                    Forms\Components\FileUpload::make('foto')
                        ->label('Upload Foto')
                        ->helperText('Format: JPG, PNG, WEBP, GIF')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                        ->disk('cloudinary')
                        ->directory('media/galeri')
                        ->panelLayout('grid')
                        ->imagePreviewHeight('150')
                        ->nullable()
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('tipe_konten'), ['foto', 'foto_video'])),

                    // ── Upload VIDEO (tampil saat tipe = video atau foto_video) ──
                    Forms\Components\FileUpload::make('video_files')
                        ->label('Upload File Video')
                        ->helperText('Format: MP4, MOV, AVI, WEBM')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/x-matroska', 'video/*'])
                        ->disk('cloudinary')
                        ->directory('media/video')
                        ->panelLayout('grid')
                        ->nullable()
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('tipe_konten'), ['video', 'foto_video'])),

                    // ── Link YouTube (tampil saat tipe = video atau foto_video) ──
                    Section::make('Link YouTube / Video Eksternal')
                        ->description('Tambahkan link YouTube atau platform lain.')
                        ->schema([
                            Forms\Components\Repeater::make('video_links')
                                ->label(false)
                                ->addActionLabel('+ Tambah Link Video')
                                ->schema([
                                    Forms\Components\TextInput::make('url')
                                        ->label('URL Video')
                                        ->placeholder('https://youtube.com/watch?v=...')
                                        ->url()
                                        ->nullable(),
                                ])
                                ->default([])
                                ->reorderable(false)
                                ->collapsible()
                                ->nullable(),
                        ])
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => in_array($get('tipe_konten'), ['video', 'foto_video'])),

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
                            // Kalau video YT, ambil thumbnail
                            foreach ($record->foto as $file) {
                                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $file, $m);
                                if (!empty($m[1])) {
                                    return 'https://img.youtube.com/vi/' . $m[1] . '/mqdefault.jpg';
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
                        $files     = is_array($record->foto) ? $record->foto : [];
                        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                        $videoCount = 0;
                        $fotoCount  = 0;
                        foreach ($files as $file) {
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($ext, $videoExts)) $videoCount++;
                            elseif (str_contains($file, 'youtube') || str_contains($file, 'youtu.be')) $videoCount++;
                            else $fotoCount++;
                        }
                        if ($fotoCount > 0 && $videoCount > 0) return $fotoCount . ' foto | ' . $videoCount . ' video';
                        if ($fotoCount > 0)  return $fotoCount . ' foto';
                        if ($videoCount > 0) return $videoCount . ' video';
                        return '0 file';
                    }),

                Tables\Columns\TextColumn::make('tipe_konten')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'foto'       => 'info',
                        'video'      => 'warning',
                        'foto_video' => 'success',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'foto'       => '🖼️ Foto',
                        'video'      => '🎬 Video',
                        'foto_video' => '🖼️🎬 Foto & Video',
                        default      => $state ?? '-',
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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_konten')
                    ->label('Tipe Konten')
                    ->options([
                        'foto'       => '🖼️ Foto',
                        'video'      => '🎬 Video',
                        'foto_video' => '🖼️🎬 Foto & Video',
                    ]),
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
