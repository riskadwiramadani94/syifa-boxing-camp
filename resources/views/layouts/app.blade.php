<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['nama_sasana'] ?? 'SYIFA Boxing Camp')</title>

    @php
        $metaDesc   = $siteSettings['deskripsi'] ?: ($siteSettings['tagline'] . ' — Sasana tinju profesional sejak ' . $siteSettings['tahun_berdiri'] . '.');
        $fotoBeranda = \App\Models\SiteSettings::get('foto_beranda');
        $fotoProfil  = \App\Models\SiteSettings::get('foto_profil');
        $defaultImg  = $fotoProfil
            ? asset('storage/' . $fotoProfil)
            : ($fotoBeranda ? asset('storage/' . $fotoBeranda) : asset('assets/images/polosan_logo_syifa.png'));
        $defaultOgTitle = ($siteSettings['nama_sasana'] ?? 'Syifa Boxing Camp') . ' — ' . ($siteSettings['tagline'] ?? 'Sasana Tinju Profesional');
    @endphp

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', $metaDesc)">
    <meta name="keywords" content="@yield('meta_keywords', ($siteSettings['nama_sasana'] ?? 'Syifa Boxing Camp') . ', sasana tinju, latihan tinju, atlet tinju, boxing indonesia')">
    <meta name="robots" content="index, follow">

    {{-- Open Graph (Facebook, WhatsApp, dll) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', $defaultOgTitle)">
    <meta property="og:description" content="@yield('og_description', $metaDesc)">
    <meta property="og:image" content="@yield('og_image', $defaultImg)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteSettings['nama_sasana'] ?? 'Syifa Boxing Camp' }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $defaultOgTitle)">
    <meta name="twitter:description" content="@yield('og_description', $metaDesc)">
    <meta name="twitter:image" content="@yield('og_image', $defaultImg)">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/polosan_logo_syifa.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/polosan_logo_syifa.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS Global (variabel, reset, tipografi, tombol) -->
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <!-- CSS Layout (navbar, footer) -->
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">

    @stack('styles')
</head>

<body>

    <div class="site-wrapper">

        @include('layouts.navbar')

        <main>
            @yield('content')
        </main>

        @include('layouts.footer')

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts')

    {{-- WhatsApp Floating Button --}}
    @if($siteSettings['whatsapp'])
    <a href="https://wa.me/{{ $siteSettings['whatsapp'] }}?text={{ urlencode('Halo Admin Syifa Boxing Camp 👋, saya ingin bertanya lebih lanjut.') }}"
       target="_blank"
       class="wa-float"
       title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <style>
        .wa-float {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            background: #25d366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            box-shadow: 0 4px 16px rgba(37,211,102,0.45);
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            animation: waPulse 2.5s infinite;
        }
        .wa-float:hover {
            transform: scale(1.12);
            box-shadow: 0 8px 24px rgba(37,211,102,0.6);
            color: #fff;
        }
        @keyframes waPulse {
            0%, 100% { box-shadow: 0 4px 16px rgba(37,211,102,0.45); }
            50%       { box-shadow: 0 4px 28px rgba(37,211,102,0.75); }
        }
        @media (max-width: 576px) {
            .wa-float { width: 50px; height: 50px; font-size: 1.5rem; bottom: 20px; right: 20px; }
        }
    </style>
    @endif

</body>
</html>