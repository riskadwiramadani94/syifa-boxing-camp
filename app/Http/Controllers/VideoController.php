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

    public function show(string $uuid)
    {
        $galeri = Galeri::where('uuid', $uuid)->firstOrFail();

        $files  = is_array($galeri->foto) ? $galeri->foto : [];
        $videos = [];

        foreach ($files as $file) {
            if (self::isVideo($file)) {
                $videos[] = [
                    'url'   => foto_url($file),
                    'thumb' => video_thumb_url($file),
                    'nama'  => basename($file),
                ];
            } elseif (str_contains($file, 'youtube') || str_contains($file, 'youtu.be') || str_contains($file, 'instagram')) {
                // YouTube / link eksternal
                $ytId = null;
                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $file, $m);
                if (!empty($m[1])) $ytId = $m[1];
                $videos[] = [
                    'url'   => $file,
                    'thumb' => $ytId ? 'https://img.youtube.com/vi/' . $ytId . '/mqdefault.jpg' : null,
                    'nama'  => $galeri->judul,
                    'type'  => 'youtube',
                    'yt_id' => $ytId,
                ];
            }
        }

        return view('video-detail', compact('galeri', 'videos'));
    }

    public function index()
    {
        $allGaleris = Galeri::orderBy('updated_at', 'desc')->get();

        // Hanya tampilkan item yang punya minimal 1 file video atau link YT
        $galeris = $allGaleris->filter(function ($item) {
            $files = is_array($item->foto) ? $item->foto : [];
            foreach ($files as $file) {
                if (self::isVideo($file)) return true;
                if (str_contains($file, 'youtube') || str_contains($file, 'youtu.be')) return true;
            }
            return false;
        })->values();

        // Siapkan data untuk grid
        $galeriData = $galeris->map(function ($item) {
            $files  = is_array($item->foto) ? $item->foto : [];
            $videos = [];
            $cover  = null;

            foreach ($files as $file) {
                if (self::isVideo($file)) {
                    $videos[] = foto_url($file);
                    if (!$cover) $cover = video_thumb_url($file);
                } elseif (str_contains($file, 'youtube') || str_contains($file, 'youtu.be')) {
                    $videos[] = $file;
                    if (!$cover) {
                        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $file, $m);
                        if (!empty($m[1])) $cover = 'https://img.youtube.com/vi/' . $m[1] . '/mqdefault.jpg';
                    }
                }
            }

            return [
                'uuid'     => $item->uuid,
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
