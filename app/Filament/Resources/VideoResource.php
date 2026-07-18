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
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Cari semua galeri yang punya video berdasarkan ekstensi file di JSON foto
        // SQLite: gunakan LIKE, karena JSON_CONTAINS tidak tersedia
        return parent::getEloquentQuery()
            ->where(function ($q) {
                $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                foreach ($videoExts as $ext) {
                    $q->orWhere('foto', 'like', '%.' . $ext . '%');
                }
                // Link YouTube/video
                $q->orWhere('foto', 'like', '%youtube%');
                $q->orWhere('foto', 'like', '%youtu.be%');
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
                        ->default('latihan'),

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
                ])->columns(2),

            Section::make('Upload File Video')
                ->description('Upload file video. Format: MP4, MOV, AVI, WEBM.')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Upload Video')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/x-matroska', 'video/*'])
                        ->disk('cloudinary')
                        ->directory('media/video')
                        ->nullable(),
                ]),

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
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
