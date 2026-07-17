<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasResource\Pages;
use App\Models\Kas;
use App\Models\KasRiwayat;
use App\Models\PendaftaranMember;
use Filament\Actions\Action as ColumnAction;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KasResource extends Resource
{
    protected static ?string $model = Kas::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-currency-dollar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Keuangan';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationLabel(): string
    {
        return 'Iuran Kas Anggota';
    }

    protected static ?string $pluralModelLabel = 'Kas';
    protected static ?string $modelLabel       = 'Kas';

    /**
     * Ambil status kas (belum/cicil/lunas) dari koleksi kas yang sudah di-eager load.
     */
    protected static function getStatus($record, int $bulan, int $tahun): string
    {
        $kas = $record->kas->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if (! $kas) return 'belum';
        if ($kas->status_bayar === 'cicil') return 'cicil';
        if ($kas->status_bayar === 'lunas' || $kas->sudah_bayar) return 'lunas';
        return 'belum';
    }

    public static function table(Table $table): Table
    {
        $tahun     = request()->get('tableFilters')['tahun']['value'] ?? now()->year;
        $namaBulan = Kas::$namaBulan;

        $bulanColumns = [];
        foreach ($namaBulan as $no => $nama) {
            $bulanColumns[] = Tables\Columns\IconColumn::make("bulan_{$no}")
                ->label(substr($nama, 0, 3))
                ->alignCenter()
                ->getStateUsing(function ($record) use ($no, $tahun) {
                    return static::getStatus($record, $no, $tahun);
                })
                ->icon(fn (string $state): string => match ($state) {
                    'lunas' => 'heroicon-o-check-circle',
                    'cicil' => 'heroicon-o-clock',
                    default => 'heroicon-o-x-circle',
                })
                ->color(fn (string $state): string => match ($state) {
                    'lunas' => 'success',
                    'cicil' => 'warning',
                    default => 'danger',
                })
                ->tooltip(function ($record) use ($no, $tahun) {
                    $kas     = $record->kas->where('bulan', $no)->where('tahun', $tahun)->first();
                    $dibayar = $kas ? (int) $kas->jumlah_dibayar : 0;
                    if ($dibayar > 0) {
                        return 'Terbayar: Rp ' . number_format($dibayar, 0, ',', '.');
                    }
                    return 'Belum ada pembayaran';
                })
                ->action(
                    ColumnAction::make("bayar_{$no}")
                        ->modalHeading(fn ($record) => 'Bayar Kas — ' . $record->nama_lengkap . ' (' . $nama . ' ' . $tahun . ')')
                        ->modalDescription(function ($record) use ($no, $tahun) {
                            $kas = Kas::where('anggota_id', $record->id)
                                ->where('tahun', $tahun)
                                ->where('bulan', $no)
                                ->with('riwayat')
                                ->first();

                            if (! $kas || (float) $kas->jumlah_dibayar === 0.0) {
                                return null;
                            }

                            $lines = ['Sudah terbayar: Rp ' . number_format($kas->jumlah_dibayar, 0, ',', '.')];
                            foreach ($kas->riwayat as $r) {
                                $lines[] = '• ' . \Carbon\Carbon::parse($r->tanggal_bayar)->format('d M Y')
                                    . ' — Rp ' . number_format($r->jumlah, 0, ',', '.')
                                    . ' (' . ($r->status === 'lunas' ? 'Lunas' : 'Cicil') . ')'
                                    . ($r->keterangan ? ' — ' . $r->keterangan : '');
                            }
                            return implode("\n", $lines);
                        })
                        ->form([
                            Forms\Components\ToggleButtons::make('status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'cicil' => 'Cicil',
                                    'lunas' => 'Lunas',
                                ])
                                ->colors([
                                    'cicil' => 'warning',
                                    'lunas' => 'success',
                                ])
                                ->icons([
                                    'cicil' => 'heroicon-o-clock',
                                    'lunas' => 'heroicon-o-check-circle',
                                ])
                                ->inline()
                                ->required(),

                            Forms\Components\TextInput::make('jumlah')
                                ->label('Jumlah Dibayar (Rp)')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->prefix('Rp'),

                            Forms\Components\DatePicker::make('tanggal_bayar')
                                ->label('Tanggal Bayar')
                                ->default(now()->toDateString())
                                ->required(),

                            Forms\Components\TextInput::make('keterangan')
                                ->label('Keterangan')
                                ->placeholder('cicil pertama, pelunasan, dll.')
                                ->maxLength(255),
                        ])
                        ->action(function ($record, array $data) use ($no, $tahun) {
                            $kas = Kas::firstOrCreate(
                                ['anggota_id' => $record->id, 'tahun' => $tahun, 'bulan' => $no],
                                ['sudah_bayar' => false, 'jumlah' => 0, 'jumlah_dibayar' => 0, 'status_bayar' => 'belum']
                            );

                            KasRiwayat::create([
                                'kas_id'        => $kas->id,
                                'jumlah'        => $data['jumlah'],
                                'status'        => $data['status'],
                                'keterangan'    => $data['keterangan'] ?? null,
                                'tanggal_bayar' => $data['tanggal_bayar'],
                            ]);

                            $kas->recalculate();

                            Notification::make()
                                ->title('Pembayaran berhasil!')
                                ->body($data['status'] === 'lunas' ? 'Status: Lunas' : 'Status: Cicil')
                                ->success()
                                ->send();
                        })
                        ->modalSubmitActionLabel('Simpan')
                        ->modalWidth('md')
                );
        }

        return $table
            ->query(
                PendaftaranMember::query()
                    ->where('aktif', true)
                    ->with(['kas' => fn ($q) => $q->where('tahun', $tahun)->with('riwayat')])
            )
            ->columns(array_merge(
                [
                    Tables\Columns\TextColumn::make('nama_lengkap')
                        ->label('Nama Anggota')
                        ->searchable()
                        ->sortable(),
                ],
                $bulanColumns,
                [
                    Tables\Columns\TextColumn::make('total_bayar')
                        ->label('Total')
                        ->getStateUsing(function ($record) use ($tahun) {
                            $kasLunas  = $record->kas->where('tahun', $tahun)->where('sudah_bayar', true);
                            $jumlahBln = $kasLunas->count();
                            $nominalTotal = $record->kas->where('tahun', $tahun)->sum('jumlah_dibayar');
                            if ($jumlahBln === 0) return '0 bulan';
                            return $jumlahBln . ' bulan (Rp ' . number_format($nominalTotal, 0, ',', '.') . ')';
                        })
                        ->badge()
                        ->color('success'),
                ]
            ))
            ->filters([
                Tables\Filters\SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(function () {
                        $years = [];
                        for ($y = now()->year - 2; $y <= now()->year + 1; $y++) {
                            $years[$y] = $y;
                        }
                        return $years;
                    })
                    ->default(now()->year)
                    ->query(fn (Builder $query) => $query),
            ])
            ->actions([
                ActionGroup::make([
                    ColumnAction::make('lihat_detail')
                        ->label('Lihat Detail & Riwayat')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->url(fn ($record) => KasResource::getUrl('view', ['record' => $record->id])),
                ]),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKas::route('/'),
            'view'  => Pages\ViewKas::route('/{record}'),
        ];
    }
}
