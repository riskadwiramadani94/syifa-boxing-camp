<?php

namespace App\Filament\Widgets;

use App\Models\Kas;
use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;

class KeuanganChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Arus Keuangan Bulanan';

    protected ?string $description = 'Pemasukan vs Pengeluaran per bulan (tahun ini)';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $namaBulan   = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $pemasukan   = [];
        $pengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            // Iuran kas bulan ini
            $iuranKas = Kas::where('tahun', now()->year)
                ->where('bulan', $i)
                ->where('sudah_bayar', true)
                ->sum('jumlah');

            // Pemasukan transaksi bulan ini
            $pemasukanTransaksi = Transaksi::where('jenis', 'Pemasukan')
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', now()->year)
                ->sum('nominal');

            $pemasukan[]   = $iuranKas + $pemasukanTransaksi;
            $pengeluaran[] = Transaksi::where('jenis', 'Pengeluaran')
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', now()->year)
                ->sum('nominal');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Pemasukan',
                    'data'            => $pemasukan,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.85)',
                    'borderColor'     => 'rgba(34, 197, 94, 1)',
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
                [
                    'label'           => 'Pengeluaran',
                    'data'            => $pengeluaran,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.85)',
                    'borderColor'     => 'rgba(239, 68, 68, 1)',
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $namaBulan,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive'          => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 1000,
                'easing'   => 'easeInOutQuart',
                'delay'    => 200,
            ],
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'top',
                    'labels'   => [
                        'color'          => 'rgba(100,116,139,0.9)',
                        'padding'        => 16,
                        'usePointStyle'  => true,
                        'pointStyleWidth'=> 8,
                        'font'           => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid'        => ['color' => 'rgba(0,0,0,0.05)'],
                    'ticks'       => [
                        'color' => 'rgba(100,116,139,0.8)',
                    ],
                ],
                'x' => [
                    'grid'  => ['display' => false],
                    'ticks' => ['color' => 'rgba(100,116,139,0.8)'],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode'      => 'index',
            ],
        ];
    }
}
