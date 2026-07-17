@extends('layouts.app')

@section('title', $event->judul . ' - Syifa Boxing Camp')
@section('og_title', $event->judul . ' - ' . ($siteSettings['nama_sasana'] ?? 'Syifa Boxing Camp'))
@section('og_description', $event->deskripsi ? \Illuminate\Support\Str::limit(strip_tags($event->deskripsi), 160) : 'Informasi lengkap event tinju ' . $event->judul . ' yang diselenggarakan oleh Syifa Boxing Camp.')
@section('og_image', foto_url($event->foto, asset('assets/images/polosan_logo_syifa.png')))

@section('content')

{{-- ===== HERO DETAIL EVENT ===== --}}
<section class="ed-hero">
    <div class="container">
        <div class="ed-hero-inner">

            {{-- Breadcrumb --}}
            <div class="ed-breadcrumb-row">
                <nav class="ed-breadcrumb">
                    <a href="{{ route('event') }}">Event</a>
                    <span>/</span>
                    <span>{{ $event->judul }}</span>
                </nav>
                <a href="{{ route('event') }}" class="ed-btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="row align-items-center g-4">

                {{-- Kiri: Poster --}}
                <div class="col-md-4 col-lg-3 text-center">
                    <div class="ed-poster-wrap">
                        <img src="{{ foto_url($event->foto, asset('assets/logo/logo.jpg')) }}"
                             alt="{{ $event->judul }}" class="ed-poster-img">
                    </div>
                </div>

                {{-- Kanan: Info Event --}}
                <div class="col-md-8 col-lg-9">

                    {{-- Status Badge --}}
                    <span class="ed-status-badge ed-status-{{ $event->status }}">
                        @if($event->status === 'selesai')
                            <i class="fas fa-check-circle"></i> Telah Selesai
                        @elseif($event->status === 'dibuka')
                            <i class="fas fa-door-open"></i> Pendaftaran Dibuka
                        @else
                            <i class="fas fa-clock"></i> Segera Hadir
                        @endif
                    </span>

                    <h1 class="ed-title">{{ $event->judul }}</h1>

                    <div class="ed-meta-row">
                        <div class="ed-meta-item">
                            <i class="far fa-calendar-alt"></i>
                            @if($event->tanggal_mulai?->format('d M Y') === $event->tanggal_selesai?->format('d M Y'))
                                {{ $event->tanggal_mulai->translatedFormat('d F Y') }}
                            @else
                                {{ $event->tanggal_mulai->translatedFormat('d F Y') }} – {{ $event->tanggal_selesai->translatedFormat('d F Y') }}
                            @endif
                        </div>
                        <div class="ed-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            @if($event->maps_link)
                                <a href="{{ $event->maps_link }}" target="_blank" class="ed-lokasi-link">
                                    {{ $event->lokasi }}
                                    <i class="fas fa-external-link-alt" style="font-size:0.7rem; margin-left:4px;"></i>
                                </a>
                            @else
                                {{ $event->lokasi }}
                            @endif
                        </div>
                    </div>

                    @if($event->deskripsi)
                    <p class="ed-desc">{{ $event->deskripsi }}</p>
                    @endif

                    {{-- Tombol Daftar — hanya jika status bukan selesai --}}
                    @if($event->status !== 'selesai')
                    @php
                        $waListHero = $event->wa_pendaftaran ?? [];
                        $nomorPertama = count($waListHero) > 0 ? $waListHero[0]['nomor'] : $whatsapp;
                        $pesan = urlencode('Halo, saya ingin mendaftar event ' . $event->judul . '. Mohon informasinya. Terima kasih 🙏');
                    @endphp
                    <a href="https://wa.me/{{ $nomorPertama }}?text={{ $pesan }}"
                       target="_blank"
                       class="ed-btn-daftar">
                        <i class="fab fa-whatsapp"></i>
                        Daftar Sekarang
                    </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CARD HARGA TIKET & PENDAFTARAN ATLET ===== --}}
