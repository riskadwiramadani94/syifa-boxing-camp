<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\JadwalLatihan;
use Filament\Widgets\Widget;

class EventJadwalWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;

    protected string $view = 'filament.widgets.event-jadwal-widget';

    protected function getViewData(): array
    {
        $hariIni = now()->locale('id')->isoFormat('dddd');

        $hariMap = [
            'Senin'  => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu'   => 'Rabu',
            'Kamis'  => 'Kamis',
            'Jumat'  => 'Jumat',
            'Sabtu'  => 'Sabtu',
            'Minggu' => 'Minggu',
        ];

        $hariDb = $hariMap[$hariIni] ?? $hariIni;

        $jadwalHariIni = JadwalLatihan::where('aktif', true)
            ->where('hari', $hariDb)
            ->orderBy('jam_mulai')
            ->get();

        $events = Event::whereIn('status', ['dibuka', 'segera'])
            ->where('is_active', true)
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        return [
            'hariIni'       => $hariIni,
            'jadwalHariIni' => $jadwalHariIni,
            'events'        => $events,
        ];
    }
}
