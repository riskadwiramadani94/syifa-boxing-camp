<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-sparkles';
    }

    public static function getNavigationLabel(): string
    {
        return 'Event';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Media & Publikasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    protected static ?string $pluralModelLabel = 'Event';

    protected static ?string $modelLabel = 'Event';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Event')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->label('Judul Event')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Event::class, 'slug', ignoreRecord: true),

                    Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required(),

                    Forms\Components\TextInput::make('lokasi')
                        ->label('Lokasi')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('maps_link')
                        ->label('Link Google Maps')
                        ->placeholder('https://maps.app.goo.gl/...')
                        ->helperText('Salin link dari Google Maps → Share → Copy link')
                        ->url()
                        ->nullable(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'selesai' => 'Telah Selesai',
                            'dibuka'  => 'Pendaftaran Dibuka',
                            'segera'  => 'Segera Hadir',
                        ])
                        ->required()
                        ->default('segera'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Tampilkan di Beranda')
                        ->default(true),
                ])->columns(2),

            Section::make('Foto & Deskripsi')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto Event')
                        ->image()
                        ->disk('public')
                        ->directory('media/events')
                        ->imagePreviewHeight('200')
                        ->nullable(),

                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->nullable(),
                ]),

            Section::make('Harga Tiket & Pendaftaran')
                ->description('Isi jika event ini memiliki harga tiket penonton atau biaya pendaftaran atlet. Kosongkan jika tidak ada.')
                ->icon('heroicon-o-ticket')
                ->schema([
                    Forms\Components\TextInput::make('harga_tiket')
                        ->label('Harga Tiket Penonton (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('25000')
                        ->helperText('Contoh: 25000 → tampil Rp 25.000/orang')
                        ->nullable(),

                    Forms\Components\TextInput::make('promo_tiket')
                        ->label('Keterangan Promo Tiket')
                        ->placeholder('Beli 2 Gratis 1 untuk 50 pembeli pertama')
                        ->helperText('Opsional — isi jika ada promo')
                        ->nullable(),

                    Forms\Components\TextInput::make('harga_atlet')
                        ->label('Biaya Pendaftaran Atlet (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('250000')
                        ->helperText('Contoh: 250000 → tampil Rp 250.000/peserta')
                        ->nullable(),

                    Forms\Components\TextInput::make('include_atlet')
                        ->label('Include / Yang Didapat Atlet')
                        ->placeholder('Medali, Sertifikat, Baju Tanding')
                        ->helperText('Pisahkan dengan koma')
                        ->nullable(),
                ])->columns(2),

            Section::make('Kontak Pendaftaran')
                ->description('Nomor WhatsApp khusus untuk pendaftaran event ini. Bisa isi 1 sampai 3 nomor.')
                ->icon('heroicon-o-phone')
                ->schema([
                    Forms\Components\Repeater::make('wa_pendaftaran')
                        ->label('Nomor WhatsApp Pendaftaran')
                        ->schema([
                            Forms\Components\TextInput::make('nomor')
                                ->label('Nomor WA')
                                ->placeholder('6281234567890')
                                ->helperText('Format: kode negara tanpa + (contoh: 6281234567890)')
                                ->required(),
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama / Label')
                                ->placeholder('Contoh: Panitia Event, Admin 1')
                                ->nullable(),
                        ])
                        ->columns(2)
                        ->maxItems(3)
                        ->addActionLabel('+ Tambah Nomor WA')
                        ->defaultItems(0)
                        ->nullable()
                        ->columnSpanFull(),
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
                    ->width(60)
                    ->height(60),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'danger',
                        'dibuka'  => 'success',
                        'segera'  => 'warning',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'selesai' => 'Telah Selesai',
                        'dibuka'  => 'Pendaftaran Dibuka',
                        'segera'  => 'Segera Hadir',
                        default   => $state,
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'selesai' => 'Telah Selesai',
                        'dibuka'  => 'Pendaftaran Dibuka',
                        'segera'  => 'Segera Hadir',
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
            ->defaultSort('tanggal', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