@if($event->harga_tiket || $event->harga_atlet)
<section class="ed-pricing-section">
    <div class="container">
        <div class="ed-pricing-layout">

            {{-- KIRI: Card Harga --}}
            <div class="ed-pricing-left">
                <div class="ed-pricing-cards">

                    {{-- Card Tiket Penonton --}}
                    @if($event->harga_tiket)
                    <div class="ed-price-card ed-price-card--tiket">
                        <div class="ed-price-card-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="ed-price-card-label">Harga Tiket Penonton</div>
                        <div class="ed-price-card-amount">
                            Rp {{ number_format($event->harga_tiket, 0, ',', '.') }}
                            <span class="ed-price-card-unit">/orang</span>
                        </div>
                        @if($event->promo_tiket)
                        <div class="ed-price-card-promo">
                            <i class="fas fa-tag"></i>
                            {{ $event->promo_tiket }}
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Card Pendaftaran Atlet --}}
                    @if($event->harga_atlet)
                    <div class="ed-price-card ed-price-card--atlet">
                        <div class="ed-price-card-icon">
                            <i class="fas fa-fist-raised"></i>
                        </div>
                        <div class="ed-price-card-label">Biaya Pendaftaran Atlet</div>
                        <div class="ed-price-card-amount">
                            Rp {{ number_format($event->harga_atlet, 0, ',', '.') }}
                            <span class="ed-price-card-unit">/peserta</span>
                        </div>
                        @if($event->include_atlet)
                        <div class="ed-price-card-includes">
                            <div class="ed-price-card-includes-title">Include:</div>
                            @foreach(explode(',', $event->include_atlet) as $item)
                            <div class="ed-price-card-include-item">
                                <i class="fas fa-check-circle"></i>
                                {{ trim($item) }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            {{-- KANAN: Hubungi Panitia --}}
            <div class="ed-pricing-right">
                @php
                    $waList = $event->wa_pendaftaran ?? [];
                    $pesanDaftar = urlencode('Halo, saya ingin bertanya tentang event ' . $event->judul . '. Mohon informasi lebih lanjut. Terima kasih 🙏');
                @endphp
                @if(count($waList) > 0)
                <div class="ed-panitia-wrap">
                    <div class="ed-panitia-title">
                        <i class="fas fa-headset"></i> Hubungi Panitia
                    </div>
                    <div class="ed-panitia-grid">
                        @foreach($waList as $wa)
                        <a href="https://wa.me/{{ $wa['nomor'] }}?text={{ $pesanDaftar }}"
                           target="_blank"
                           class="ed-panitia-card">
                            <div class="ed-panitia-card-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="ed-panitia-card-info">
                                <div class="ed-panitia-card-nama">{{ $wa['nama'] ?? 'Panitia Event' }}</div>
                                <div class="ed-panitia-card-nomor">+{{ ltrim($wa['nomor'], '0') }}</div>
                            </div>
                            <div class="ed-panitia-card-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @elseif($whatsapp)
                <div class="ed-panitia-wrap">
                    <div class="ed-panitia-title">
                        <i class="fas fa-headset"></i> Hubungi Panitia
                    </div>
                    <div class="ed-panitia-grid">
                        @php $pesanHarga = urlencode('Halo, saya ingin bertanya tentang event ' . $event->judul . '. Mohon informasi lebih lanjut. Terima kasih 🙏'); @endphp
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ $pesanHarga }}"
                           target="_blank"
                           class="ed-panitia-card">
                            <div class="ed-panitia-card-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="ed-panitia-card-info">
                                <div class="ed-panitia-card-nama">Admin Syifa Boxing Camp</div>
                                <div class="ed-panitia-card-nomor">+{{ ltrim($whatsapp, '0') }}</div>
                            </div>
                            <div class="ed-panitia-card-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif

{{-- ===== DOKUMENTASI FOTO ===== --}}
<section class="ed-gallery-section">
    <div class="container">

        <div class="ed-section-header">
            <span class="ed-section-label">DOKUMENTASI</span>
            <h2 class="ed-section-title">Foto Event</h2>
        </div>

        @php
            $allFotos = collect();
            foreach ($event->galeris as $galeri) {
                if (is_array($galeri->foto)) {
                    foreach ($galeri->foto as $file) {
                        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                            $allFotos->push([
                                'url'        => foto_url($file),
                                'judul'      => $galeri->judul,
                                'keterangan' => $galeri->keterangan,
                                'tahun'      => $galeri->tahun,
                            ]);
                        }
                    }
                }
            }
        @endphp

        @if($allFotos->count() > 0)
        <div class="ed-doc-grid">
            @foreach($allFotos as $i => $foto)
            <div class="ed-doc-item" onclick="openDocLightbox({{ $i }})">
                <img src="{{ $foto['url'] }}" alt="{{ $foto['judul'] }}" loading="lazy">
                <div class="ed-doc-overlay">
                    <i class="fas fa-expand-alt"></i>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="ed-gallery-empty">
            <i class="far fa-images"></i>
            <p>Belum ada dokumentasi foto untuk event ini.</p>
        </div>
        @endif

    </div>
