<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['nama_sasana'] ?? 'SYIFA Boxing Camp')</title>

    @php
        $metaDesc   = $siteSettings['deskripsi'] ?: ($siteSettings['tagline'] . ' — Sasana tinju sejak ' . $siteSettings['tahun_berdiri'] . '.');
        $fotoBeranda = \App\Models\SiteSettings::get('foto_beranda');
        $fotoProfil  = \App\Models\SiteSettings::get('foto_profil');
        $defaultImg  = $fotoProfil
            ? foto_url($fotoProfil)
            : ($fotoBeranda ? foto_url($fotoBeranda) : asset('assets/images/polosan_logo_syifa.png'));
        $defaultOgTitle = ($siteSettings['nama_sasana'] ?? 'Syifa Boxing Camp') . ' — ' . ($siteSettings['tagline'] ?? 'Sasana Tinju');
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

    {{-- Google Search Console Verification --}}
    <meta name="google-site-verification" content="DtAALwNmuenakYeQ895Jrk-pI4IWQ4YAzSzsup7IHhI" />

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

    <!-- GLightbox (Lightbox untuk foto & video) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <!-- CSS Global (variabel, reset, tipografi, tombol) -->
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <!-- CSS Layout (navbar, footer) -->
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">

    @stack('styles')
</head>

<body>

    {{-- Loading Screen --}}
    <div id="loading-screen">
        <div class="loading-inner">
            <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Syifa Boxing Camp" class="loading-logo">
            <div class="loading-bar-wrap">
                <div class="loading-bar"></div>
            </div>
        </div>
    </div>

    <div class="site-wrapper">

        @include('layouts.navbar')

        <main>
            @yield('content')
        </main>

        @include('layouts.footer')

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 60,
        });
    </script>

    <!-- JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts')

    {{-- Back to Top Button --}}
    <button id="back-to-top" title="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </button>

    {{-- WhatsApp Floating Button --}}
    @if($siteSettings['whatsapp'])
    <a href="https://wa.me/{{ $siteSettings['whatsapp'] }}?text={{ urlencode('Halo Admin Syifa Boxing Camp 👋, saya ingin bertanya lebih lanjut.') }}"
       target="_blank"
       class="wa-float"
       title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <style>
        /* ===== LOADING SCREEN ===== */
        #loading-screen {
            position: fixed;
            inset: 0;
            background: #ffffff;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #loading-screen.hide {
            opacity: 0;
            visibility: hidden;
        }
        .loading-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .loading-logo {
            width: 90px;
            animation: loadingPulse 1s ease-in-out infinite alternate;
        }
        @keyframes loadingPulse {
            from { transform: scale(0.92); opacity: 0.7; }
            to   { transform: scale(1.05); opacity: 1; }
        }
        .loading-bar-wrap {
            width: 120px;
            height: 3px;
            background: rgba(0,0,0,0.08);
            border-radius: 2px;
            overflow: hidden;
        }
        .loading-bar {
            height: 100%;
            background: #cc2929;
            border-radius: 2px;
            animation: loadingBar 0.9s ease forwards;
        }
        @keyframes loadingBar {
            from { width: 0; }
            to   { width: 100%; }
        }

        /* ===== BACK TO TOP ===== */
        #back-to-top {
            position: fixed;
            bottom: 96px;
            right: 28px;
            z-index: 9998;
            width: 44px;
            height: 44px;
            background: #1a2a4a;
            color: #fff;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.3s, visibility 0.3s, transform 0.3s, background 0.2s;
        }
        #back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        #back-to-top:hover {
            background: #cc2929;
        }

        /* ===== WA FLOAT ===== */
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
            #back-to-top { bottom: 82px; right: 20px; width: 40px; height: 40px; }
        }
    </style>

    <script>
        // ===== LOADING SCREEN =====
        // Hanya tampil saat fresh load / refresh, tidak saat navigasi antar halaman
        const loadingEl = document.getElementById('loading-screen');
        if (sessionStorage.getItem('syifa_loaded')) {
            // Sudah pernah load di sesi ini — langsung sembunyikan
            loadingEl.style.display = 'none';
        } else {
            // Pertama kali / refresh — tampilkan loading
            window.addEventListener('load', function () {
                setTimeout(function () {
                    loadingEl.classList.add('hide');
                    sessionStorage.setItem('syifa_loaded', '1');
                }, 900);
            });
        }

        // ===== BACK TO TOP =====
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }, { passive: true });
    </script>
    @endif

</body>
</html>