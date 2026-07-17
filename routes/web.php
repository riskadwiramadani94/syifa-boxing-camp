<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/event', [EventController::class, 'index'])->name('event');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::view('/schedule', 'schedule')->name('schedule');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama'  => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'pesan' => 'required|string',
    ]);
    return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
})->name('contact.send');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    return back()->with('success', 'Pendaftaran berhasil! Kami akan segera menghubungi Anda.');
})->name('register.store');
