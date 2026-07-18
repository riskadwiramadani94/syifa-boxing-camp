@extends('layouts.app')

@section('title', $galeri->judul . ' - Galeri Syifa Boxing Camp')
@section('meta_description', $galeri->keterangan ?: 'Lihat galeri foto dan dokumentasi ' . $galeri->judul . ' dari Syifa Boxing Camp.')
@section('og_title', $galeri->judul . ' - Galeri Syifa Boxing Camp')
@section('og_description', $galeri->keterangan ?: 'Lihat galeri foto dan dokumentasi ' . $galeri->judul . ' dari Syifa Boxing Camp.')
@section('og_image', $fotos[0] ?? asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HEADER ===== --}}
<section class="gd-header-section">
    <div class="container">
        {{-- Breadcrumb Nav & Back Button --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 12px;">
            <nav style="display: flex; gap: 8px; align-items: center; font-size: 0.9rem; font-weight: 600;">
                <a href="{{ route('gallery') }}" style="color: #d63384; text-decoration: none;">Galeri</a>
                <span style="color: #94a3b8;">/</span>
                <span style="color: #475569;">{{ \Illuminate\Support\Str::limit($galeri->judul, 40) }}</span>
            </nav>
            <a href="{{ route('gallery') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1px solid #cbd5e1; border-radius: 6px; color: #475569; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; background: #fff;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#fff'; this.style.borderColor='#cbd5e1';">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="gd-header-inner" style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
            <div class="gd-header-meta" style="justify-content: center;">
                <span class="gd-badge-kategori">
                    {{ match($galeri->kategori) {
                        'latihan'      => 'Latihan',
                        'event'        => 'Event',
                        'pertandingan' => 'Pertandingan',
                        default        => ucfirst($galeri->kategori)
                    } }}
                </span>
                <span class="gd-badge-tahun">{{ $galeri->tahun }}</span>
                @if($galeri->juara_umum)
                    <span class="gd-badge-special gold">
                        <i class="fas fa-trophy"></i> Juara Umum
                    </span>
                @endif
                @if($galeri->petinju_terbaik)
                    <span class="gd-badge-special silver">
                        <i class="fas fa-star"></i> Petinju Terbaik
                    </span>
                @endif
            </div>
            <h1 class="gd-header-title">{{ $galeri->judul }}</h1>
            @if($galeri->event)
                <p class="gd-header-event" style="justify-content: center;">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $galeri->event->judul }}
                    @if($galeri->event->tanggal_mulai)
                        &nbsp;·&nbsp; {{ $galeri->event->tanggal_mulai->format('d M Y') }}
                    @endif
                    @if($galeri->event->lokasi ?? $galeri->event->tempat ?? null)
                        &nbsp;·&nbsp; <i class="fas fa-map-marker-alt"></i>
                        {{ $galeri->event->lokasi ?? $galeri->event->tempat }}
                    @endif
                </p>
            @endif
        </div>
    </div>
</section>


{{-- ===== GRID FOTO ===== --}}
@if(count($fotos) > 0)
<section class="gd-foto-section">
    <div class="container">
        <h2 class="gd-section-title">
            <i class="fas fa-images"></i> Foto
            <span class="gd-section-count">{{ count($fotos) }}</span>
        </h2>
        <div class="gd-foto-grid" id="gdFotoGrid">
            @foreach($fotos as $i => $url)
            <div class="gd-foto-item" onclick="openFotoLightbox({{ $i }})">
                <img src="{{ $url }}" alt="{{ $galeri->judul }} foto {{ $i + 1 }}"
                     loading="lazy"
                     onerror="this.src='{{ asset('assets/logo/logo.jpg') }}'">
                <div class="gd-foto-overlay">
                    <i class="fas fa-expand-alt"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== SECTION VIDEO ===== --}}
