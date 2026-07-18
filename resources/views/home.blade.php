@extends('layouts.app')

@section('title', 'Beranda - SYIFA Boxing Camp')
@section('meta_description', 'Syifa Boxing Camp — sasana tinju profesional yang mencetak atlet berprestasi. Bergabunglah dan wujudkan impianmu bersama kami!')
@section('og_title', 'Syifa Boxing Camp — Sasana Tinju Profesional')
@section('og_description', 'Syifa Boxing Camp — sasana tinju profesional yang mencetak atlet berprestasi. Bergabunglah dan wujudkan impianmu bersama kami!')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO ===== --}}
<section id="hero">
    <div class="container">
        <div class="hero-content">
            @php
                $heroBadge   = \App\Models\SiteSettings::get('hero_badge', 'Pusat Latihan Tinju Terbaik Sejak 1998');
                $heroJudul   = \App\Models\SiteSettings::get('hero_judul', 'Melatih Atlet, Mencetak Juara, Harum Bangsa!');
                $heroDesc    = \App\Models\SiteSettings::get('hero_desc', 'Syifa Boxing Camp hadir untuk membina dan mengembangkan atlet tinju menuju prestasi terbaik.');
            @endphp
            <div class="hero-badge">
                <i class="fas fa-circle" style="font-size:7px;"></i>
                {{ $heroBadge }}
            </div>
            <h1 class="hero-title">
                {{ $heroJudul }}
            </h1>
            <p class="hero-desc">{{ $heroDesc }}</p>
            <div class="hero-btns">
                <a href="/event" class="btn-primary-custom btn-glow">
                    Lihat Event <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/about" class="btn-outline-custom">
                    Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== TENTANG SINGKAT ===== --}}
<section id="about-singkat">
    <div class="container">
        <div class="about-section-card">
            <div class="row align-items-center g-5">

                {{-- Kiri: Teks --}}
                <div class="col-lg-6">
                    @php
                        $tahunBerdiri = \App\Models\SiteSettings::get('tahun_berdiri', '1998');
                        $deskripsi    = \App\Models\SiteSettings::get('deskripsi');
                        $statMember2  = \App\Models\PendaftaranMember::where('aktif', true)->count();
                        $statTahun2   = (date('Y') - (int)$tahunBerdiri) . '+';
                        $statPrestasi2= \App\Models\Galeri::where('kategori', 'pertandingan')->get()
                            ->sum(fn($g) => $g->jumlahPrestasi());
                        $statMedali   = \App\Models\Galeri::where('kategori', 'pertandingan')->get()
                            ->sum(fn($g) => $g->jumlahMedali());
                    @endphp
                    <div class="hero-badge mb-3">
                        <i class="fas fa-circle" style="font-size:7px;"></i>
                        Berdiri Sejak {{ $tahunBerdiri }}
                    </div>
                    <h2 class="section-title">Tentang <span>Syifa Boxing Camp</span></h2>
                    <div class="divider"></div>
                    <p style="color:var(--gray-dark); font-size:0.97rem; line-height:1.8; margin-bottom:28px;">
                        {{ $deskripsi ?: 'Syifa Boxing Camp adalah pusat latihan tinju profesional yang telah berdiri sejak tahun ' . $tahunBerdiri . '. Kami berkomitmen membina atlet dari berbagai kalangan untuk meraih prestasi terbaik.' }}
                    </p>

                    {{-- Stats --}}
                    <div class="d-flex gap-5 mb-4">
                        <div>
                            <div id="stat-member" data-target="{{ $statMember2 }}" style="font-size:2rem; font-weight:900; color:var(--navy);">0</div>
                            <div style="font-size:0.78rem; color:var(--gray); text-transform:uppercase; letter-spacing:1px;">Member Aktif</div>
                        </div>
                        <div>
                            <div style="font-size:2rem; font-weight:900; color:var(--navy);">{{ $statTahun2 }}</div>
                            <div style="font-size:0.78rem; color:var(--gray); text-transform:uppercase; letter-spacing:1px;">Tahun Berdiri</div>
                        </div>
                        <div>
                            <div id="stat-prestasi" data-target="{{ $statPrestasi2 }}" style="font-size:2rem; font-weight:900; color:var(--navy);">0</div>
                            <div style="font-size:0.78rem; color:var(--gray); text-transform:uppercase; letter-spacing:1px;">Prestasi</div>
                        </div>
                        <div>
                            <div id="stat-medali" data-target="{{ $statMedali }}" style="font-size:2rem; font-weight:900; color:var(--navy);">0</div>
                            <div style="font-size:0.78rem; color:var(--gray); text-transform:uppercase; letter-spacing:1px;">Medali</div>
                        </div>
                    </div>

                    <a href="/about" class="btn-primary-custom">
                        Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Kanan: Foto + Floating Card --}}
                <div class="col-lg-6">
                    <div class="about-img-wrap">

                        {{-- Foto utama --}}
                        @php $fotoBeranda = \App\Models\SiteSettings::get('foto_beranda'); @endphp
                        @if($fotoBeranda)
                        <div class="about-img-main">
                            <img src="{{ foto_url($fotoBeranda) }}" alt="Syifa Boxing Camp">
                        </div>
                        @endif

                        {{-- Floating card --}}
                        <div class="about-float-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:28px; height:28px; background:var(--red); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-trophy" style="color:white; font-size:0.72rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700; font-size:0.82rem; color:var(--navy); line-height:1.1;">Prestasi</div>
                                    <div style="font-size:0.68rem; color:var(--gray);">Terdepan</div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mt-1">
                                <div style="text-align:center;">
                                    <div style="font-size:1.1rem; font-weight:900; color:var(--navy);">{{ $statTahun2 }}</div>
                                    <div style="font-size:0.65rem; color:var(--gray);">Tahun</div>
                                </div>
                                <div style="text-align:center;">
                                    <div style="font-size:1.1rem; font-weight:900; color:var(--red);">{{ $statMedali }}</div>
                                    <div style="font-size:0.65rem; color:var(--gray);">Medali</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ===== EVENT ===== --}}
