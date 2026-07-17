@extends('layouts.app')

@section('title', 'Galeri - Syifa Boxing Camp')

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
                    ? asset('storage/' . $fotos[0])
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
    <div class="lb-modal">

        {{-- Header modal --}}
        <div class="lb-header">
            <span id="lb-counter" class="lb-counter"></span>
            <div class="lb-header-actions">
                <a id="lb-download" href="#" class="lb-btn-download" title="Unduh foto" onclick="downloadFoto(event)">
                    <i class="fas fa-download"></i> Unduh
                </a>
                <button class="lb-close" onclick="closeLightbox()">&times;</button>
            </div>
        </div>

        {{-- Area foto --}}
        <div class="lb-foto-wrap">
            <div class="lb-foto-bg" id="lb-foto-bg"></div>
            <button class="lb-nav lb-nav-prev" id="lb-prev" onclick="lightboxPrev()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <img id="lb-img" src="" alt="Galeri">
            <button class="lb-nav lb-nav-next" id="lb-next" onclick="lightboxNext()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- Card info bawah --}}
        <div class="lb-info">
            <div class="lb-info-top">
                <div>
                    <h5 id="lb-judul" class="lb-judul"></h5>
                    <span id="lb-tahun" class="lb-tahun"></span>
                </div>
                <span id="lb-juara" class="lb-juara-badge" style="display:none;"></span>
            </div>
            <p id="lb-keterangan" class="lb-keterangan" style="display:none;"></p>
        </div>

    </div>
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
            background: rgba(0,0,0,0.75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        #lightbox.active { display: flex; }

        .lb-modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            max-height: 92vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        /* header */
        .lb-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        .lb-counter {
            font-size: 0.82rem;
            color: #888;
            font-weight: 500;
        }
        .lb-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .lb-btn-download {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            border: none;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .lb-btn-download:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
        .lb-close {
            background: #f1f1f1;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            transition: background 0.2s;
        }
        .lb-close:hover { background: #e0e0e0; }

        /* area foto */
        .lb-foto-wrap {
            position: relative;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 55vh;
            overflow: hidden;
        }
        /* blur background dari foto yang sama */
        .lb-foto-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: blur(20px) brightness(0.45);
            transform: scale(1.1);
            z-index: 0;
        }
        #lb-img {
            position: relative;
            z-index: 1;
            max-width: 100%;
            max-height: 55vh;
            object-fit: contain;
            display: block;
        }
        .lb-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.35);
            border: none;
            color: #fff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: background 0.2s;
            z-index: 2;
        }
        .lb-nav:hover { background: rgba(0,0,0,0.6); }
        .lb-nav-prev { left: 10px; }
        .lb-nav-next { right: 10px; }

        /* card info bawah */
        .lb-info {
            padding: 16px 20px 20px;
            border-top: 1px solid #f0f0f0;
            background: #fff;
            overflow-y: auto;
            max-height: 180px;
        }
        .lb-info-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }
        .lb-judul {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px;
            line-height: 1.4;
        }
        .lb-tahun {
            font-size: 0.78rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .lb-juara-badge {
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .lb-juara-badge.medal-1 {
            background: #fef3c7;
            color: #92400e;
        }
        .lb-juara-badge.medal-2 {
            background: #e2e8f0;
            color: #334155;
        }
        .lb-juara-badge.medal-3 {
            background: #fde8d8;
            color: #7c3a1a;
        }
        .lb-juara-badge.medal-other {
            background: #f1f5f9;
            color: #475569;
        }
        .lb-keterangan {
            font-size: 0.85rem;
            color: #64748b;
            margin: 10px 0 0;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .lb-modal { max-width: 100%; border-radius: 12px; }
            #lb-img { max-height: 40vh; }
        }
    </style>
@endpush

@push('scripts')
<script>
    function downloadFoto(e) {
        e.preventDefault();
        const fotoSrc = document.getElementById('lb-img').src;
        const namaFile = fotoSrc.split('/').pop() || 'foto-galeri.jpg';

        // fetch as blob lalu force download
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
            .catch(() => {
                // fallback: buka di tab baru kalau fetch gagal
                window.open(fotoSrc, '_blank');
            });
    }

    // ===== DATA GALERI =====
    const galeriData = @json($galeriData);

    // State lightbox
    let currentGaleri = null;
    let currentFotoIdx = 0;

    function openLightbox(galeriIndex) {
        currentGaleri  = galeriData[galeriIndex];
        currentFotoIdx = 0;
        showLightbox();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function showLightbox() {
        const fotos      = currentGaleri.fotos;
        const juara      = currentGaleri.juara;
        const judul      = currentGaleri.judul;
        const tahun      = currentGaleri.tahun;
        const total      = fotos.length;
        const fotoSrc    = fotos[currentFotoIdx];

        // foto + blur background
        document.getElementById('lb-img').src = fotoSrc;
        document.getElementById('lb-foto-bg').style.backgroundImage = `url('${fotoSrc}')`;

        // tombol unduh — href tidak dipakai, foto diambil dari lb-img.src saat klik
        document.getElementById('lb-download').dataset.src = fotoSrc;

        // counter
        document.getElementById('lb-counter').textContent =
            total > 1 ? (currentFotoIdx + 1) + ' / ' + total + ' foto' : '';

        // nav tombol
        const showNav = total > 1;
        document.getElementById('lb-prev').style.display = showNav ? 'flex' : 'none';
        document.getElementById('lb-next').style.display = showNav ? 'flex' : 'none';

        // info card
        document.getElementById('lb-judul').textContent = judul;
        document.getElementById('lb-tahun').textContent = tahun;

        // badge juara — warna medali
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

        // keterangan — hidden (field dihapus)
        document.getElementById('lb-keterangan').style.display = 'none';
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
            visibleIndices = [];

            gridItems.forEach((item, i) => {
                let match = false;
                if (filter === 'all') {
                    match = true;
                } else if (filter === 'prestasi') {
                    // Prestasi = pertandingan yang punya juara
                    match = item.dataset.kategori === 'pertandingan' && item.dataset.juara === '1';
                } else {
                    match = item.dataset.kategori === filter;
                }
                item.style.display = match ? '' : 'none';
                if (match) {
                    visibleIndices.push(i);
                    visible++;
                }
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