</section>



{{-- ===== LIGHTBOX DOKUMENTASI (gaya galeri) ===== --}}
<div id="doc-lightbox" onclick="if(event.target===this) closeDocLightbox()">
    <div class="lb-modal">
        <div class="lb-header">
            <span id="doc-lb-counter" class="lb-counter"></span>
            <button class="lb-close" onclick="closeDocLightbox()">&times;</button>
        </div>
        <div class="lb-foto-wrap">
            <button class="lb-nav lb-nav-prev" id="doc-lb-prev" onclick="docLightboxPrev()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <img id="doc-lb-img" src="" alt="Dokumentasi">
            <button class="lb-nav lb-nav-next" id="doc-lb-next" onclick="docLightboxNext()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="lb-info">
            <div class="lb-info-top">
                <div>
                    <h5 id="doc-lb-judul" class="lb-judul"></h5>
                    <span id="doc-lb-tahun" class="lb-tahun"></span>
                </div>
            </div>
            <p id="doc-lb-keterangan" class="lb-keterangan" style="display:none;"></p>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/event.css') }}">
<style>
/* ===== EVENT DETAIL ===== */
.ed-hero {
    background: #fff;
    padding: 40px 0 50px;
    border-bottom: 1px solid #f1f5f9;
}

.ed-hero-inner {
    max-width: 1000px;
    margin: 0 auto;
}

.ed-breadcrumb-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.ed-breadcrumb {
    font-size: 0.83rem;
    color: #94a3b8;
    display: flex;
    gap: 8px;
    align-items: center;
}

.ed-breadcrumb a {
    color: #e91e8c;
    text-decoration: none;
    font-weight: 500;
}

.ed-breadcrumb a:hover { text-decoration: underline; }

/* Tombol Kembali */
.ed-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 0.83rem;
    font-weight: 500;
    text-decoration: none;
    padding: 7px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s;
    white-space: nowrap;
}

.ed-btn-back:hover {
    background: #f1f5f9;
    color: #1a2a4a;
    border-color: #cbd5e1;
}

/* Poster */
.ed-poster-wrap {
    background: #ffffff;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
}

.ed-poster-img {
    width: 100%;
    max-height: 280px;
    object-fit: contain;
    border-radius: 8px;
}

/* Status Badge */
.ed-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 12px;
}

.ed-status-selesai {
    background: #fff1f2;
    color: #e11d48;
}

.ed-status-dibuka {
    background: #f0fdf4;
    color: #16a34a;
}

.ed-status-segera {
    background: #fffbeb;
    color: #d97706;
}

/* Judul */
.ed-title {
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 900;
    color: #1a2a4a;
    line-height: 1.25;
    margin-bottom: 16px;
}

/* Meta */
.ed-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
}

.ed-meta-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.9rem;
    color: #64748b;
}

.ed-meta-item i {
    color: #e91e8c;
    font-size: 0.85rem;
}

/* Deskripsi */
.ed-desc {
    font-size: 0.95rem;
    color: #475569;
    line-height: 1.75;
    margin-bottom: 24px;
    max-width: 600px;
}

/* Tombol Daftar */
.ed-btn-daftar {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: #25d366;
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 12px 28px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 4px 14px rgba(37,211,102,0.3);
}

.ed-btn-daftar:hover {
    background: #1ebe5d;
    color: #fff;
    transform: translateY(-2px);
}

.ed-btn-daftar i {
    font-size: 1.15rem;
}

/* ===== GALERI SECTION ===== */
.ed-gallery-section {
    padding: 60px 0 40px;
    background: #ffffff;
}

.ed-section-header {
    text-align: center;
    margin-bottom: 36px;
}

.ed-section-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 2px;
    color: #e91e8c;
    display: block;
    margin-bottom: 8px;
}

.ed-section-title {
    font-size: 1.9rem;
    font-weight: 900;
    color: #1a2a4a;
}

/* Grid Foto — natural size masonry */
.ed-doc-grid {
    columns: 3;
    column-gap: 12px;
}

.ed-doc-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    margin-bottom: 12px;
    break-inside: avoid;
    display: block;
}

.ed-doc-item img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 10px;
    transition: transform 0.35s ease;
}

.ed-doc-item:hover img {
    transform: scale(1.03);
}

.ed-doc-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s;
    color: #fff;
    font-size: 1.4rem;
    border-radius: 10px;
}

