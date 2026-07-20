<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
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

class VideoResource extends Resource
{
    protected static ?string $model = Galeri::class;

    // Pakai slug berbeda agar tidak bentrok dengan GaleriResource
    protected static ?string $slug = 'videos';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-film';
    }

    public static function getNavigationLabel(): string
    {
        return 'Video';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Media & Publikasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    protected static ?string $pluralModelLabel = 'Video';
    protected static ?string $modelLabel       = 'Video';

    /**
     * Hanya tampilkan record yang mengandung minimal 1 file video di field foto
     * Menggunakan CAST ke TEXT agar kompatibel dengan PostgreSQL JSON column
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($q) {
                $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                foreach ($videoExts as $ext) {
                    $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%." . $ext . "%'");
                }
                $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%youtube%'");
                $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%youtu.be%'");
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Kolom kiri: Informasi + Prestasi + Link Video
            Section::make('Informasi Video')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Video')
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
                        ->nullable()
                        ->columnSpanFull(),

                    // Field prestasi — hanya tampil saat kategori pertandingan/event
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

                    // Link Video di dalam kolom kiri agar tidak ikut turun
                    Section::make('Link Video (YouTube / Instagram / lainnya)')
                        ->description('Tambahkan link video dari YouTube, Instagram, atau platform lain. Bisa lebih dari 1.')
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
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(1),

            // Kolom kanan: Upload File Video
            Section::make('Upload File Video')
                ->description('Upload file video. Format: MP4, MOV, AVI, WEBM.')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Upload Video')
                        ->multiple()
                        ->panelLayout('grid')
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/x-matroska', 'video/*'])
                        ->disk('cloudinary')
                        ->directory('media/video')
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
                    ->label('Preview')
                    ->getStateUsing(function ($record) {
                        $files = is_array($record->foto) ? $record->foto : [];

                        // Ambil thumbnail YouTube
                        foreach ($files as $file) {
                            if (str_contains($file, 'youtube.com') || str_contains($file, 'youtu.be')) {
                                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $file, $m);
                                if (!empty($m[1])) {
                                    return 'https://img.youtube.com/vi/' . $m[1] . '/mqdefault.jpg';
                                }
                            }
                        }

                        return null;
                    })
                    ->defaultImageUrl('https://placehold.co/80x50/1f2937/6b7280?text=VIDEO')
                    ->width(80)
                    ->height(50),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->description(function ($record) {
                        $files     = is_array($record->foto) ? $record->foto : [];
                        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                        $count     = 0;
                        foreach ($files as $file) {
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($ext, $videoExts)) $count++;
                            if (str_starts_with($file, 'http') && str_contains($file, 'youtube')) $count++;
                        }
                        return $count . ' video';
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
            'index'  => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit'   => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
