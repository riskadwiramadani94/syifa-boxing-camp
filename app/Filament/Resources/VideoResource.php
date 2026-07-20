<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Event;
use App\Models\Galeri;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Infolists;
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
            // Kolom kiri: Informasi Video + Link YT
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
                        ->columnSpanFull()
                        ->rows(3),

                    // Link YT tetap di kolom kiri (section dalam section)
                    Forms\Components\Group::make([
                        Forms\Components\Placeholder::make('link_label')
                            ->label('')
                            ->content('Link Video (YouTube / Instagram / lainnya)')
                            ->extraAttributes(['class' => 'font-semibold text-sm']),

                        Forms\Components\Repeater::make('video_links')
                            ->label('')
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
                    ])->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(1),

            // Kolom kanan: Upload Video
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
                        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

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
                    ->defaultImageUrl(url('assets/logo/logo.jpg'))
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
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            // Kolom kiri: Informasi + Link Video
            Section::make('Informasi Video')
                ->schema([
                    Infolists\Components\TextEntry::make('judul')
                        ->label('Judul')
                        ->weight(\Filament\Support\Enums\FontWeight::Bold)
                        ->columnSpanFull(),

                    Infolists\Components\TextEntry::make('kategori')
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

                    Infolists\Components\TextEntry::make('tahun')
                        ->label('Tahun'),

                    Infolists\Components\TextEntry::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('—')
                        ->columnSpanFull(),

                    // Link video YouTube
                    Infolists\Components\TextEntry::make('foto')
                        ->label('Link Video')
                        ->getStateUsing(function ($record) {
                            $files = is_array($record->foto) ? $record->foto : [];
                            $links = [];
                            foreach ($files as $file) {
                                if (str_contains($file, 'youtube') || str_contains($file, 'youtu.be') || str_contains($file, 'instagram')) {
                                    $links[] = $file;
                                }
                            }
                            return implode("\n", $links) ?: null;
                        })
                        ->placeholder('Tidak ada link video')
                        ->columnSpanFull()
                        ->listWithLineBreaks(),
                ])
                ->columns(2)
                ->columnSpan(1),

            // Kolom kanan: Preview video
            Section::make('Preview Video')
                ->schema([
                    Infolists\Components\ViewEntry::make('foto')
                        ->label('')
                        ->view('filament.infolists.video-preview')
                        ->columnSpanFull(),
                ])
                ->columnSpan(1),
        ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit'   => Pages\EditVideo::route('/{record}/edit'),
            'view'   => Pages\ViewVideo::route('/{record}'),
        ];
    }
}