<section id="event">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <span class="section-label">Event Tinju</span>
                <h2 class="event-section-title">
                    <span class="event-emoji">🎉</span>
                    <span class="event-bullet">●</span>
                    Event – event Kami
                </h2>
            </div>
            <a href="/event" class="event-lihat-semua">
                Lihat semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3 justify-content-center">

            @forelse($events as $event)
            <div class="col-12 col-md-4">
                <div class="event-card-new">
                    {{-- Gambar di atas (vertikal di desktop, kiri di mobile) --}}
                    <div class="event-card-img-wrap">
                        <img src="{{ foto_url($event->foto, asset('assets/logo/logo.jpg')) }}" alt="{{ $event->judul }}">
                    </div>
                    {{-- Konten di bawah --}}
                    <div class="event-card-body">
                        <span class="event-label-status {{ $event->status === 'selesai' ? 'closed' : ($event->status === 'dibuka' ? 'open' : 'soon') }}">
                            {{ $event->status === 'selesai' ? 'Telah Selesai' : ($event->status === 'dibuka' ? 'Pendaftaran Dibuka' : 'Segera Hadir') }}
                        </span>
                        <h5>{{ $event->judul }}</h5>
                        <p class="event-date-text"><i class="far fa-calendar-alt me-1"></i>
                            @if($event->tanggal_mulai && $event->tanggal_selesai)
                                @if($event->tanggal_mulai->format('d M Y') === $event->tanggal_selesai->format('d M Y'))
                                    {{ $event->tanggal_mulai->translatedFormat('d F Y') }}
                                @else
                                    {{ $event->tanggal_mulai->translatedFormat('d F Y') }} – {{ $event->tanggal_selesai->translatedFormat('d F Y') }}
                                @endif
                            @elseif($event->tanggal_mulai)
                                {{ $event->tanggal_mulai->translatedFormat('d F Y') }}
                            @else
                                <span class="text-muted">Tanggal belum ditentukan</span>
                            @endif
                        </p>
                        <p class="event-loc-text"><i class="fas fa-trophy me-1"></i>{{ $event->lokasi }}</p>
                    </div>
                    <div class="event-card-footer">
                        <a href="{{ route('event.show', $event->slug) }}" class="event-btn-lihat">Lihat Event <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada event tersedia.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

