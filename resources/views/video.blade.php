@extends('layouts.app')

@section('title', 'Video - Syifa Boxing Camp')
@section('meta_description', 'Tonton video dokumentasi latihan, pertandingan, dan momen terbaik atlet Syifa Boxing Camp.')
@section('og_title', 'Video - Syifa Boxing Camp')
@section('og_description', 'Tonton video dokumentasi latihan, pertandingan, dan momen terbaik atlet Syifa Boxing Camp.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO ===== --}}
<section class="gallery-hero-section">
    <div class="container">
        <div class="gallery-hero-inner">
            <h1 class="gallery-hero-title g-reveal g-fadeup" style="--gd:0s">
                Video Syifa –
                <span class="gallery-hero-accent">Aksi &<br>Momen Terbaik.</span>
            </h1>
            <p class="gallery-hero-desc g-reveal g-fade" style="--gd:0.18s">
                Tonton dokumentasi latihan, pertandingan, dan pencapaian atlet Syifa Boxing Camp.
            </p>
            <div class="gallery-filter-wrap g-reveal g-fadeup" style="--gd:0.32s">
                <button class="gallery-filter-btn active" data-filter="all">Semua</button>
                <button class="gallery-filter-btn" data-filter="latihan">Latihan</button>
                <button class="gallery-filter-btn" data-filter="event">Event</button>
                <button class="gallery-filter-btn" data-filter="pertandingan">Pertandingan</button>
            </div>
        </div>
    </div>
</section>

{{-- ===== GRID VIDEO ===== --}}
<section class="gallery-grid-section">
    <div class="container">

        <div class="gallery-grid" id="videoGrid">
            @forelse($galeris as $i => $item)
            @php
                $cover = $galeriData[$i]['cover'] ?? null;
                $coverUrl = $cover ?: asset('assets/logo/logo.jpg');
                $animTypes = ['g-fadeleft', 'g-fadeup', 'g-faderight'];
                $anim  = $animTypes[$i % 3];
                $delay = round(($i % 3) * 0.1, 2) . 's';
                $labelKategori = match($item->kategori) {
                    'latihan'      => 'Latihan',
                    'event'        => 'Event',
                    'pertandingan' => 'Pertandingan',
                    default        => ucfirst($item->kategori),
                };
                $videoCount = count($galeriData[$i]['videos'] ?? []);
            @endphp
            <div class="gallery-grid-item g-reveal {{ $anim }}" data-kategori="{{ $item->kategori }}" style="--gd:{{ $delay }}">
                <div class="gallery-grid-card video-card" style="cursor:pointer;" onclick="openVideoModal({{ $i }})">
                    <img src="{{ $coverUrl }}" alt="{{ $item->judul }}" style="object-fit: cover; width:100%;">
                    {{-- Overlay play button --}}
                    <div class="video-play-overlay">
                        <div class="video-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                        @if($videoCount > 1)
                        <span class="video-count-badge">{{ $videoCount }} video</span>
                        @endif
                    </div>
                    <div class="gallery-grid-overlay">
                        <span class="gallery-grid-badge">{{ $item->tahun }}</span>
                        <h6 class="gallery-grid-title">{{ $item->judul }}</h6>
                        <span class="gallery-grid-cat">{{ $labelKategori }}</span>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>

        <div class="gallery-empty" id="videoEmpty" style="{{ $galeris->isEmpty() ? 'display:flex' : 'display:none' }}">
            <i class="fas fa-video"></i>
            <p>Belum ada video tersedia.</p>
        </div>

    </div>
</section>

{{-- ===== MODAL VIDEO PLAYER ===== --}}
<div id="video-lightbox" onclick="if(event.target===this) closeVideoModal()">

    <button class="vl-nav-outer vl-nav-prev" id="vl-prev" onclick="videoModalPrev()">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="vl-modal">

        {{-- Video player --}}
        <div class="vl-video-wrap">
            <video id="vl-video" controls playsinline preload="metadata">
                <source id="vl-source" src="" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>
            <span id="vl-counter" class="vl-counter-overlay"></span>
        </div>

        {{-- Thumbnail strip video --}}
        <div class="vl-thumb-strip" id="vl-thumb-strip"></div>

        {{-- Info + tombol bawah --}}
        <div class="vl-info">
            <div class="vl-info-left">
                <h5 id="vl-judul" class="vl-judul"></h5>
                <div class="vl-info-meta">
                    <span id="vl-tahun" class="vl-tahun"></span>
                    <span id="vl-juara" class="vl-juara-badge" style="display:none;"></span>
                </div>
            </div>
            <div class="vl-info-actions">
                <button class="vl-btn vl-btn-batal" onclick="closeVideoModal()">
                    Tutup
                </button>
            </div>
        </div>

    </div>

    <button class="vl-nav-outer vl-nav-next" id="vl-next" onclick="videoModalNext()">
        <i class="fas fa-chevron-right"></i>
    </button>

</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/gallery.css') }}">
    <style>
        /* ===== VIDEO PLAY OVERLAY ===== */
        .video-card { position: relative; }
        .video-play-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(0,0,0,0.25);
            transition: background 0.2s;
            z-index: 1;
        }
        .video-card:hover .video-play-overlay { background: rgba(0,0,0,0.45); }
        .video-play-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #1e293b;
            transition: transform 0.2s, background 0.2s;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .video-card:hover .video-play-btn { transform: scale(1.12); background: #fff; }
        .video-count-badge {
            background: rgba(0,0,0,0.6);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        /* ===== VIDEO LIGHTBOX ===== */
        #video-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 20px 8px;
        }
        #video-lightbox.vl-active { display: flex; }

        .vl-nav-outer {
            flex-shrink: 0;
            background: rgba(255,255,255,0.12);
            border: none;
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .vl-nav-outer:hover { background: rgba(255,255,255,0.28); }

        .vl-modal {
            background: #1a1a1a;
            border-radius: 14px;
            width: 100%;
            max-width: 640px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 64px rgba(0,0,0,0.7);
        }

        .vl-video-wrap {
            position: relative;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #vl-video {
            width: 100%;
            max-height: 56vh;
            display: block;
            background: #000;
        }
        .vl-counter-overlay {
            position: absolute;
            top: 10px;
            left: 12px;
            background: rgba(0,0,0,0.55);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        /* Thumbnail strip (jika multi video) */
        .vl-thumb-strip {
            display: flex;
            gap: 6px;
            padding: 8px 12px;
            background: #111;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .vl-thumb-strip::-webkit-scrollbar { display: none; }
        .vl-thumb-strip.hidden { display: none; }

        .vl-thumb {
            flex-shrink: 0;
            width: 72px;
            height: 44px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.55;
            transition: border-color 0.18s, opacity 0.18s;
            font-size: 1.2rem;
            color: #fff;
        }
        .vl-thumb.active { border-color: #fff; opacity: 1; }
        .vl-thumb:hover { opacity: 0.85; }

        .vl-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px 16px;
            background: #1a1a1a;
        }
        .vl-info-left { flex: 1; min-width: 0; }
        .vl-judul {
            font-size: 0.95rem;
            font-weight: 700;
            color: #f1f5f9;
            margin: 0 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .vl-info-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .vl-tahun { font-size: 0.78rem; color: #94a3b8; }
        .vl-juara-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
        }
        .vl-juara-badge.medal-1 { background: #fef3c7; color: #92400e; }
        .vl-juara-badge.medal-2 { background: #e2e8f0; color: #334155; }
        .vl-juara-badge.medal-3 { background: #fde8d8; color: #7c3a1a; }
        .vl-juara-badge.medal-other { background: #f1f5f9; color: #475569; }

        .vl-info-actions { flex-shrink: 0; }
        .vl-btn {
            border: none;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
        }
        .vl-btn-batal { background: #3f3f3f; color: #94a3b8; }
        .vl-btn-batal:hover { background: #555; color: #f1f5f9; }

        @media (max-width: 600px) {
            #video-lightbox { padding: 12px 4px; gap: 6px; }
            .vl-nav-outer { width: 36px; height: 36px; font-size: 0.85rem; }
            .vl-modal { border-radius: 12px; }
            #vl-video { max-height: 48vh; }
        }
    </style>
@endpush

@push('scripts')
<script>
    const videoData = @json($galeriData);

    let currentVideo = null;
    let currentVideoIdx = 0;

    function openVideoModal(index) {
        currentVideo    = videoData[index];
        currentVideoIdx = 0;
        renderVlThumbnails();
        showVideoModal();
        document.getElementById('video-lightbox').classList.add('vl-active');
        document.body.style.overflow = 'hidden';
    }

    function renderVlThumbnails() {
        const strip  = document.getElementById('vl-thumb-strip');
        const videos = currentVideo.videos;
        strip.innerHTML = '';

        if (videos.length <= 1) { strip.classList.add('hidden'); return; }
        strip.classList.remove('hidden');

        videos.forEach((src, i) => {
            const div = document.createElement('div');
            div.className = 'vl-thumb' + (i === currentVideoIdx ? ' active' : '');
            div.onclick = () => { currentVideoIdx = i; showVideoModal(); };
            // ikon play sebagai thumbnail
            div.innerHTML = '<i class="fas fa-play" style="font-size:1rem;"></i>';
            strip.appendChild(div);
        });
    }

    function updateVlThumbActive() {
        document.querySelectorAll('.vl-thumb').forEach((t, i) => {
            t.classList.toggle('active', i === currentVideoIdx);
        });
    }

    function showVideoModal() {
        const videos = currentVideo.videos;
        const total  = videos.length;
        const src    = videos[currentVideoIdx];

        // Ganti sumber video
        const videoEl  = document.getElementById('vl-video');
        const sourceEl = document.getElementById('vl-source');
        sourceEl.src = src;
        videoEl.load();

        // Counter
        const counter = document.getElementById('vl-counter');
        if (total > 1) { counter.textContent = (currentVideoIdx + 1) + ' dari ' + total + ' video'; counter.style.display = 'inline-block'; }
        else { counter.style.display = 'none'; }

        // Nav
        const showNav = total > 1;
        document.getElementById('vl-prev').style.visibility = showNav ? 'visible' : 'hidden';
        document.getElementById('vl-next').style.visibility = showNav ? 'visible' : 'hidden';

        updateVlThumbActive();

        document.getElementById('vl-judul').textContent = currentVideo.judul;
        document.getElementById('vl-tahun').textContent = currentVideo.tahun;

        const juaraEl = document.getElementById('vl-juara');
        if (currentVideo.juara) {
            const medals = { 1: ['🥇', 'medal-1'], 2: ['🥈', 'medal-2'], 3: ['🥉', 'medal-3'] };
            const [icon, cls] = medals[currentVideo.juara] || ['🏅', 'medal-other'];
            juaraEl.textContent = icon + ' Juara ' + currentVideo.juara;
            juaraEl.className = 'vl-juara-badge ' + cls;
            juaraEl.style.display = 'inline-block';
        } else { juaraEl.style.display = 'none'; }
    }

    function closeVideoModal() {
        // Pause video dulu
        const videoEl = document.getElementById('vl-video');
        videoEl.pause();
        document.getElementById('video-lightbox').classList.remove('vl-active');
        document.body.style.overflow = '';
        currentVideo    = null;
        currentVideoIdx = 0;
    }

    function videoModalPrev() {
        const total = currentVideo.videos.length;
        currentVideoIdx = (currentVideoIdx - 1 + total) % total;
        showVideoModal();
    }

    function videoModalNext() {
        const total = currentVideo.videos.length;
        currentVideoIdx = (currentVideoIdx + 1) % total;
        showVideoModal();
    }

    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('video-lightbox').classList.contains('vl-active')) return;
        if (e.key === 'Escape') closeVideoModal();
        if (e.key === 'ArrowLeft')  videoModalPrev();
        if (e.key === 'ArrowRight') videoModalNext();
    });

    // ===== FILTER =====
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const gridItems  = document.querySelectorAll('.gallery-grid-item');
    const emptyEl    = document.getElementById('videoEmpty');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            let visible  = 0;
            gridItems.forEach(item => {
                const match = filter === 'all' || item.dataset.kategori === filter;
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            emptyEl.style.display = visible === 0 ? 'flex' : 'none';
        });
    });

    // ===== SCROLL REVEAL =====
    const revealEls = document.querySelectorAll('.g-reveal');
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('g-visible'); revealObs.unobserve(e.target); }
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => revealObs.observe(el));
</script>
@endpush
