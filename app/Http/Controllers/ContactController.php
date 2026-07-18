<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $settings = [
            'whatsapp'            => SiteSettings::get('whatsapp', '6281234567890'),
            'email'               => SiteSettings::get('email', 'info@syifaboxingcamp.com'),
            'alamat'              => SiteSettings::get('alamat', 'GOR Padjadjaran, Kota Bandung, Jawa Barat'),
            'maps_embed'          => SiteSettings::get('maps_embed'),
            'instagram'           => SiteSettings::get('instagram'),
            'facebook'            => SiteSettings::get('facebook'),
            'nama_tempat_latihan' => SiteSettings::get('nama_tempat_latihan', 'GOR Padjadjaran, Kota Bandung, Jawa Barat'),
            'maps_url'            => SiteSettings::get('maps_url'),
        ];

        return view('contact', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'pesan' => 'required|string',
        ]);

        ContactMessage::create([
            'nama'  => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
        ]);

        return redirect()->route('contact')->with('success', 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
