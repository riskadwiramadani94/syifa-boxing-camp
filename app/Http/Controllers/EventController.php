<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SiteSettings;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('tanggal', 'desc')
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

        return view('event-detail', compact('event', 'whatsapp'));
    }
}
