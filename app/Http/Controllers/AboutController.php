<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Pelatih;
use App\Models\SiteSettings;

class AboutController extends Controller
{
    public function index()
    {
        $pelatih = Pelatih::where('aktif', true)
            ->orderBy('urutan', 'asc')
            ->get();

        $settings = [
            'nama_sasana'   => SiteSettings::get('nama_sasana', 'Syifa Boxing Camp'),
            'tagline'       => SiteSettings::get('tagline', 'Sasana Tinju Profesional'),
            'deskripsi'     => SiteSettings::get('deskripsi'),
            'tahun_berdiri' => SiteSettings::get('tahun_berdiri', '1998'),
            'whatsapp'      => SiteSettings::get('whatsapp', '6281234567890'),
            'alamat'        => SiteSettings::get('alamat'),
        ];

        $totalPrestasi = Galeri::where('kategori', 'pertandingan')->count();
        $totalMedali   = Galeri::where('kategori', 'pertandingan')->whereNotNull('juara')->count();

        return view('about', compact('pelatih', 'settings', 'totalPrestasi', 'totalMedali'));
    }
}
