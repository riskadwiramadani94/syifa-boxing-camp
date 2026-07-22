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

    public function show(string $uuid)
    {
        $galeri = Galeri::where('uuid', $uuid)->firstOrFail();

        $files  = is_array($galeri->foto) ? $galeri->foto : [];
        $fotos  = [];
        $videos = [];

        foreach ($files as $file) {
            if (self::isVideo($file)) {
                $videos[] = [
                    'url'   => foto_url($file),
                    'thumb' => video_thumb_url($file),
                    'nama'  => basename($file),
                ];
            } else {
                $fotos[] = foto_url($file);
            }
        }

        // Kelompokkan medali: emas=1, perak=2, perunggu=3
        $medaliEmas     = 0;
        $medaliPerak    = 0;
        $medaliPerunggu = 0;

        if ($galeri->juara) {
            foreach ($galeri->juaraArray() as $angka) {
                if ($angka === 1)     $medaliEmas++;
                elseif ($angka === 2) $medaliPerak++;
                elseif ($angka === 3) $medaliPerunggu++;
            }
        }

        return view('gallery-detail', compact(
            'galeri', 'fotos', 'videos',
            'medaliEmas', 'medaliPerak', 'medaliPerunggu'
        ));
    }

    public function index()
    {
        // Tampilkan galeri yang punya foto: tipe foto atau foto_video
        $galeris = Galeri::whereIn('tipe_konten', ['foto', 'foto_video'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $galeriData = $galeris->map(function ($item) {
            $files = is_array($item->foto) ? $item->foto : [];

            // Hanya kirim file foto ke lightbox (filter out video)
            $fotoUrls = array_values(array_map(
                fn($f) => foto_url($f),
                array_filter($files, fn($f) => !self::isVideo($f))
            ));

            if (empty($fotoUrls)) {
                $fotoUrls = [asset('assets/logo/logo.jpg')];
            }

            return [
                'uuid'       => $item->uuid,
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
