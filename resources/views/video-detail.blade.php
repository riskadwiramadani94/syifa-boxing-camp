@extends('layouts.app')

@section('title', $galeri->judul . ' - Video Syifa Boxing Camp')
@section('meta_description', $galeri->keterangan ?: 'Tonton video dokumentasi ' . $galeri->judul . ' dari Syifa Boxing Camp.')
@section('og_title', $galeri->judul . ' - Video Syifa Boxing Camp')
@section('og_description', $galeri->keterangan ?: 'Tonton video dokumentasi ' . $galeri->judul . ' dari Syifa Boxing Camp.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HEADER ===== --}}
<section class="gd-header-section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 12px;">
            <nav style="display: flex; gap: 8px; align-items: center; font-size: 0.9rem; font-weight: 600;">
                <a href="{{ route('video') }}" style="color: #d63384; text-decoration: none;">Video</a>
                <span style="color: #94a3b8;">/</span>
                <span style="color: #475569;">{{ \Illuminate\Support\Str::limit($galeri->judul, 40) }}</span>
            </nav>
            <a href="{{ route('video') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1px solid #cbd5e1; border-radius: 6px; color: #475569; font-size: 0.85rem; font-weight: 600; text-decoration: none; background: #fff;" onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
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
                <span class="gd-badge-tahun" style="background:#f0fdf4; color:#15803d; border-color:#bbf7d0;">
                    <i class="fas fa-film"></i> {{ count($videos) }} Video
                </span>
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
                </p>
            @endif
        </div>
    </div>
</section>

{{-- ===== KETERANGAN ===== --}}
@if($galeri->keterangan)
<section style="padding: 32px 0 0;">
    <div class="container">
        <p style="color: #475569; font-size: 0.97rem; line-height: 1.8; margin: 0;">{{ $galeri->keterangan }}</p>
    </div>
</section>
@endif

{{-- ===== GRID VIDEO ===== --}}
<section class="gd-video-section" style="padding: 40px 0;">
    <div class="container">
        <h2 class="gd-section-title">
            <i class="fas fa-film"></i> Video
            <span class="gd-section-count">{{ count($videos) }}</span>
        </h2>

        @if(count($videos) > 0)
        <div class="vd-grid">
            @foreach($videos as $i => $v)
            <div class="vd-item">
                @if(isset($v['type']) && $v['type'] === 'youtube' && $v['yt_id'])
                    {{-- YouTube embed --}}
                    <div class="vd-embed-wrap">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $v['yt_id'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"
                        ></iframe>
                    </div>
                @elseif(isset($v['type']) && $v['type'] === 'youtube')
                    {{-- Link YT tanpa ID --}}
                    <div class="vd-embed-wrap" style="background:#111; display:flex; align-items:center; justify-content:center;">
                        <a href="{{ $v['url'] }}" target="_blank" style="color:#fff; text-align:center; padding:20px;">
                            <i class="fas fa-external-link-alt" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                            Buka di YouTube
                        </a>
                    </div>
                @else
                    {{-- File video --}}
                    <div class="vd-video-wrap">
                        <video controls preload="none"
                               @if($v['thumb']) poster="{{ $v['thumb'] }}" @endif>
                            <source src="{{ $v['url'] }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                @endif
                <p class="vd-nama">
                    <i class="fas fa-play-circle" style="color:#cc2929; margin-right:5px;"></i>
                    Video {{ $i + 1 }}
                </p>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center; padding: 60px 0; color: #94a3b8;">
            <i class="fas fa-video-slash" style="font-size:2.5rem; margin-bottom:12px; display:block;"></i>
            Belum ada video tersedia.
        </div>
        @endif
    </div>
</section>

{{-- ===== PRESTASI / MEDALI ===== --}}
@php
    $daftarJuara    = is_array($galeri->daftar_juara) ? $galeri->daftar_juara : [];
    // Total medali HANYA dari field juara (rekap koma) — repeater hanya detail nama
    $medaliEmas     = 0;
    $medaliPerak    = 0;
    $medaliPerunggu = 0;

    if ($galeri->juara) {
        foreach ($galeri->juaraArray() as $angka) {
            if ($angka === 1)     $medaliEmas++;
            elseif ($angka === 2) $medaliPerak++;
            elseif ($angka === 3) $medaliPerunggu++;
        }
    }
    $adaPrestasi = $medaliEmas > 0 || $medaliPerak > 0 || $medaliPerunggu > 0
                   || $galeri->juara_umum || $galeri->petinju_terbaik;
@endphp

@if($adaPrestasi)
<section style="padding: 48px 0; background: #ffffff; border-top: 1px solid #f1f5f9;">
    <div class="container">
        <h2 class="gd-section-title"><i class="fas fa-medal"></i> Total Perolehan Medali</h2>
        <div class="gd-medali-grid">

            @if($medaliEmas > 0)
            <div class="gd-medali-card gd-medali-emas">
                <div class="gd-medali-icon"><i class="fas fa-medal"></i></div>
                <div class="gd-medali-info">
                    <span class="gd-medali-label">Medali Emas</span>
                    <span class="gd-medali-count">{{ $medaliEmas }}</span>
                </div>
            </div>
            @endif

            @if($medaliPerak > 0)
            <div class="gd-medali-card gd-medali-perak">
                <div class="gd-medali-icon"><i class="fas fa-medal"></i></div>
                <div class="gd-medali-info">
                    <span class="gd-medali-label">Medali Perak</span>
                    <span class="gd-medali-count">{{ $medaliPerak }}</span>
                </div>
            </div>
            @endif

            @if($medaliPerunggu > 0)
            <div class="gd-medali-card gd-medali-perunggu">
                <div class="gd-medali-icon"><i class="fas fa-medal"></i></div>
                <div class="gd-medali-info">
                    <span class="gd-medali-label">Medali Perunggu</span>
                    <span class="gd-medali-count">{{ $medaliPerunggu }}</span>
                </div>
            </div>
            @endif

            @if($galeri->juara_umum)
            <div class="gd-medali-card gd-medali-special">
                <div class="gd-medali-icon"><i class="fas fa-trophy"></i></div>
                <div class="gd-medali-info">
                    <span class="gd-medali-label">Juara Umum</span>
                </div>
            </div>
            @endif

            @if($galeri->petinju_terbaik)
            <div class="gd-medali-card gd-medali-special">
                <div class="gd-medali-icon"><i class="fas fa-star"></i></div>
                <div class="gd-medali-info">
                    <span class="gd-medali-label">Petinju Terbaik</span>
                </div>
            </div>
            @endif

        </div>

        {{-- Daftar atlet --}}
        @if(count($daftarJuara) > 0)
        <div style="margin-top: 32px;">
            <h3 class="gd-section-title">
                <i class="fas fa-users"></i> Perolehan Medali
                <span class="gd-section-count">{{ count($daftarJuara) }} atlet</span>
            </h3>
            <div class="gd-atlet-list-grid">
                @foreach($daftarJuara as $atlet)
                @php
                    $juaraKe = intval($atlet['juara_ke'] ?? 0);
                    $icon  = match($juaraKe) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => '🏅' };
                    $label = match($juaraKe) { 1 => 'Juara 1', 2 => 'Juara 2', 3 => 'Juara 3', default => 'Juara' };
                    $colorClass = match($juaraKe) {
                        1 => 'gd-atlet-row--emas',
                        2 => 'gd-atlet-row--perak',
                        3 => 'gd-atlet-row--perunggu',
                        default => 'gd-atlet-row--default',
                    };
                @endphp
                <div class="gd-atlet-row {{ $colorClass }}">
                    <span class="gd-atlet-row-icon">{{ $icon }}</span>
                    <span class="gd-atlet-row-nama">{{ $atlet['nama'] ?? '-' }}</span>
                    <span class="gd-atlet-row-label">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
/* Reuse gd- styles from gallery-detail */
.gd-header-section {
    background: #ffffff;
    padding: 48px 0 36px;
    border-bottom: 1px solid #f1f5f9;
}
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
.gd-badge-tahun    { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
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
.gd-badge-special.gold   { background: #fefce8; color: #a16207; border: 1px solid #fde68a; }
.gd-badge-special.silver { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
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

/* Video grid */
.vd-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}
.vd-item {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.vd-embed-wrap {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    background: #000;
}
.vd-embed-wrap iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}
.vd-video-wrap video {
    width: 100%;
    display: block;
    background: #000;
    max-height: 280px;
    object-fit: contain;
}
.vd-nama {
    padding: 10px 14px;
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
    font-weight: 600;
}

/* Medali */
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
.gd-medali-emas     { background: #fefce8; border-color: #fde68a; }
.gd-medali-emas .gd-medali-icon, .gd-medali-emas .gd-medali-count, .gd-medali-emas .gd-medali-label { color: #a16207; }
.gd-medali-perak    { background: #f8fafc; border-color: #e2e8f0; }
.gd-medali-perak .gd-medali-icon, .gd-medali-perak .gd-medali-count, .gd-medali-perak .gd-medali-label { color: #475569; }
.gd-medali-perunggu { background: #fff7ed; border-color: #fed7aa; }
.gd-medali-perunggu .gd-medali-icon, .gd-medali-perunggu .gd-medali-count, .gd-medali-perunggu .gd-medali-label { color: #c2410c; }
.gd-medali-special  { background: #fff1f2; border-color: #fecdd3; }
.gd-medali-special .gd-medali-icon, .gd-medali-special .gd-medali-label { color: #cc2929; }

/* Atlet list */
.gd-atlet-list-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 10px;
    margin-top: 12px;
}
.gd-atlet-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1px solid transparent;
    font-size: 0.9rem;
    font-weight: 600;
}
.gd-atlet-row-icon  { font-size: 1.3rem; flex-shrink: 0; }
.gd-atlet-row-nama  { flex: 1; color: #1a2a4a; }
.gd-atlet-row-label {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    flex-shrink: 0;
}
.gd-atlet-row--emas     { background: #fefce8; border-color: #fde68a; }
.gd-atlet-row--emas .gd-atlet-row-label     { background: #fde68a; color: #92400e; }
.gd-atlet-row--perak    { background: #f8fafc; border-color: #e2e8f0; }
.gd-atlet-row--perak .gd-atlet-row-label    { background: #e2e8f0; color: #475569; }
.gd-atlet-row--perunggu { background: #fff7ed; border-color: #fed7aa; }
.gd-atlet-row--perunggu .gd-atlet-row-label { background: #fed7aa; color: #c2410c; }
.gd-atlet-row--default  { background: #fff1f2; border-color: #fecdd3; }
.gd-atlet-row--default .gd-atlet-row-label  { background: #fecdd3; color: #cc2929; }

@media (max-width: 640px) {
    .vd-grid { grid-template-columns: 1fr; }
    .gd-medali-card { min-width: 150px; padding: 14px 18px; }
    .gd-medali-count { font-size: 1.6rem; }
    .gd-atlet-list-grid { grid-template-columns: 1fr; }
}
</style>
@endpush