{{-- ===== GALERI ===== --}}
<section id="galeri">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <span class="section-label">Galeri</span>
                <h2 class="event-section-title">
                    <span class="event-emoji">📸</span>
                    <span class="event-bullet">●</span>
                    Galeri Kami
                </h2>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <a href="/gallery" class="event-lihat-semua">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Grid Galeri --}}
        <div class="gallery-list">
            @forelse($galeriList as $i => $item)
            @php
                $files      = is_array($item->foto) ? $item->foto : [];
                $videoExts  = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                $coverFile  = null;
                $coverIsVid = false;
                foreach ($files as $f) {
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    if (!in_array($ext, $videoExts)) { $coverFile = $f; break; }
                }
                if (!$coverFile && count($files) > 0) {
                    $coverFile  = $files[0];
                    $coverIsVid = true;
                }
                $coverUrl = $coverFile ? foto_url($coverFile) : asset('assets/logo/logo.jpg');
                if ($coverIsVid && $coverFile) {
                    $coverUrl = video_thumb_url($coverFile);
                }
            @endphp
            <a href="{{ route('gallery.show', $item->uuid) }}" class="gallery-list-card" style="text-decoration:none;position:relative;display:block;">
                <img src="{{ $coverUrl }}" alt="{{ $item->judul }}" onerror="this.src='{{ asset('assets/logo/logo.jpg') }}'">
                <div class="gallery-list-overlay"></div>
                <div class="gallery-list-content">
                    <span class="gallery-list-badge">● {{ $item->tahun }}</span>
                    <h5 class="gallery-list-title" style="color:#fff;">{{ $item->judul }}</h5>
                </div>
            </a>
            @empty
            <p class="text-muted">Belum ada galeri tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== VIDEO ===== --}}
<section id="video" style="padding-top: 2rem;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <span class="section-label">Video</span>
                <h2 class="event-section-title">
                    <span class="event-emoji">🎬</span>
                    <span class="event-bullet">●</span>
                    Video Kami
                </h2>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <a href="/video" class="event-lihat-semua">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Grid Video --}}
        <div class="gallery-list">
            @forelse($videoList as $i => $item)
            @php
                $files      = is_array($item->foto) ? $item->foto : [];
                $videoExts  = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];
                $coverFile  = collect($files)->first(fn($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $videoExts));
                $coverUrl   = $coverFile ? video_thumb_url($coverFile) : asset('assets/logo/logo.jpg');
            @endphp
            <a href="/video" class="gallery-list-card" style="text-decoration:none;position:relative;display:block;">
                <img src="{{ $coverUrl }}" alt="{{ $item->judul }}" onerror="this.src='{{ asset('assets/logo/logo.jpg') }}'">
                <div class="gallery-list-overlay"></div>
                <div style="position:absolute;top:10px;right:10px;background:rgba(214, 51, 132, 0.9);color:#fff;border-radius:20px;padding:4px 10px;font-size:0.75rem;font-weight:700;display:flex;align-items:center;gap:6px;z-index:2;">
                    <i class="fas fa-play" style="font-size:0.7rem;"></i> Video
                </div>
                <div class="gallery-list-content">
                    <span class="gallery-list-badge">● {{ $item->tahun }}</span>
                    <h5 class="gallery-list-title" style="color:#fff;">{{ $item->judul }}</h5>
                </div>
            </a>
            @empty
            <p class="text-muted">Belum ada video tersedia.</p>
            @endforelse
        </div>
    </div>
