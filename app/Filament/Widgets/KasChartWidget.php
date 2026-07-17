<?php

namespace App\Filament\Widgets;

use App\Models\Kas;
use Filament\Widgets\ChartWidget;

class KasChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected ?string $heading = 'Pemasukan Kas Bulanan';

    protected ?string $description = 'Jumlah anggota yang bayar kas per bulan (tahun ini)';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $labels    = [];
        $data      = [];
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $namaBulan[$i - 1];
            $data[]   = Kas::where('tahun', now()->year)
                            ->where('bulan', $i)
                            ->where('sudah_bayar', true)
                            ->count();
        }

        return [
            'datasets' => [
                [
                    'label'                => 'Anggota Bayar',
                    'data'                 => $data,
                    'borderColor'          => 'rgba(34, 197, 94, 1)',
                    'backgroundColor'      => 'rgba(34, 197, 94, 0.1)',
                    'pointBackgroundColor' => 'rgba(34, 197, 94, 1)',
                    'pointBorderColor'     => '#fff',
                    'pointRadius'          => 5,
                    'pointHoverRadius'     => 7,
                    'fill'                 => true,
                    'tension'              => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                    'grid'        => ['color' => 'rgba(255,255,255,0.05)'],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}
