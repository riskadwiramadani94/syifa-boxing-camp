<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class VideoController extends Controller
{
    // Ekstensi yang dianggap video
    private static array $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

    public static function isVideo(string $file): bool
    {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($ext, self::$videoExts);
    }

    public function index()
    {
        $allGaleris = Galeri::orderBy('updated_at', 'desc')->get();

        // Hanya tampilkan item yang punya minimal 1 file video
        $galeris = $allGaleris->filter(function ($item) {
            $files = is_array($item->foto) ? $item->foto : [];
            foreach ($files as $file) {
                if (self::isVideo($file)) return true;
            }
            return false;
        })->values();

        // Siapkan data untuk modal video player
        $galeriData = $galeris->map(function ($item) {
            $files  = is_array($item->foto) ? $item->foto : [];
            $videos = [];
            $thumbs = [];

            foreach ($files as $file) {
                $url = foto_url($file);
                if (self::isVideo($file)) {
                    $videos[] = $url;
                } else {
                    $thumbs[] = $url;
                }
            }

            // Gunakan foto pertama sebagai cover, atau null kalau tidak ada
            $cover = count($thumbs) > 0 ? $thumbs[0] : null;

            return [
                'videos'   => array_values($videos),
                'cover'    => $cover,
                'judul'    => $item->judul,
                'tahun'    => $item->tahun,
                'juara'    => $item->juara,
                'kategori' => $item->kategori,
            ];
        })->values();

        return view('video', compact('galeris', 'galeriData'));
    }
}
