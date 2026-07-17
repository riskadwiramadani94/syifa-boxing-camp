<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;

class ContactController extends Controller
{
    public function index()
    {
        $settings = [
            'whatsapp'  => SiteSettings::get('whatsapp', '6281234567890'),
            'email'     => SiteSettings::get('email', 'info@syifaboxingcamp.com'),
            'alamat'    => SiteSettings::get('alamat', 'GOR Padjadjaran, Kota Bandung, Jawa Barat'),
            'maps_embed'=> SiteSettings::get('maps_embed'),
            'instagram' => SiteSettings::get('instagram'),
            'facebook'  => SiteSettings::get('facebook'),
        ];

        return view('contact', compact('settings'));
    }
}
