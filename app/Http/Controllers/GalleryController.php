<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GalleryController extends Controller
{
    private static array $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

    public static function isVideo(string $file): bool
    {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($ext, self::$videoExts);
    }

    public function index()
    {
        $allGaleris = Galeri::orderBy('updated_at', 'desc')->get();

        // Hanya tampilkan item yang punya minimal 1 file foto
        $galeris = $allGaleris->filter(function ($item) {
            $files = is_array($item->foto) ? $item->foto : [];
            foreach ($files as $file) {
                if (!self::isVideo($file)) return true;
            }
            return false;
        })->values();

        $galeriData = $galeris->map(function ($item) {
            $files = is_array($item->foto) ? $item->foto : [];

            // Hanya kirim file foto ke lightbox
            $fotoUrls = array_values(array_map(
                fn($f) => foto_url($f),
                array_filter($files, fn($f) => !self::isVideo($f))
            ));

            if (empty($fotoUrls)) {
                $fotoUrls = [asset('assets/logo/logo.jpg')];
            }

            return [
                'fotos'      => $fotoUrls,
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
