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

</body>
</html>