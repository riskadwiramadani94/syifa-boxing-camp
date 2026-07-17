<?php

namespace App\Filament\Widgets;

use App\Models\PendaftaranMember;
use Filament\Widgets\ChartWidget;

class AnggotaChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected ?string $heading = 'Pertumbuhan Anggota';

    protected ?string $description = 'Jumlah anggota yang bergabung per bulan (6 bulan terakhir)';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');
            $data[]   = PendaftaranMember::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Anggota Baru',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.8)',
                    'borderColor'     => 'rgba(245, 158, 11, 1)',
                    'borderWidth'     => 2,
                    'borderRadius'    => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'animation' => [
                'duration' => 1000,
                'easing'   => 'easeInOutQuart',
                'delay'    => function ($context) {
                    return $context['dataIndex'] * 100;
                },
            ],
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'backgroundColor' => 'rgba(17, 24, 39, 0.9)',
                    'borderColor'     => 'rgba(245, 158, 11, 0.4)',
                    'borderWidth'     => 1,
                    'titleColor'      => 'rgba(255, 255, 255, 0.9)',
                    'bodyColor'       => 'rgba(251, 191, 36, 1)',
                    'padding'         => 10,
                    'cornerRadius'    => 8,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => [
                        'stepSize' => 1,
                        'color'    => 'rgba(255,255,255,0.5)',
                    ],
                    'grid' => ['color' => 'rgba(255,255,255,0.05)'],
                ],
                'x' => [
                    'grid'  => ['display' => false],
                    'ticks' => ['color' => 'rgba(255,255,255,0.5)'],
                ],
            ],
            'hover' => [
                'animationDuration' => 200,
            ],
        ];
    }
}