.ed-doc-item:hover .ed-doc-overlay {
    opacity: 1;
}

/* Empty state */
.ed-gallery-empty {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.ed-gallery-empty i {
    font-size: 3rem;
    display: block;
    margin-bottom: 12px;
}



/* ===== LIGHTBOX ===== */
.ed-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.ed-lightbox.active {
    display: flex;
}

.ed-lightbox-content {
    max-width: 90vw;
    max-height: 90vh;
    text-align: center;
}

.ed-lightbox-content img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 8px;
    object-fit: contain;
}

.ed-lightbox-content p {
    color: #e2e8f0;
    font-size: 0.88rem;
    margin-top: 10px;
}

.ed-lightbox-close,
.ed-lightbox-prev,
.ed-lightbox-next {
    position: absolute;
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    font-size: 1.2rem;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.ed-lightbox-close { top: 20px; right: 20px; }
.ed-lightbox-prev  { left: 20px; top: 50%; transform: translateY(-50%); }
.ed-lightbox-next  { right: 20px; top: 50%; transform: translateY(-50%); }

.ed-lightbox-close:hover,
.ed-lightbox-prev:hover,
.ed-lightbox-next:hover {
    background: rgba(255,255,255,0.25);
}

@media (max-width: 768px) {
    .ed-doc-grid { columns: 2; }
    .ed-lightbox-prev { left: 8px; }
    .ed-lightbox-next { right: 8px; }
}

@media (max-width: 480px) {
    .ed-doc-grid { columns: 1; }
}

/* ===== LIGHTBOX MODAL (gaya galeri) ===== */
#doc-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
#doc-lightbox.active { display: flex; }

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
.lb-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px 8px;
    border-bottom: 1px solid #f0f0f0;
}
.lb-counter { font-size: 0.82rem; color: #888; font-weight: 500; }
.lb-close {
    background: #f1f1f1; border: none; width: 32px; height: 32px;
    border-radius: 50%; font-size: 1.2rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #555; transition: background 0.2s;
}
.lb-close:hover { background: #e0e0e0; }
.lb-foto-wrap {
    position: relative; background: #fff;
    display: flex; align-items: center; justify-content: center;
    min-height: 280px; max-height: 50vh; overflow: hidden;
}
#doc-lb-img {
    max-width: 100%; max-height: 50vh;
    object-fit: contain; padding: 16px; display: block;
}
.lb-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(0,0,0,0.35); border: none; color: #fff;
    width: 36px; height: 36px; border-radius: 50%; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; transition: background 0.2s; z-index: 2;
}
.lb-nav:hover { background: rgba(0,0,0,0.6); }
.lb-nav-prev { left: 10px; }
.lb-nav-next { right: 10px; }
.lb-info {
    padding: 16px 20px 20px; border-top: 1px solid #f0f0f0;
    background: #fff; overflow-y: auto; max-height: 180px;
}
.lb-info-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
.lb-judul { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 4px; line-height: 1.4; }
.lb-tahun { font-size: 0.78rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
.lb-keterangan { font-size: 0.85rem; color: #64748b; margin: 10px 0 0; line-height: 1.6; }

/* ===== PRICING CARDS ===== */
.ed-pricing-section {
    padding: 24px 0 40px;
    background: #fff;
}

/* ===== PRICING LAYOUT 2 KOLOM ===== */
.ed-pricing-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
    align-items: center;
}

.ed-pricing-cards {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ed-pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    max-width: 780px;
    margin: 0 auto 28px;
}

.ed-price-card {
    border-radius: 16px;
    padding: 28px 26px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    overflow: hidden;
}

.ed-price-card--tiket {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1.5px solid #fbbf24;
}

.ed-price-card--atlet {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1.5px solid #4ade80;
}

.ed-price-card-icon {
    font-size: 1.6rem;
    margin-bottom: 2px;
}

.ed-price-card--tiket .ed-price-card-icon { color: #d97706; }
.ed-price-card--atlet .ed-price-card-icon { color: #16a34a; }

.ed-price-card-label {
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #64748b;
}

.ed-price-card-amount {
    font-size: 1.75rem;
    font-weight: 900;
    color: #1a2a4a;
    line-height: 1.1;
}

.ed-price-card--tiket .ed-price-card-amount { color: #b45309; }
.ed-price-card--atlet .ed-price-card-amount { color: #15803d; }

.ed-price-card-unit {
    font-size: 0.9rem;
    font-weight: 500;
    color: #94a3b8;
}

.ed-price-card-promo {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px dashed #f59e0b;
    color: #b45309;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    margin-top: 4px;
}

.ed-price-card-promo i { font-size: 0.75rem; }

.ed-price-card-includes {
    margin-top: 6px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.ed-price-card-includes-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.ed-price-card-include-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.88rem;
    color: #334155;
    font-weight: 500;
}

.ed-price-card-include-item i {
    color: #22c55e;
    font-size: 0.85rem;
    flex-shrink: 0;
}

/* Tombol WA besar di bawah card */
.ed-pricing-wa {
    text-align: center;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
}

/* ===== HUBUNGI PANITIA CARDS ===== */
.ed-panitia-wrap {
    margin-top: 0;
}

.ed-panitia-title {
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.ed-panitia-title i {
    color: #25d366;
}

.ed-panitia-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ed-panitia-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    text-decoration: none;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    cursor: pointer;
}

.ed-panitia-card:hover {
    border-color: #25d366;
    box-shadow: 0 4px 16px rgba(37,211,102,0.12);
    transform: translateY(-1px);
}

.ed-panitia-card-icon {
    width: 44px;
    height: 44px;
    background: #f0fdf4;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #25d366;
    font-size: 1.2rem;
    flex-shrink: 0;
    transition: background 0.2s;
}

.ed-panitia-card:hover .ed-panitia-card-icon {
    background: #25d366;
    color: #fff;
}

.ed-panitia-card-info {
    flex: 1;
}

.ed-panitia-card-nama {
    font-size: 0.92rem;
    font-weight: 700;
    color: #1a2a4a;
    line-height: 1.3;
}

.ed-panitia-card-nomor {
    font-size: 0.82rem;
    color: #64748b;
    margin-top: 2px;
}

.ed-panitia-card-arrow {
    color: #cbd5e1;
    font-size: 0.8rem;
    transition: color 0.2s;
}

.ed-panitia-card:hover .ed-panitia-card-arrow {
    color: #25d366;
}

.ed-lokasi-link {
    color: #e91e8c;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    transition: color 0.2s;
}

.ed-lokasi-link:hover {
    color: #be185d;
    text-decoration: underline;
}

.ed-btn-wa {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #25d366;
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 6px 20px rgba(37,211,102,0.35);
}

.ed-btn-wa:hover {
    background: #1ebe5d;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(37,211,102,0.4);
}

.ed-btn-wa i {
    font-size: 1.3rem;
}

@media (max-width: 600px) {
    .ed-pricing-grid {
        grid-template-columns: 1fr;
    }
    .ed-pricing-layout {
        grid-template-columns: 1fr;
    }
    .ed-price-card-amount {
        font-size: 1.4rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    const docPhotos = @json($allFotos->values());
    let docCurrent = 0;

    function openDocLightbox(index) {
        docCurrent = index;
        updateDocLightbox();
        document.getElementById('doc-lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDocLightbox() {
        document.getElementById('doc-lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    function updateDocLightbox() {
        const photo = docPhotos[docCurrent];
        const total = docPhotos.length;

        document.getElementById('doc-lb-img').src = photo.url;
        document.getElementById('doc-lb-counter').textContent = total > 1 ? (docCurrent + 1) + ' / ' + total : '';
        document.getElementById('doc-lb-judul').textContent = photo.judul || '';
        document.getElementById('doc-lb-tahun').textContent = photo.tahun ? photo.tahun : '';

        const showNav = total > 1;
        document.getElementById('doc-lb-prev').style.display = showNav ? 'flex' : 'none';
        document.getElementById('doc-lb-next').style.display = showNav ? 'flex' : 'none';

        const ketEl = document.getElementById('doc-lb-keterangan');
        if (photo.keterangan) {
            ketEl.textContent = photo.keterangan;
            ketEl.style.display = 'block';
        } else {
            ketEl.style.display = 'none';
        }
    }

    function docLightboxPrev() {
        docCurrent = (docCurrent - 1 + docPhotos.length) % docPhotos.length;
        updateDocLightbox();
    }

    function docLightboxNext() {
        docCurrent = (docCurrent + 1) % docPhotos.length;
        updateDocLightbox();
    }

    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('doc-lightbox').classList.contains('active')) return;
        if (e.key === 'ArrowLeft')  docLightboxPrev();
        if (e.key === 'ArrowRight') docLightboxNext();
        if (e.key === 'Escape')     closeDocLightbox();
    });
</script>
@endpush
