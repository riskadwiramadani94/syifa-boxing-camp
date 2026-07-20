<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VideoController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/event', [EventController::class, 'index'])->name('event');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{uuid}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/video', [VideoController::class, 'index'])->name('video');
Route::view('/schedule', 'schedule')->name('schedule');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.send');



// ROUTE SEMENTARA - HAPUS SETELAH DIPAKAI
Route::get('/clear-galeri-syifa2026', function () {
    \App\Models\Galeri::truncate();
    return 'Semua data galeri & video berhasil dihapus.';
});

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => url('/'),          'priority' => '1.0', 'changefreq' => 'weekly'],
        ['loc' => url('/about'),     'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => url('/event'),     'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => url('/gallery'),   'priority' => '0.8', 'changefreq' => 'weekly'],
        ['loc' => url('/schedule'),  'priority' => '0.7', 'changefreq' => 'monthly'],
        ['loc' => url('/contact'),   'priority' => '0.7', 'changefreq' => 'monthly'],
    ];

    try {
        $events = \App\Models\Event::where('is_active', true)->get();
        foreach ($events as $event) {
            $urls[] = [
                'loc'        => url('/event/' . $event->uuid),
                'priority'   => '0.8',
                'changefreq' => 'monthly',
                'lastmod'    => $event->updated_at ? $event->updated_at->toAtomString() : null,
            ];
        }
    } catch (\Exception $e) {
        // lanjut tanpa event
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
        $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
        $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
        if (!empty($url['lastmod'])) {
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
        }
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');
