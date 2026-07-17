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
        return 'Galeri';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Media & Publikasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    protected static ?string $pluralModelLabel = 'Galeri';

    protected static ?string $modelLabel = 'Galeri';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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

                    Forms\Components\TextInput::make('juara')
                        ->label('Juara ke-')
                        ->numeric()
                        ->minValue(1)
                        ->placeholder('Contoh: 1 = Juara 1, 2 = Juara 2')
                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('kategori') === 'pertandingan')
                        ->nullable(),
                ])->columns(2),

            Section::make('Foto & Media')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto & Video Galeri')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/*', 'video/*'])
                        ->disk('cloudinary')
                        ->directory('media/galeri')
                        ->panelLayout('grid')
                        ->imagePreviewHeight('150')
                        ->nullable(),
                ]),
        ]);
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
                    ->description(fn ($record) => is_array($record->foto) ? count($record->foto) . ' file' : '0 file'),

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
                    ->label('Juara')
                    ->formatStateUsing(fn ($state) => $state ? 'Juara ' . $state : '-')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
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
