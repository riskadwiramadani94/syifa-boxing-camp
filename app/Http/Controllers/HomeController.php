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

        // Data untuk modal lightbox di beranda
        $galeriData = $galeris->map(function ($item) {
            $fotos = is_array($item->foto) ? $item->foto : [];
            $fotoUrls = count($fotos) > 0
                ? array_map(fn($f) => foto_url($f), $fotos)
                : [asset('assets/logo/logo.jpg')];
            return [
                'fotos'      => array_values($fotoUrls),
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