@if(count($videos) > 0)
<section class="gd-video-section">
    <div class="container">
        <h2 class="gd-section-title">
            <i class="fas fa-film"></i> Video
            <span class="gd-section-count">{{ count($videos) }}</span>
        </h2>
        <div class="gd-video-grid">
            @foreach($videos as $v)
            <div class="gd-video-item">
                <video controls preload="none"
                       poster="{{ $v['thumb'] }}"
                       onerror="this.parentElement.classList.add('gd-video-error')">
                    <source src="{{ $v['url'] }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
                <p class="gd-video-name">{{ $v['nama'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ===== DAFTAR ATLET JUARA ===== --}}
@php
    $daftarJuara = is_array($galeri->daftar_juara) ? $galeri->daftar_juara : [];
    // Hitung medali gabungan (dari koma + dari repeater)
    $totalEmas     = $medaliEmas;
    $totalPerak    = $medaliPerak;
    $totalPerunggu = $medaliPerunggu;
    foreach ($daftarJuara as $atlet) {
        $juaraKe = intval($atlet['juara_ke'] ?? 0);
        if ($juaraKe === 1) $totalEmas++;
        elseif ($juaraKe === 2) $totalPerak++;
        elseif ($juaraKe === 3) $totalPerunggu++;
    }
@endphp

@if(count($daftarJuara) > 0)
<section class="gd-atlet-section">
    <div class="container">
        <h2 class="gd-section-title">
            <i class="fas fa-user-shield"></i> Atlet Pencetak Prestasi
            <span class="gd-section-count">{{ count($daftarJuara) }}</span>
        </h2>

        <div class="gd-atlet-grid">
            @foreach($daftarJuara as $atlet)
            @php
                $juaraKe  = intval($atlet['juara_ke'] ?? 0);
                $fotoAtlet = $atlet['foto_atlet'] ?? null;
                $fotoUrl   = $fotoAtlet ? foto_url($fotoAtlet) : asset('assets/logo/logo.jpg');
                $medalColor = match($juaraKe) {
                    1 => ['bg' => 'linear-gradient(135deg,#b8860b,#ffd700)', 'label' => '🥇 Juara 1', 'badge' => '#ffd700'],
                    2 => ['bg' => 'linear-gradient(135deg,#6b7280,#c0c0c0)', 'label' => '🥈 Juara 2', 'badge' => '#c0c0c0'],
                    3 => ['bg' => 'linear-gradient(135deg,#92400e,#cd7f32)', 'label' => '🥉 Juara 3', 'badge' => '#cd7f32'],
                    default => ['bg' => 'linear-gradient(135deg,#be123c,#f43f5e)', 'label' => '🏅 Juara', 'badge' => '#f43f5e'],
                };
            @endphp
            <div class="gd-atlet-card">
                <div class="gd-atlet-foto-wrap">
                    <img src="{{ $fotoUrl }}" alt="{{ $atlet['nama'] ?? 'Atlet' }}"
                         onerror="this.src='{{ asset('assets/logo/logo.jpg') }}'">
                    <div class="gd-atlet-medal-badge" style="background: {{ $medalColor['badge'] }};">
                        {{ $medalColor['label'] }}
                    </div>
                </div>
                <div class="gd-atlet-info" style="background: {{ $medalColor['bg'] }};">
                    <h4 class="gd-atlet-nama">{{ $atlet['nama'] ?? '-' }}</h4>
                    @if(!empty($atlet['kelas']))
                    <p class="gd-atlet-kelas">{{ $atlet['kelas'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ===== KETERANGAN & PRESTASI ===== --}}
@if($galeri->keterangan || $totalEmas > 0 || $totalPerak > 0 || $totalPerunggu > 0 || $galeri->juara_umum || $galeri->petinju_terbaik)
<section class="gd-info-section">
    <div class="container">

        @if($galeri->keterangan)
        <div class="gd-keterangan">
            <h2 class="gd-section-title"><i class="fas fa-align-left"></i> Keterangan</h2>
            <p class="gd-keterangan-text">{{ $galeri->keterangan }}</p>
        </div>
        @endif

        @if($totalEmas > 0 || $totalPerak > 0 || $totalPerunggu > 0 || $galeri->juara_umum || $galeri->petinju_terbaik)
        <div class="gd-prestasi">
            <h2 class="gd-section-title"><i class="fas fa-medal"></i> Total Perolehan Medali</h2>
            <div class="gd-medali-grid">

                @if($totalEmas > 0)
                <div class="gd-medali-card gd-medali-emas">
                    <div class="gd-medali-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="gd-medali-info">
                        <span class="gd-medali-label">Medali Emas</span>
                        <span class="gd-medali-count">{{ $totalEmas }}</span>
                    </div>
                </div>
                @endif

                @if($totalPerak > 0)
                <div class="gd-medali-card gd-medali-perak">
                    <div class="gd-medali-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="gd-medali-info">
                        <span class="gd-medali-label">Medali Perak</span>
                        <span class="gd-medali-count">{{ $totalPerak }}</span>
                    </div>
                </div>
                @endif

                @if($totalPerunggu > 0)
                <div class="gd-medali-card gd-medali-perunggu">
                    <div class="gd-medali-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="gd-medali-info">
                        <span class="gd-medali-label">Medali Perunggu</span>
                        <span class="gd-medali-count">{{ $totalPerunggu }}</span>
                    </div>
                </div>
                @endif

                @if($galeri->juara_umum)
                <div class="gd-medali-card gd-medali-special">
                    <div class="gd-medali-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="gd-medali-info">
                        <span class="gd-medali-label">Juara Umum</span>
                    </div>
                </div>
                @endif

                @if($galeri->petinju_terbaik)
                <div class="gd-medali-card gd-medali-special">
                    <div class="gd-medali-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="gd-medali-info">
                        <span class="gd-medali-label">Petinju Terbaik</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif

    </div>
</section>
@endif

{{-- ===== LIGHTBOX ===== --}}
<div id="gdLightbox" onclick="if(event.target===this) closeGdLightbox()">
    <button class="gd-lb-nav" id="gdLbPrev" onclick="gdLbPrev()">
        <i class="fas fa-chevron-left"></i>
    </button>
    <div class="gd-lb-main">
        <button class="gd-lb-close" onclick="closeGdLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <img id="gdLbImg" src="" alt="Foto galeri">
        <span class="gd-lb-counter" id="gdLbCounter"></span>
    </div>
    <button class="gd-lb-nav" id="gdLbNext" onclick="gdLbNext()">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>

@endsection


@push('styles')
<style>
/* ===== HEADER ===== */
.gd-header-section {
    background: #ffffff;
    padding: 48px 0 36px;
    border-bottom: 1px solid #f1f5f9;
}
.gd-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 20px;
    transition: color 0.2s;
    letter-spacing: 0.3px;
}
.gd-back-link:hover { color: #1a2a4a; }
.gd-back-link i { font-size: 0.78rem; }
.gd-header-inner { padding-top: 4px; }
.gd-header-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
    align-items: center;
}
.gd-badge-kategori, .gd-badge-tahun {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.gd-badge-kategori { background: #fff1f2; color: #cc2929; border: 1px solid #fecdd3; }
.gd-badge-tahun { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
.gd-badge-special {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    letter-spacing: 0.3px;
}
.gd-badge-special.gold  { background: #fefce8; color: #a16207; border: 1px solid #fde68a; }
.gd-badge-special.silver{ background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
.gd-header-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    color: #1a2a4a;
    letter-spacing: 1px;
    margin: 0 0 10px;
    line-height: 1.15;
}
.gd-header-event {
    color: #64748b;
    font-size: 0.85rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.gd-header-event i { color: #cc2929; }

/* ===== SECTION TITLES ===== */
.gd-foto-section, .gd-video-section, .gd-info-section {
    padding: 48px 0;
    background: #ffffff;
}
.gd-foto-section { background: #ffffff; border-top: 1px solid #f1f5f9; }
.gd-video-section { background: #f8fafc; border-top: 1px solid #f1f5f9; }
.gd-info-section { background: #ffffff; border-top: 1px solid #f1f5f9; }
.gd-section-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.gd-section-title i { color: #cc2929; font-size: 0.85rem; }
.gd-section-count {
    background: #fff1f2;
    color: #cc2929;
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 700;
}

/* ===== GRID FOTO ===== */
.gd-foto-grid {
    columns: 4;
    column-gap: 10px;
}
@media (max-width: 992px) { .gd-foto-grid { columns: 3; } }
@media (max-width: 640px) { .gd-foto-grid { columns: 2; } }
.gd-foto-item {
    break-inside: avoid;
    margin-bottom: 10px;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    background: #f1f5f9;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.gd-foto-item img {
    width: 100%;
    display: block;
    border-radius: 10px;
    transition: transform 0.3s ease;
}
.gd-foto-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.25s;
    border-radius: 10px;
}
.gd-foto-overlay i {
    color: #fff;
    font-size: 1.4rem;
    opacity: 0;
    transition: opacity 0.25s, transform 0.25s;
    transform: scale(0.8);
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
}
.gd-foto-item:hover img { transform: scale(1.04); }
.gd-foto-item:hover .gd-foto-overlay { background: rgba(0,0,0,0.38); }
.gd-foto-item:hover .gd-foto-overlay i { opacity: 1; transform: scale(1); }

/* ===== GRID VIDEO ===== */
.gd-video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
}
.gd-video-item {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.gd-video-item video {
    width: 100%;
    display: block;
    max-height: 220px;
    object-fit: cover;
    background: #000;
}
.gd-video-name {
    padding: 10px 14px;
    font-size: 0.78rem;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ===== KETERANGAN ===== */
.gd-keterangan { margin-bottom: 40px; }
.gd-keterangan-text {
    color: #475569;
    font-size: 0.95rem;
    line-height: 1.75;
    max-width: 720px;
}

/* ===== MEDALI CARDS ===== */
.gd-medali-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.gd-medali-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 24px;
    border-radius: 14px;
    min-width: 180px;
    border: 1px solid transparent;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}
.gd-medali-icon { font-size: 1.8rem; flex-shrink: 0; }
.gd-medali-info { display: flex; flex-direction: column; gap: 2px; }
.gd-medali-label { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; opacity: 0.75; }
.gd-medali-count { font-family: 'Bebas Neue', sans-serif; font-size: 2rem; line-height: 1; letter-spacing: 1px; }

.gd-medali-emas {
    background: #fefce8;
    border-color: #fde68a;
}
.gd-medali-emas .gd-medali-icon, .gd-medali-emas .gd-medali-count { color: #a16207; }
.gd-medali-emas .gd-medali-label { color: #a16207; }

.gd-medali-perak {
    background: #f8fafc;
    border-color: #e2e8f0;
}
.gd-medali-perak .gd-medali-icon, .gd-medali-perak .gd-medali-count { color: #475569; }
.gd-medali-perak .gd-medali-label { color: #475569; }

.gd-medali-perunggu {
    background: #fff7ed;
    border-color: #fed7aa;
}
.gd-medali-perunggu .gd-medali-icon, .gd-medali-perunggu .gd-medali-count { color: #c2410c; }
.gd-medali-perunggu .gd-medali-label { color: #c2410c; }

.gd-medali-special {
    background: #fff1f2;
    border-color: #fecdd3;
}
.gd-medali-special .gd-medali-icon { color: #cc2929; }
.gd-medali-special .gd-medali-label { color: #cc2929; }

/* ===== LIGHTBOX FOTO ===== */
#gdLightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 20px 12px;
}
#gdLightbox.active { display: flex; }
.gd-lb-nav {
    flex-shrink: 0;
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: background 0.2s;
}
.gd-lb-nav:hover { background: rgba(255,255,255,0.3); }
.gd-lb-main {
    position: relative;
    max-width: min(90vw, 900px);
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}
#gdLbImg {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
    display: block;
    box-shadow: 0 24px 64px rgba(0,0,0,0.6);
}
.gd-lb-close {
    position: absolute;
    top: -14px;
    right: -14px;
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.gd-lb-close:hover { background: #cc2929; }
.gd-lb-counter {
    margin-top: 12px;
    font-size: 0.78rem;
    color: #94a3b8;
    letter-spacing: 0.3px;
}
@media (max-width: 600px) {
    #gdLightbox { gap: 8px; padding: 12px 6px; }
    .gd-lb-nav { width: 38px; height: 38px; }
    .gd-medali-card { min-width: 150px; padding: 14px 18px; }
    .gd-medali-count { font-size: 1.6rem; }
}

/* ===== ATLET JUARA ===== */
.gd-atlet-section {
    padding: 48px 0 32px;
}
.gd-atlet-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 24px;
    margin-top: 24px;
}
.gd-atlet-card {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gd-atlet-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.2);
}
.gd-atlet-foto-wrap {
    position: relative;
    aspect-ratio: 3/4;
    overflow: hidden;
    background: #1a1a2e;
}
.gd-atlet-foto-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.gd-atlet-card:hover .gd-atlet-foto-wrap img {
    transform: scale(1.05);
}
.gd-atlet-medal-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    color: #1a1a1a;
    font-weight: 800;
    font-size: 0.72rem;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.gd-atlet-info {
    padding: 14px 16px;
    color: #fff;
}
.gd-atlet-nama {
    font-size: 1rem;
    font-weight: 800;
    margin: 0 0 4px;
    color: #fff;
    line-height: 1.3;
}
.gd-atlet-kelas {
    font-size: 0.78rem;
    margin: 0;
    opacity: 0.88;
    font-weight: 500;
    color: rgba(255,255,255,0.9);
}
@media (max-width: 600px) {
    .gd-atlet-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
</style>
@endpush


@push('scripts')
<script>
const gdFotos = @json($fotos);
let gdCurrentIdx = 0;

function openFotoLightbox(idx) {
    gdCurrentIdx = idx;
    renderGdLightbox();
    document.getElementById('gdLightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeGdLightbox() {
    document.getElementById('gdLightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function gdLbPrev() {
    gdCurrentIdx = (gdCurrentIdx - 1 + gdFotos.length) % gdFotos.length;
    renderGdLightbox();
}

function gdLbNext() {
    gdCurrentIdx = (gdCurrentIdx + 1) % gdFotos.length;
    renderGdLightbox();
}

function renderGdLightbox() {
    document.getElementById('gdLbImg').src = gdFotos[gdCurrentIdx];
    const showNav = gdFotos.length > 1;
    document.getElementById('gdLbPrev').style.visibility = showNav ? 'visible' : 'hidden';
    document.getElementById('gdLbNext').style.visibility = showNav ? 'visible' : 'hidden';
    document.getElementById('gdLbCounter').textContent =
        gdFotos.length > 1 ? (gdCurrentIdx + 1) + ' / ' + gdFotos.length : '';
}

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('gdLightbox').classList.contains('active')) return;
    if (e.key === 'ArrowLeft')  gdLbPrev();
    if (e.key === 'ArrowRight') gdLbNext();
    if (e.key === 'Escape')     closeGdLightbox();
});

let gdTouchX = 0;
document.getElementById('gdLightbox').addEventListener('touchstart', function(e) {
    gdTouchX = e.changedTouches[0].screenX;
}, { passive: true });
document.getElementById('gdLightbox').addEventListener('touchend', function(e) {
    const diff = gdTouchX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 50) { diff > 0 ? gdLbNext() : gdLbPrev(); }
}, { passive: true });

// Scroll reveal
const gdReveal = document.querySelectorAll('.gd-foto-item, .gd-video-item, .gd-medali-card');
const gdObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
            gdObs.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });
gdReveal.forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.4s ease ' + (i * 0.04) + 's, transform 0.4s ease ' + (i * 0.04) + 's';
    gdObs.observe(el);
});
</script>
@endpush
