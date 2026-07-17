<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GalleryController extends Controller
{
    public function index()
    {
        $galeris = Galeri::orderBy('updated_at', 'desc')->get();

        $galeriData = $galeris->map(function ($item) {
            $fotos = is_array($item->foto) ? $item->foto : [];
            // kirim semua foto dalam galeri ini
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

        return view('gallery', compact('galeris', 'galeriData'));
    }
}