</section>


{{-- ===== CTA ===== --}}

{{-- ===== JADWAL ===== --}}
<section id="jadwal">
    <div class="container">
        <div class="mb-4">
            <span class="section-label">Jadwal Latihan</span>
            <h2 class="event-section-title">
                <span class="event-emoji">🗓️</span>
                <span class="event-bullet">●</span>
                Jadwal <span>Mingguan</span>
            </h2>
            <p class="mt-2 mb-0" style="font-size:0.9rem; color:#64748b;">Pilih sesi yang sesuai dengan waktu dan level Anda</p>
        </div>

        <div class="row g-3 justify-content-center">

            @forelse($jadwals as $jadwal)
            @php
                $kelasLower = strtolower($jadwal->kelas);
                if (str_contains($kelasLower, 'sparring')) {
                    $badgeClass = 'badge-sparring';
                    $iconClass  = 'icon-sparring';
                    $cardClass  = '';
                } elseif (str_contains($kelasLower, 'interval')) {
                    $badgeClass = 'badge-interval';
                    $iconClass  = 'icon-interval';
                    $cardClass  = '';
                } elseif (str_contains($kelasLower, 'rest') || str_contains($kelasLower, 'istirahat')) {
                    $badgeClass = 'badge-rest';
                    $iconClass  = 'icon-rest';
                    $cardClass  = 'jadwal-card-rest';
                } else {
                    $badgeClass = 'badge-teknik';
                    $iconClass  = 'icon-teknik';
                    $cardClass  = '';
                }
                $jamMulai   = \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i');
                $jamSelesai = $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') : 'Selesai';
            @endphp
            <div class="col-12 col-md-3">
                <div class="jadwal-card-item {{ $cardClass }} h-100">
                    <div class="jadwal-card-header">
                        <span class="jadwal-badge {{ $badgeClass }}">{{ $jadwal->kelas }}</span>
                        <div class="jadwal-card-icon {{ $iconClass }}"><i class="far fa-calendar"></i></div>
                    </div>
                    <h5 class="jadwal-card-day">{{ $jadwal->hari }}</h5>
                    @if($jadwal->pelatih)
                        <p class="jadwal-card-time"><i class="far fa-clock"></i>{{ $jamMulai }} – {{ $jamSelesai }}</p>
                        <p class="jadwal-card-level"><i class="far fa-user"></i>{{ $jadwal->pelatih }}</p>
                    @elseif($jadwal->keterangan)
                        <p class="jadwal-card-time"><i class="fas fa-bed"></i>{{ $jadwal->keterangan }}</p>
                    @else
                        <p class="jadwal-card-time"><i class="far fa-clock"></i>{{ $jamMulai }} – {{ $jamSelesai }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Jadwal latihan belum tersedia.</p>
            </div>
            @endforelse

        </div>

        {{-- CTA bawah jadwal --}}
        <div class="jadwal-cta-bar">
            <div>
                <p style="font-weight:800; font-size:1rem; margin-bottom:2px;">Tertarik Gabung?</p>
                <p style="font-size:0.85rem; color:#64748b; margin:0;">Daftar sekarang dan mulai perjalanan tinju kamu bersama kami.</p>
            </div>
            <a href="https://wa.me/{{ \App\Models\SiteSettings::get('whatsapp') }}?text={{ urlencode('Halo Admin Syifa Boxing Camp 👋

Saya ingin mendaftar sebagai member.

Nama saya: [isi nama]
Umur: [isi umur]
Sudah pernah boxing sebelumnya: [Ya/Tidak]

Mohon info lebih lanjut mengenai pendaftaran dan biayanya. Terima kasih 🙏') }}"
               target="_blank" class="btn-daftar-jadwal">
                <i class="fab fa-whatsapp ms-1"></i> Daftar Sekarang
            </a>
        </div>

    </div>
</section>



@endsection

@push('styles')
<style>
/* ===== MODAL GALERI BERANDA ===== */
#home-lightbox {
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
#home-lightbox.hg-active { display: flex; }

.hg-nav-outer {
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
.hg-nav-outer:hover { background: rgba(255,255,255,0.28); }

.hg-modal {
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

.hg-foto-wrap {
    position: relative;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 280px;
    max-height: 52vh;
    overflow: hidden;
}
.hg-foto-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(24px) brightness(0.35);
    transform: scale(1.12);
    z-index: 0;
}
#hg-img {
    position: relative;
    z-index: 1;
    max-width: 100%;
    max-height: 52vh;
    object-fit: contain;
    display: block;
    border-radius: 2px;
}
.hg-counter-overlay {
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
}

.hg-thumb-strip {
    display: flex;
    gap: 6px;
    padding: 8px 12px;
    background: #222;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.hg-thumb-strip::-webkit-scrollbar { display: none; }
.hg-thumb-strip.hidden { display: none; }

.hg-thumb {
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
.hg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.hg-thumb.active { border-color: #fff; opacity: 1; }
.hg-thumb:hover { opacity: 0.85; }

.hg-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px 16px;
    background: #2a2a2a;
}
.hg-info-left { flex: 1; min-width: 0; }
.hg-judul {
    font-size: 0.95rem;
    font-weight: 700;
    color: #f1f5f9;
    margin: 0 0 4px;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.hg-info-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.hg-tahun { font-size: 0.78rem; color: #94a3b8; }
.hg-juara-badge {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 20px;
    white-space: nowrap;
}
.hg-juara-badge.medal-1 { background: #fef3c7; color: #92400e; }
.hg-juara-badge.medal-2 { background: #e2e8f0; color: #334155; }
.hg-juara-badge.medal-3 { background: #fde8d8; color: #7c3a1a; }
.hg-juara-badge.medal-other { background: #f1f5f9; color: #475569; }
.hg-juara-badge.medal-umum { background: #fef9c3; color: #78350f; }
.hg-juara-badge.medal-petinju { background: #fee2e2; color: #991b1b; }
#hg-juara-badges { display: flex; flex-wrap: wrap; gap: 4px; }

.hg-info-actions { display: flex; gap: 8px; flex-shrink: 0; }
.hg-btn {
    border: none;
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.18s;
}
.hg-btn-unduh {
    background: #3f3f3f;
    color: #f1f5f9;
    display: flex;
    align-items: center;
    gap: 5px;
}
.hg-btn-unduh:hover { background: #555; }
.hg-btn-batal { background: #3f3f3f; color: #94a3b8; }
.hg-btn-batal:hover { background: #555; color: #f1f5f9; }

@media (max-width: 600px) {
    #home-lightbox { padding: 12px 4px; gap: 6px; }
    .hg-nav-outer { width: 36px; height: 36px; font-size: 0.85rem; }
    .hg-modal { border-radius: 12px; }
    .hg-foto-wrap { max-height: 44vh; min-height: 220px; }
    #hg-img { max-height: 44vh; }
    .hg-thumb { width: 44px; height: 34px; }
    .hg-judul { font-size: 0.88rem; }
    .hg-btn { padding: 6px 12px; font-size: 0.78rem; }
}
</style>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush

@push('scripts')
<script>
    // ===== COUNT UP ANIMATION =====
    function countUp(el, target, duration = 1500) {
        const start = 0;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // easing: ease out
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = target;
            }
        }
        requestAnimationFrame(update);
    }

    // Jalankan saat elemen masuk viewport
    const statEls = document.querySelectorAll('#stat-member, #stat-prestasi, #stat-medali');
    let counted = false;

    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !counted) {
                counted = true;
                statEls.forEach(el => {
                    const target = parseInt(el.dataset.target) || 0;
                    countUp(el, target);
                });
                statObserver.disconnect();
            }
        });
    }, { threshold: 0.3 });

    statEls.forEach(el => statObserver.observe(el));
</script>
@endpush
