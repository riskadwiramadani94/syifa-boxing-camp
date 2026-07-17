<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranMemberResource\Pages;
use App\Models\PendaftaranMember;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PendaftaranMemberResource extends Resource
{
    protected static ?string $model = PendaftaranMember::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationLabel(): string
    {
        return 'Data Anggota';
    }

    public static function getModelLabel(): string
    {
        return 'Anggota';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Anggota';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Utama';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Anggota')
                ->schema([
                    Forms\Components\TextInput::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('no_telepon')
                        ->label('No. Telepon')
                        ->required()
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required(),

                    Forms\Components\Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->required(),

                    Forms\Components\Select::make('kelas')
                        ->label('Kelas')
                        ->options([
                            'Schoolboys/Schoolgirls (U15)' => 'Schoolboys/Schoolgirls (U15) — usia 13-14 tahun',
                            'Junior (U17)'                  => 'Junior (U17) — usia 15-16 tahun',
                            'Youth (U19)'                   => 'Youth (U19) — usia 17-18 tahun',
                            'Elite (Senior)'                => 'Elite (Senior) — usia 19-40 tahun',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('berat_badan')
                        ->label('Berat Badan (kg)')
                        ->required()
                        ->numeric()
                        ->minValue(20)
                        ->maxValue(200)
                        ->suffix('kg'),
                ])->columns(2),

            Section::make('Verifikasi & Pembayaran')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status Verifikasi')
                        ->options([
                            'menunggu'      => 'Menunggu Verifikasi',
                            'terverifikasi' => 'Terverifikasi',
                            'ditolak'       => 'Ditolak',
                        ])
                        ->required()
                        ->default('menunggu'),

                    Forms\Components\Toggle::make('aktif')
                        ->label('Anggota Aktif')
                        ->default(true)
                        ->helperText('Nonaktifkan jika anggota sudah tidak aktif'),

                    Forms\Components\Textarea::make('catatan_admin')
                        ->label('Catatan Admin')
                        ->rows(3)
                        ->nullable()
                        ->helperText('Isi jika ada catatan atau alasan penolakan')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('bukti_pembayaran')
                        ->label('Upload Bukti Pembayaran')
                        ->image()
                        ->disk('cloudinary')
                        ->directory('keuangan/bukti-pembayaran')
                        ->imagePreviewHeight('250')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                        ->nullable()
                        ->helperText('Format: JPG atau PNG. Maks 2MB.')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Anggota')
                ->schema([
                    Infolists\Components\TextEntry::make('nama_lengkap')->label('Nama Lengkap'),
                    Infolists\Components\TextEntry::make('no_telepon')->label('No. Telepon'),
                    Infolists\Components\TextEntry::make('email')->label('Email'),
                    Infolists\Components\TextEntry::make('tanggal_lahir')->label('Tanggal Lahir')->date('d M Y'),
                    Infolists\Components\TextEntry::make('jenis_kelamin')->label('Jenis Kelamin'),
                    Infolists\Components\TextEntry::make('kelas')->label('Kelas')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('berat_badan')->label('Berat Badan')->suffix(' kg'),
                ])->columns(2),

            Section::make('Verifikasi & Pembayaran')
                ->schema([
                    Infolists\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'menunggu'      => 'warning',
                            'terverifikasi' => 'success',
                            'ditolak'       => 'danger',
                            default         => 'gray',
                        })
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'menunggu'      => 'Menunggu Verifikasi',
                            'terverifikasi' => 'Terverifikasi',
                            'ditolak'       => 'Ditolak',
                            default         => $state,
                        }),
                    Infolists\Components\TextEntry::make('created_at')->label('Tanggal Daftar')->dateTime('d M Y, H:i'),
                    Infolists\Components\TextEntry::make('catatan_admin')->label('Catatan Admin')->columnSpanFull(),
                    Infolists\Components\ImageEntry::make('bukti_pembayaran')
                        ->label('Bukti Pembayaran')
                        ->disk('cloudinary')
                        ->height(250)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB (kg)')
                    ->suffix(' kg'),

                Tables\Columns\ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti Bayar')
                    ->disk('cloudinary')
                    ->width(60)
                    ->height(60),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu'      => 'warning',
                        'terverifikasi' => 'success',
                        'ditolak'       => 'danger',
                        default         => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu'      => 'Menunggu',
                        'terverifikasi' => 'Terverifikasi',
                        'ditolak'       => 'Ditolak',
                        default         => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('aktif')
                    ->label('Aktif'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu'      => 'Menunggu Verifikasi',
                        'terverifikasi' => 'Terverifikasi',
                        'ditolak'       => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->options([
                        'Schoolboys/Schoolgirls (U15)' => 'Schoolboys/Schoolgirls (U15)',
                        'Junior (U17)'                  => 'Junior (U17)',
                        'Youth (U19)'                   => 'Youth (U19)',
                        'Elite (Senior)'                => 'Elite (Senior)',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),

                    // Verifikasi langsung dari tabel
                    Action::make('verifikasi')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Pendaftaran')
                        ->modalDescription('Yakin ingin memverifikasi pendaftaran ini?')
                        ->visible(fn (PendaftaranMember $record) => $record->status === 'menunggu')
                        ->action(fn (PendaftaranMember $record) => $record->update(['status' => 'terverifikasi'])),

                    // Tolak dengan alasan
                    Action::make('tolak')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Pendaftaran')
                        ->form([
                            Forms\Components\Textarea::make('catatan_admin')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(3),
                        ])
                        ->visible(fn (PendaftaranMember $record) => $record->status === 'menunggu')
                        ->action(fn (PendaftaranMember $record, array $data) => $record->update([
                            'status'        => 'ditolak',
                            'catatan_admin' => $data['catatan_admin'],
                        ])),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPendaftaranMembers::route('/'),
            'create' => Pages\CreatePendaftaranMember::route('/create'),
            'view'   => Pages\ViewPendaftaranMember::route('/{record}'),
            'edit'   => Pages\EditPendaftaranMember::route('/{record}/edit'),
        ];
    }
}
