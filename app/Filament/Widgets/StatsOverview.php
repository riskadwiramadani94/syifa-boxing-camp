<?php

namespace App\Filament\Widgets;

use App\Models\Kas;
use App\Models\PendaftaranMember;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected int | array | null $columns = 3;

    protected function getStats(): array
    {
        // Anggota aktif
        $totalAnggota     = PendaftaranMember::where('aktif', true)->count();
        $anggotaBulanIni  = PendaftaranMember::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)->count();
        $anggotaBulanLalu = PendaftaranMember::whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)->count();
        $trendAnggota     = $anggotaBulanIni - $anggotaBulanLalu;

        // Kas bulan ini
        $kasBulanIni   = Kas::where('tahun', now()->year)
                            ->where('bulan', now()->month)
                            ->where('sudah_bayar', true)
                            ->count();
        $kasPersentase = $totalAnggota > 0
                            ? round(($kasBulanIni / $totalAnggota) * 100)
                            : 0;

        // Saldo akhir
        $totalIuranKas      = Kas::where('sudah_bayar', true)->sum('jumlah');
        $totalPemasukanLain = Transaksi::where('jenis', 'Pemasukan')->where('tipe_pemasukan', 'Uang')->sum('nominal');
        $totalPemasukan     = $totalIuranKas + $totalPemasukanLain;
        $totalPengeluaran   = Transaksi::where('jenis', 'Pengeluaran')->sum('nominal');
        $saldoAkhir         = $totalPemasukan - $totalPengeluaran;

        return [
            Stat::make('Total Anggota Aktif', $totalAnggota)
                ->description($trendAnggota >= 0
                    ? '+' . $trendAnggota . ' dari bulan lalu'
                    : $trendAnggota . ' dari bulan lalu')
                ->descriptionIcon($trendAnggota >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($trendAnggota >= 0 ? 'success' : 'danger')
                ->chart([
                    PendaftaranMember::whereMonth('created_at', now()->subMonths(5)->month)->count(),
                    PendaftaranMember::whereMonth('created_at', now()->subMonths(4)->month)->count(),
                    PendaftaranMember::whereMonth('created_at', now()->subMonths(3)->month)->count(),
                    PendaftaranMember::whereMonth('created_at', now()->subMonths(2)->month)->count(),
                    $anggotaBulanLalu,
                    $anggotaBulanIni,
                ]),

            Stat::make('Kas ' . now()->translatedFormat('F'), $kasBulanIni . ' / ' . $totalAnggota . ' anggota')
                ->description($kasPersentase . '% anggota sudah bayar')
                ->descriptionIcon($kasPersentase >= 80
                    ? 'heroicon-o-check-circle'
                    : 'heroicon-o-exclamation-circle')
                ->color($kasPersentase >= 80 ? 'success' : ($kasPersentase >= 50 ? 'warning' : 'danger'))
                ->chart([
                    Kas::where('tahun', now()->year)->where('bulan', now()->month - 5)->where('sudah_bayar', true)->count(),
                    Kas::where('tahun', now()->year)->where('bulan', now()->month - 4)->where('sudah_bayar', true)->count(),
                    Kas::where('tahun', now()->year)->where('bulan', now()->month - 3)->where('sudah_bayar', true)->count(),
                    Kas::where('tahun', now()->year)->where('bulan', now()->month - 2)->where('sudah_bayar', true)->count(),
                    Kas::where('tahun', now()->year)->where('bulan', now()->month - 1)->where('sudah_bayar', true)->count(),
                    $kasBulanIni,
                ]),

            Stat::make('Saldo Akhir', 'Rp ' . number_format($saldoAkhir, 0, ',', '.'))
                ->description('Pemasukan - Pengeluaran')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($saldoAkhir >= 0 ? 'primary' : 'danger'),
        ];
    }
}
