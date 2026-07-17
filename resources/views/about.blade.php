@extends('layouts.app')

@section('title', 'Tentang Kami - Syifa Boxing Camp')
@section('meta_description', 'Kenali lebih dekat Syifa Boxing Camp — sejarah, visi misi, pelatih berpengalaman, dan prestasi kami dalam dunia tinju Indonesia.')
@section('og_title', 'Tentang Kami - SYIFA Boxing Camp')
@section('og_description', 'Kenali lebih dekat Syifa Boxing Camp — sejarah, visi misi, dan prestasi kami dalam dunia tinju Indonesia sejak 1998.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== PROFIL SINGKAT ===== --}}
<section class="tentang-page-section border-bottom-subtle animate-fade-in">
    <div class="container">
        <div class="row align-items-start g-5">

            {{-- Kiri: Teks & Informasi --}}
            <div class="col-lg-6 content-slide-up">
                <span class="section-label-premium">Profil Sasana</span>
                <h1 class="hero-title-premium mt-2">
                    Syifa <span class="highlight-accent">Boxing</span><br>Camp
                </h1>
                <div class="divider-elegant"></div>

                <p class="hero-desc-premium">
                    {{ $settings['deskripsi_tentang'] ?: ($settings['deskripsi'] ?: 'Syifa Boxing Camp merupakan sebuah sasana tinju yang berlokasi di GOR Padjadjaran, Kota Bandung, Jawa Barat.') }}
                </p>

                {{-- Tombol --}}
                <div class="hero-action-wrapper">
                    <a href="{{ route('event') }}" class="btn-event-premium">
                        <span>Lihat Event</span> <i class="fas fa-arrow-right ms-2 arrow-icon"></i>
                    </a>
                </div>

                {{-- Stats Ringkas --}}
                <div class="hero-stats-premium">
                    <div class="stat-item-premium">
                        <div class="stat-number-premium">{{ $settings['tahun_berdiri'] }}</div>
                        <div class="stat-label-premium">Tahun Berdiri</div>
                    </div>
                    <div class="stat-item-premium">
                        <div class="stat-number-premium">{{ $totalPrestasi > 0 ? $totalPrestasi . '+' : '0' }}</div>
                        <div class="stat-label-premium">Prestasi</div>
                    </div>
                    <div class="stat-item-premium">
                        <div class="stat-number-premium">{{ $totalMedali > 0 ? $totalMedali . '+' : '0' }}</div>
                        <div class="stat-label-premium">Medali</div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Foto Grid Premium --}}
            @php
                $fotoProfil = json_decode(\App\Models\SiteSettings::get('foto_tentang', '[]'), true) ?? [];
                $fotoUrls   = array_values(array_filter(
                    array_map(fn($f) => trim($f) !== '' ? asset('storage/' . $f) : null, $fotoProfil)
                ));
            @endphp
            <div class="col-lg-6 visual-slide-up">
                @if(count($fotoUrls) > 0)
                <div class="foto-grid-premium">
                    {{-- Foto 1: besar penuh di atas --}}
                    <div class="grid-box item-top">
                        <img src="{{ $fotoUrls[0] }}" alt="Foto Sasana 1">
                        <div class="subtle-overlay"></div>
                    </div>

                    {{-- Baris tengah: foto 2 (lebar) & foto 3 (kecil) --}}
                    @if(count($fotoUrls) > 1)
                    <div class="grid-box-bottom grid-row-1">
                        <div class="grid-box">
                            <img src="{{ $fotoUrls[1] }}" alt="Foto Sasana 2">
                            <div class="subtle-overlay"></div>
                        </div>
                        @if(isset($fotoUrls[2]))
                        <div class="grid-box">
                            <img src="{{ $fotoUrls[2] }}" alt="Foto Sasana 3">
                            <div class="subtle-overlay"></div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Baris bawah: foto 4 (kecil) & foto 5 (lebar) --}}
                    @if(count($fotoUrls) > 3)
                    <div class="grid-box-bottom grid-row-2">
                        <div class="grid-box">
                            <img src="{{ $fotoUrls[3] }}" alt="Foto Sasana 4">
                            <div class="subtle-overlay"></div>
                        </div>
                        @if(isset($fotoUrls[4]))
                        <div class="grid-box">
                            <img src="{{ $fotoUrls[4] }}" alt="Foto Sasana 5">
                            <div class="subtle-overlay"></div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @else
                <div class="d-flex align-items-center justify-content-center h-100 text-muted" style="min-height:300px; border:2px dashed #ccc; border-radius:12px;">
                    <div class="text-center">
                        <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Foto belum diupload.<br><small>Upload via Admin → Pengaturan → Foto Halaman Tentang Kami</small></p>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>

{{-- ===== PELATIH (CARD WITH INTERNAL PHOTO SLIDER) ===== --}}
<section id="pelatih" class="tentang-page-section pelatih-gray-section">
    <div class="container">
        <div class="text-center mb-5 header-slide-up">
            <span class="section-label-premium">Tim Pelatih</span>
            <h2 class="section-title-premium mt-1">Para Pelatih Kami</h2>
            <div class="divider-elegant divider-center"></div>
        </div>

        <div class="row g-4 justify-content-center pelatih-row-max header-slide-up">

            @forelse($pelatih as $p)
            <div class="col-12 col-sm-6 col-md-4">
                <div class="pelatih-card-luxury">

                    {{-- Mini Carousel per Pelatih --}}
                    @php $fotos = is_array($p->foto) ? $p->foto : []; @endphp
                    <div id="slidePelatih{{ $p->id }}" class="carousel slide pelatih-avatar-carousel" data-bs-ride="false">
                        <div class="carousel-inner">
                            @if(count($fotos) > 0)
                                @foreach($fotos as $i => $foto)
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Foto {{ $p->nama_lengkap }}">
                                </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ $p->nama_lengkap }}">
                                </div>
                            @endif
                        </div>

                        @if(count($fotos) > 1)
                        <button class="carousel-control-prev mini-nav-btn" type="button"
                            data-bs-target="#slidePelatih{{ $p->id }}" data-bs-slide="prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="carousel-control-next mini-nav-btn" type="button"
                            data-bs-target="#slidePelatih{{ $p->id }}" data-bs-slide="next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        @endif
                    </div>

                    <div class="pelatih-meta-box">
                        <h5 class="pelatih-name-luxury">{{ $p->nama_lengkap }}</h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada data pelatih.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush