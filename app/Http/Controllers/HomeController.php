<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\JadwalLatihan;
use App\Models\Galeri;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('tanggal_mulai', 'desc')
            ->limit(3)
            ->get();

        $galeris = Galeri::orderBy('updated_at', 'desc')
            ->orderBy('tahun', 'desc')
            ->limit(8)
            ->get();

        // Data untuk modal lightbox di beranda (foto + video)
        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
        $galeriData = $galeris->map(function ($item) use ($videoExts) {
            $files = is_array($item->foto) ? $item->foto : [];
            if (count($files) === 0) {
                $files = [''];
            }
            $items = array_map(function ($f) use ($videoExts) {
                $ext     = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                $isVideo = in_array($ext, $videoExts);
                $url     = $f ? foto_url($f) : asset('assets/logo/logo.jpg');
                return [
                    'url'     => $url,
                    'isVideo' => $isVideo,
                ];
            }, $files);

            // Cover = file pertama (foto lebih diutamakan, kalau semua video pakai video pertama)
            $coverItem = collect($items)->firstWhere('isVideo', false) ?? $items[0];

            return [
                'items'      => array_values($items),
                'cover'      => $coverItem['url'],
                'coverVideo' => $coverItem['isVideo'],
                'judul'      => $item->judul,
                'tahun'      => $item->tahun,
                'juara'      => $item->juara,
                'kategori'   => $item->kategori,
                'keterangan' => $item->keterangan,
            ];
        })->values();

        $urutanHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];

        $jadwals = JadwalLatihan::where('aktif', true)
            ->get()
            ->sortBy(fn($j) => $urutanHari[$j->hari] ?? 8);

        return view('home', compact('events', 'galeris', 'jadwals', 'galeriData'));
    }
}
