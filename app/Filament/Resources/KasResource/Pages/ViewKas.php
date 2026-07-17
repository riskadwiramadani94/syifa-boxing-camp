<?php

namespace App\Filament\Resources\KasResource\Pages;

use App\Filament\Resources\KasResource;
use App\Models\Kas;
use App\Models\KasRiwayat;
use App\Models\PendaftaranMember;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ViewKas extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = KasResource::class;

    protected string $view = 'filament.resources.kas.view-kas';

    public PendaftaranMember $anggota;

    public int $tahun;

    public function mount(int $record): void
    {
        $this->anggota = PendaftaranMember::findOrFail($record);
        $this->tahun   = now()->year;
    }

    public function getTitle(): string
    {
        return 'Detail Kas — ' . $this->anggota->nama_lengkap;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(KasResource::getUrl('index')),
        ];
    }

    public function gantiTahun(int $tahun): void
    {
        $this->tahun = $tahun;
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        $anggotaId = $this->anggota->id;
        $tahun     = $this->tahun;
        $namaBulan = Kas::$namaBulan;

        return $table
            ->query(
                KasRiwayat::query()
                    ->whereHas('kas', fn (Builder $q) =>
                        $q->where('anggota_id', $anggotaId)->where('tahun', $tahun)
                    )
                    ->with('kas')
                    ->orderBy('tanggal_bayar')
            )
            ->columns([
                Tables\Columns\TextColumn::make('kas.bulan')
                    ->label('Bulan')
                    ->formatStateUsing(fn ($state) => ($namaBulan[$state] ?? '-') . ' ' . $tahun)
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->alignRight(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'lunas',
                        'warning' => 'cicil',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'lunas' ? 'Lunas' : 'Cicil'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->default('-')
                    ->limit(40),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'cicil' => 'Cicil',
                                'lunas' => 'Lunas',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Nominal (Rp)')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->prefix('Rp'),

                        Forms\Components\DatePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->required(),

                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(255),
                    ])
                    ->after(function (KasRiwayat $record) {
                        $record->kas->recalculate();
                        Notification::make()
                            ->title('Riwayat berhasil diupdate')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->after(function (KasRiwayat $record) {
                        // Reload kas sebelum recalculate karena record sudah dihapus
                        $kas = Kas::find($record->kas_id);
                        if ($kas) $kas->recalculate();
                        Notification::make()
                            ->title('Riwayat berhasil dihapus')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Belum ada riwayat pembayaran')
            ->emptyStateDescription('Klik ikon bulan di halaman kas untuk mencatat pembayaran.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public function getKasSummary(): array
    {
        $kasAll = Kas::where('anggota_id', $this->anggota->id)
            ->where('tahun', $this->tahun)
            ->get();

        return [
            'total_lunas'   => $kasAll->where('sudah_bayar', true)->count(),
            'total_cicil'   => $kasAll->where('status_bayar', 'cicil')->count(),
            'total_nominal' => $kasAll->sum('jumlah_dibayar'),
        ];
    }
}
