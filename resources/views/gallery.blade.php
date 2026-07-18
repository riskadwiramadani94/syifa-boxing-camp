@extends('layouts.app')

@section('title', 'Galeri - Syifa Boxing Camp')
@section('meta_description', 'Lihat galeri foto dan dokumentasi latihan, event, serta prestasi atlet Syifa Boxing Camp.')
@section('og_title', 'Galeri - Syifa Boxing Camp')
@section('og_description', 'Lihat galeri foto dan dokumentasi latihan, event, serta prestasi atlet Syifa Boxing Camp.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO ===== --}}
<section class="gallery-hero-section">
    <div class="container">
        <div class="gallery-hero-inner">
            <h1 class="gallery-hero-title g-reveal g-fadeup" style="--gd:0s">
                Galeri Syifa –
                <span class="gallery-hero-accent">Prestasi &<br>Momen Bersejarah.</span>
            </h1>
            <p class="gallery-hero-desc g-reveal g-fade" style="--gd:0.18s">
                Lihat momen terbaik dari latihan, event, dan pertandingan atlet Syifa Boxing Camp.
            </p>
            {{-- Filter langsung di hero seperti KONI --}}
            <div class="gallery-filter-wrap g-reveal g-fadeup" style="--gd:0.32s">
                <button class="gallery-filter-btn active" data-filter="all">Semua</button>
                <button class="gallery-filter-btn" data-filter="latihan">Latihan</button>
                <button class="gallery-filter-btn" data-filter="event">Event</button>
                <button class="gallery-filter-btn" data-filter="pertandingan">Pertandingan</button>
                <button class="gallery-filter-btn" data-filter="prestasi">Prestasi</button>
            </div>
        </div>
    </div>
</section>

{{-- ===== GRID GALERI ===== --}}
<section class="gallery-grid-section">
    <div class="container">

        <div class="gallery-grid" id="galleryGrid">
            @forelse($galeris as $i => $item)
            @php
                $fotos = is_array($item->foto) ? $item->foto : [];
                $fotoUrl = count($fotos) > 0
                    ? foto_url($fotos[0])
                    : asset('assets/logo/logo.jpg');
                $animTypes = ['g-fadeleft', 'g-fadeup', 'g-faderight'];
                $anim  = $animTypes[$i % 3];
                $delay = round(($i % 3) * 0.1, 2) . 's';
                $labelKategori = match($item->kategori) {
                    'latihan'      => 'Latihan',
                    'event'        => 'Event',
                    'pertandingan' => 'Pertandingan',
                    'prestasi'     => 'Prestasi',
                    default        => ucfirst($item->kategori),
                };
            @endphp
            <div class="gallery-grid-item g-reveal {{ $anim }}" data-kategori="{{ $item->kategori }}" data-juara="{{ $item->juara ? '1' : '0' }}" style="--gd:{{ $delay }}">
                <div class="gallery-grid-card" style="cursor:pointer;" onclick="openLightbox({{ $i }})">
                    <img src="{{ $fotoUrl }}"
                         alt="{{ $item->judul }}"
                         style="object-fit: cover; width:100%;">
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

        <div class="gallery-empty" id="galleryEmpty" style="{{ $galeris->isEmpty() ? 'display:flex' : 'display:none' }}">
            <i class="fas fa-images"></i>
            <p>Belum ada foto tersedia.</p>
        </div>

    </div>
</section>

{{-- ===== LIGHTBOX MODAL ===== --}}
<div id="lightbox" onclick="if(event.target===this) closeLightbox()">

    {{-- Tombol nav di LUAR card --}}
    <button class="lb-nav-outer lb-nav-outer-prev" id="lb-prev" onclick="lightboxPrev()">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="lb-modal">

        {{-- Area foto + thumbnail strip --}}
        <div class="lb-foto-wrap">
            {{-- blur background --}}
            <div class="lb-foto-bg" id="lb-foto-bg"></div>

            {{-- foto utama --}}
            <img id="lb-img" src="" alt="Galeri">

            {{-- counter overlay pojok kiri bawah --}}
            <span id="lb-counter" class="lb-counter-overlay"></span>
        </div>

        {{-- Thumbnail strip --}}
        <div class="lb-thumb-strip" id="lb-thumb-strip"></div>

        {{-- Info + tombol bawah card --}}
        <div class="lb-info">
            <div class="lb-info-left">
                <h5 id="lb-judul" class="lb-judul"></h5>
                <div class="lb-info-meta">
                    <span id="lb-tahun" class="lb-tahun"></span>
                    <span id="lb-juara" class="lb-juara-badge" style="display:none;"></span>
                </div>
            </div>
            <div class="lb-info-actions">
                <button class="lb-btn lb-btn-unduh" onclick="downloadFoto(event)">
                    <i class="fas fa-download"></i> Unduh
                </button>
                <button class="lb-btn lb-btn-batal" onclick="closeLightbox()">
                    Batal
                </button>
            </div>
        </div>

    </div>

    {{-- Tombol nav di LUAR card --}}
    <button class="lb-nav-outer lb-nav-outer-next" id="lb-next" onclick="lightboxNext()">
        <i class="fas fa-chevron-right"></i>
    </button>

</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/gallery.css') }}">
    <style>
        /* ===== LIGHTBOX MODAL ===== */
        #lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.82);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 20px 8px;
        }
        #lightbox.active { display: flex; }

        /* ===== TOMBOL NAV LUAR CARD ===== */
        .lb-nav-outer {
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
            z-index: 2;
        }
        .lb-nav-outer:hover { background: rgba(255,255,255,0.28); }

        /* ===== MODAL CARD ===== */
        .lb-modal {
            background: #2a2a2a;
            border-radius: 14px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 64px rgba(0,0,0,0.6);
        }

        /* ===== AREA FOTO ===== */
        .lb-foto-wrap {
            position: relative;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            max-height: 52vh;
            overflow: hidden;
        }
        .lb-foto-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: blur(24px) brightness(0.35);
            transform: scale(1.12);
            z-index: 0;
        }
        #lb-img {
            position: relative;
            z-index: 1;
            max-width: 100%;
            max-height: 52vh;
            object-fit: contain;
            display: block;
            border-radius: 2px;
        }

        /* counter overlay pojok kiri bawah foto */
        .lb-counter-overlay {
            position: absolute;
            bottom: 10px;
            left: 12px;
            z-index: 2;
            background: rgba(0,0,0,0.55);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            letter-spacing: 0.3px;
        }

        /* ===== THUMBNAIL STRIP ===== */
        .lb-thumb-strip {
            display: flex;
            gap: 6px;
            padding: 8px 12px;
            background: #222;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            min-height: 0;
        }
        .lb-thumb-strip::-webkit-scrollbar { display: none; }
        .lb-thumb-strip.hidden { display: none; }

        .lb-thumb {
            flex-shrink: 0;
            width: 52px;
            height: 40px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.18s, opacity 0.18s;
            opacity: 0.55;
        }
        .lb-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .lb-thumb.active {
            border-color: #fff;
            opacity: 1;
        }
        .lb-thumb:hover { opacity: 0.85; }

        /* ===== INFO + TOMBOL BAWAH ===== */
        .lb-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px 16px;
            background: #2a2a2a;
        }
        .lb-info-left { flex: 1; min-width: 0; }
        .lb-judul {
            font-size: 0.95rem;
            font-weight: 700;
            color: #f1f5f9;
            margin: 0 0 4px;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .lb-info-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .lb-tahun {
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .lb-juara-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .lb-juara-badge.medal-1 { background: #fef3c7; color: #92400e; }
        .lb-juara-badge.medal-2 { background: #e2e8f0; color: #334155; }
        .lb-juara-badge.medal-3 { background: #fde8d8; color: #7c3a1a; }
        .lb-juara-badge.medal-other { background: #f1f5f9; color: #475569; }

        .lb-info-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .lb-btn {
            border: none;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
        }
        .lb-btn-unduh {
            background: #3f3f3f;
            color: #f1f5f9;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .lb-btn-unduh:hover { background: #555; }
        .lb-btn-batal {
            background: #3f3f3f;
            color: #94a3b8;
        }
        .lb-btn-batal:hover { background: #555; color: #f1f5f9; }

        /* ===== MOBILE ===== */
        @media (max-width: 600px) {
            #lightbox {
                padding: 12px 4px;
                gap: 6px;
            }
            .lb-nav-outer {
                width: 36px;
                height: 36px;
                font-size: 0.85rem;
            }
            .lb-modal { border-radius: 12px; }
            .lb-foto-wrap { max-height: 44vh; min-height: 220px; }
            #lb-img { max-height: 44vh; }
            .lb-thumb { width: 44px; height: 34px; }
            .lb-judul { font-size: 0.88rem; }
            .lb-btn { padding: 6px 12px; font-size: 0.78rem; }
        }
    </style>
@endpush

@push('scripts')
<script>
    function downloadFoto(e) {
        e.preventDefault();
        const fotoSrc = document.getElementById('lb-img').src;
        const namaFile = fotoSrc.split('/').pop() || 'foto-galeri.jpg';
        fetch(fotoSrc)
            .then(res => res.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = namaFile;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            })
            .catch(() => window.open(fotoSrc, '_blank'));
    }

    // ===== DATA GALERI =====
    const galeriData = @json($galeriData);

    let currentGaleri = null;
    let currentFotoIdx = 0;

    function openLightbox(galeriIndex) {
        currentGaleri  = galeriData[galeriIndex];
        currentFotoIdx = 0;
        renderThumbnails();
        showLightbox();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function renderThumbnails() {
        const strip = document.getElementById('lb-thumb-strip');
        const fotos = currentGaleri.fotos;
        strip.innerHTML = '';

        if (fotos.length <= 1) {
            strip.classList.add('hidden');
            return;
        }
        strip.classList.remove('hidden');

        fotos.forEach((src, i) => {
            const div = document.createElement('div');
            div.className = 'lb-thumb' + (i === currentFotoIdx ? ' active' : '');
            div.onclick = () => { currentFotoIdx = i; showLightbox(); };
            const img = document.createElement('img');
            img.src = src;
            img.alt = '';
            div.appendChild(img);
            strip.appendChild(div);
        });
    }

    function updateThumbnailActive() {
        const thumbs = document.querySelectorAll('.lb-thumb');
        thumbs.forEach((t, i) => {
            t.classList.toggle('active', i === currentFotoIdx);
        });
        // scroll thumbnail aktif ke tengah strip
        const strip = document.getElementById('lb-thumb-strip');
        const active = strip.querySelector('.lb-thumb.active');
        if (active) {
            const stripLeft   = strip.getBoundingClientRect().left;
            const activeLeft  = active.getBoundingClientRect().left;
            const activeWidth = active.offsetWidth;
            const stripWidth  = strip.offsetWidth;
            strip.scrollLeft += (activeLeft - stripLeft) - (stripWidth / 2) + (activeWidth / 2);
        }
    }

    function showLightbox() {
        const fotos   = currentGaleri.fotos;
        const juara   = currentGaleri.juara;
        const judul   = currentGaleri.judul;
        const tahun   = currentGaleri.tahun;
        const total   = fotos.length;
        const fotoSrc = fotos[currentFotoIdx];

        // foto + blur bg
        document.getElementById('lb-img').src = fotoSrc;
        document.getElementById('lb-foto-bg').style.backgroundImage = `url('${fotoSrc}')`;

        // counter overlay
        const counter = document.getElementById('lb-counter');
        if (total > 1) {
            counter.textContent = (currentFotoIdx + 1) + ' dari ' + total + ' foto';
            counter.style.display = 'inline-block';
        } else {
            counter.style.display = 'none';
        }

        // tombol nav luar
        const showNav = total > 1;
        document.getElementById('lb-prev').style.visibility = showNav ? 'visible' : 'hidden';
        document.getElementById('lb-next').style.visibility = showNav ? 'visible' : 'hidden';

        // update thumbnail active
        updateThumbnailActive();

        // info
        document.getElementById('lb-judul').textContent = judul;
        document.getElementById('lb-tahun').textContent = tahun;

        // badge juara
        const juaraEl = document.getElementById('lb-juara');
        if (juara) {
            const medals = { 1: ['🥇', 'medal-1'], 2: ['🥈', 'medal-2'], 3: ['🥉', 'medal-3'] };
            const [icon, cls] = medals[juara] || ['🏅', 'medal-other'];
            juaraEl.textContent = icon + ' Juara ' + juara;
            juaraEl.className = 'lb-juara-badge ' + cls;
            juaraEl.style.display = 'inline-block';
        } else {
            juaraEl.style.display = 'none';
        }
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
        currentGaleri  = null;
        currentFotoIdx = 0;
    }

    function lightboxPrev() {
        const total = currentGaleri.fotos.length;
        currentFotoIdx = (currentFotoIdx - 1 + total) % total;
        showLightbox();
    }

    function lightboxNext() {
        const total = currentGaleri.fotos.length;
        currentFotoIdx = (currentFotoIdx + 1) % total;
        showLightbox();
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  lightboxPrev();
        if (e.key === 'ArrowRight') lightboxNext();
        if (e.key === 'Escape')     closeLightbox();
    });

    // Touch swipe
    let touchStartX = 0;
    document.getElementById('lightbox').addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    document.getElementById('lightbox').addEventListener('touchend', function(e) {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) lightboxNext();
            else          lightboxPrev();
        }
    }, { passive: true });

    // ===== FILTER =====
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const gridItems  = document.querySelectorAll('.gallery-grid-item');
    const emptyEl    = document.getElementById('galleryEmpty');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            let visible = 0;
            gridItems.forEach((item) => {
                let match = false;
                if (filter === 'all') {
                    match = true;
                } else if (filter === 'prestasi') {
                    match = item.dataset.kategori === 'pertandingan' && item.dataset.juara === '1';
                } else {
                    match = item.dataset.kategori === filter;
                }
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            emptyEl.style.display = visible === 0 ? 'flex' : 'none';
        });
    });

    // ===== SCROLL REVEAL =====
    const revealEls = document.querySelectorAll('.g-reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('g-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => revealObserver.observe(el));
</script>
@endpush
