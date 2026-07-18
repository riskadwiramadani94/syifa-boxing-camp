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
            ->get();

        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

        // Filter galeri foto (ambil yang punya minimal 1 foto/bukan video)
        $galeriList = $galeris->filter(function ($item) use ($videoExts) {
            $files = is_array($item->foto) ? $item->foto : [];
            if (empty($files)) return true; // anggap galeri kosong tetap galeri
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (!in_array($ext, $videoExts)) return true;
            }
            return false;
        })->take(6)->values();

        // Filter video (ambil yang punya minimal 1 video)
        $videoList = $galeris->filter(function ($item) use ($videoExts) {
            $files = is_array($item->foto) ? $item->foto : [];
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $videoExts)) return true;
            }
            return false;
        })->take(4)->values();

        $urutanHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];

        $jadwals = JadwalLatihan::where('aktif', true)
            ->get()
            ->sortBy(fn($j) => $urutanHari[$j->hari] ?? 8);

        return view('home', compact('events', 'galeriList', 'videoList', 'jadwals'));
    }
}
