<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalLatihanResource\Pages;
use App\Models\JadwalLatihan;
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

class JadwalLatihanResource extends Resource
{
    protected static ?string $model = JadwalLatihan::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getNavigationLabel(): string
    {
        return 'Jadwal Latihan';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Utama';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    protected static ?string $pluralModelLabel = 'Jadwal Latihan';

    protected static ?string $modelLabel = 'Jadwal Latihan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Jadwal')
                ->schema([
                    Forms\Components\Select::make('hari')
                        ->label('Hari')
                        ->options([
                            'Senin'  => 'Senin',
                            'Selasa' => 'Selasa',
                            'Rabu'   => 'Rabu',
                            'Kamis'  => 'Kamis',
                            'Jumat'  => 'Jumat',
                            'Sabtu'  => 'Sabtu',
                            'Minggu' => 'Minggu',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('kelas')
                        ->label('Nama Kelas')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('contoh: Kelas Pemula, Kelas Sparring'),

                    Forms\Components\TimePicker::make('jam_mulai')
                        ->label('Jam Mulai')
                        ->required()
                        ->seconds(false),

                    Forms\Components\TimePicker::make('jam_selesai')
                        ->label('Jam Selesai')
                        ->required()
                        ->seconds(false),

                    Forms\Components\TextInput::make('pelatih')
                        ->label('Pelatih')
                        ->maxLength(255)
                        ->nullable()
                        ->placeholder('Nama pelatih'),

                    Forms\Components\Toggle::make('aktif')
                        ->label('Aktif')
                        ->default(true)
                        ->helperText('Nonaktifkan jika jadwal ini sedang tidak berjalan'),
                ])->columns(2),

            Section::make('Keterangan Tambahan')
                ->schema([
                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(3)
                        ->nullable()
                        ->placeholder('Informasi tambahan tentang kelas ini'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('pelatih')
                    ->label('Pelatih')
                    ->searchable()
                    ->default('-'),

                Tables\Columns\ToggleColumn::make('aktif')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin'  => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu'   => 'Rabu',
                        'Kamis'  => 'Kamis',
                        'Jumat'  => 'Jumat',
                        'Sabtu'  => 'Sabtu',
                        'Minggu' => 'Minggu',
                    ]),

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
            ->defaultSort('hari');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJadwalLatihans::route('/'),
            'create' => Pages\CreateJadwalLatihan::route('/create'),
            'edit'   => Pages\EditJadwalLatihan::route('/{record}/edit'),
        ];
    }
}
