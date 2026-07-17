<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelatihResource\Pages;
use App\Models\Pelatih;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihResource extends Resource
{
    protected static ?string $model = Pelatih::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pelatih';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Utama';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    protected static ?string $pluralModelLabel = 'Pelatih';

    protected static ?string $modelLabel = 'Pelatih';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pelatih')
                ->schema([
                    Forms\Components\TextInput::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Toggle::make('aktif')
                        ->label('Tampilkan di Website')
                        ->default(true)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Foto Pelatih')
                ->description('Upload satu atau lebih foto. Foto bisa ditambah lagi saat edit.')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->image()
                        ->disk('public')
                        ->directory('media/pelatih')
                        ->panelLayout('grid')
                        ->imagePreviewHeight('150')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                        ->nullable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->getStateUsing(fn ($record) => is_array($record->foto) && count($record->foto) > 0
                        ? $record->foto[0]
                        : null
                    )
                    ->defaultImageUrl(url('assets/logo/logo.jpg'))
                    ->width(60)
                    ->height(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => is_array($record->foto) ? count($record->foto) . ' foto' : '0 foto'),

                Tables\Columns\ToggleColumn::make('aktif')
                    ->label('Aktif'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('aktif')
                    ->label('Status Aktif'),
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
            ->defaultSort('urutan', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPelatih::route('/'),
            'create' => Pages\CreatePelatih::route('/create'),
            'edit'   => Pages\EditPelatih::route('/{record}/edit'),
        ];
    }
}
