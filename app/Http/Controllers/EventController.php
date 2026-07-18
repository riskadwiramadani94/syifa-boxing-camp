<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SiteSettings;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('event', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('is_active', true)
            ->where('slug', $slug)
            ->with('galeris')
            ->firstOrFail();

        $whatsapp = SiteSettings::get('whatsapp', '6281234567890');

        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

        // Kumpulkan semua foto flat dari semua galeri terkait event
        $allFotos = collect();
        // Kumpulkan semua video flat dari semua galeri terkait event
        $allVideos = collect();

        foreach ($event->galeris as $galeri) {
            $files = is_array($galeri->foto) ? $galeri->foto : [];
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $videoExts)) {
                    $allVideos->push([
                        'url'   => foto_url($file),
                        'thumb' => video_thumb_url($file),
                    ]);
                } else {
                    $allFotos->push([
                        'url' => foto_url($file),
                    ]);
                }
            }
        }

        return view('event-detail', compact('event', 'whatsapp', 'allFotos', 'allVideos'));
    }
}
