@extends('layouts.app')

@section('title', 'Beranda - SYIFA Boxing Camp')

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
                        $statTahun2   = \App\Models\SiteSettings::get('stat_tahun', '25+');
                        $statPrestasi2= \App\Models\Galeri::where('kategori', 'pertandingan')->count();
                        $statMedali   = \App\Models\Galeri::where('kategori', 'pertandingan')->whereNotNull('juara')->count();
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
                            <img src="{{ asset('storage/' . $fotoBeranda) }}" alt="Syifa Boxing Camp">
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
                        <img src="{{ $event->foto ? asset('storage/' . $event->foto) : asset('assets/logo/logo.jpg') }}" alt="{{ $event->judul }}">
                    </div>
                    {{-- Konten di bawah --}}
                    <div class="event-card-body">
                        <span class="event-label-status {{ $event->status === 'selesai' ? 'closed' : ($event->status === 'dibuka' ? 'open' : 'soon') }}">
                            {{ $event->status === 'selesai' ? 'Telah Selesai' : ($event->status === 'dibuka' ? 'Pendaftaran Dibuka' : 'Segera Hadir') }}
                        </span>
                        <h5>{{ $event->judul }}</h5>
                        <p class="event-date-text"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</p>
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
                <span class="section-label">Galeri Foto</span>
                <h2 class="event-section-title">
                    <span class="event-emoji">📸</span>
                    <span class="event-bullet">●</span>
                    Galeri Kami
                </h2>
            </div>
            <a href="/gallery" class="event-lihat-semua">
                Lihat semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        {{-- Grid Galeri --}}
        <div class="gallery-list">

            @forelse($galeris->take(8) as $i => $item)
            @php
                $fotos = is_array($item->foto) ? $item->foto : [];
                $fotoUrl = count($fotos) > 0
                    ? asset('storage/' . $fotos[0])
                    : asset('assets/logo/logo.jpg');
            @endphp
            <div class="gallery-list-card" style="cursor:pointer;" onclick="openHomeGaleri({{ $i }})">
                <img src="{{ $fotoUrl }}" alt="{{ $item->judul }}">
                <div class="gallery-list-overlay"></div>
                <div class="gallery-list-content">
                    <span class="gallery-list-badge">● {{ $item->tahun }}</span>
                    <h5 class="gallery-list-title">{{ $item->judul }}</h5>
                </div>
            </div>
            @empty
            <p class="text-muted">Belum ada galeri tersedia.</p>
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
                <p style="font-weight:800; font-size:1rem; margin-bottom:2px;">Tertarik bergabung?</p>
                <p style="font-size:0.85rem; color:#64748b; margin:0;">Daftarkan diri sekarang dan dapatkan <strong style="color:var(--red);">sesi pertama GRATIS!</strong></p>
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

{{-- ===== MODAL GALERI BERANDA ===== --}}
<div id="home-lightbox" onclick="if(event.target===this) closeHomeGaleri()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:520px; max-height:92vh; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.4);">

        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px 8px; border-bottom:1px solid #f0f0f0;">
            <span id="hg-counter" style="font-size:0.82rem; color:#888; font-weight:500;"></span>
            <button onclick="closeHomeGaleri()" style="background:#f1f1f1; border:none; width:32px; height:32px; border-radius:50%; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#555;">&times;</button>
        </div>

        {{-- Foto --}}
        <div style="position:relative; background:#fff; display:flex; align-items:center; justify-content:center; min-height:280px; max-height:50vh; overflow:hidden;">
            <button id="hg-prev" onclick="homeGaleriPrev()" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.35); border:none; color:#fff; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <img id="hg-img" src="" alt="Galeri" style="max-width:100%; max-height:50vh; object-fit:contain; padding:16px; display:block;">
            <button id="hg-next" onclick="homeGaleriNext()" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.35); border:none; color:#fff; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:2;">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- Info card --}}
        <div style="padding:16px 20px 20px; border-top:1px solid #f0f0f0; overflow-y:auto; max-height:180px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                <div>
                    <h5 id="hg-judul" style="font-size:1rem; font-weight:700; color:#1e293b; margin:0 0 4px; line-height:1.4;"></h5>
                    <span id="hg-tahun" style="font-size:0.78rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;"></span>
                </div>
                <span id="hg-juara" style="display:none; background:#fef3c7; color:#92400e; font-size:0.78rem; font-weight:700; padding:4px 12px; border-radius:20px; white-space:nowrap; flex-shrink:0;"></span>
            </div>
            <p id="hg-keterangan" style="display:none; font-size:0.85rem; color:#64748b; margin:10px 0 0; line-height:1.6;"></p>
        </div>

    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush

@push('scripts')
<script>
    // ===== MODAL GALERI BERANDA =====
    const homeGaleriData = @json($galeriData);
    let hgCurrent = null;
    let hgFotoIdx = 0;

    function openHomeGaleri(index) {
        hgCurrent = homeGaleriData[index];
        hgFotoIdx = 0;
        showHomeGaleri();
        const lb = document.getElementById('home-lightbox');
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function showHomeGaleri() {
        const fotos      = hgCurrent.fotos;
        const total      = fotos.length;
        const juara      = hgCurrent.juara;
        const keterangan = hgCurrent.keterangan || '';

        document.getElementById('hg-img').src        = fotos[hgFotoIdx];
        document.getElementById('hg-judul').textContent = hgCurrent.judul;
        document.getElementById('hg-tahun').textContent = hgCurrent.tahun;
        document.getElementById('hg-counter').textContent = total > 1 ? (hgFotoIdx + 1) + ' / ' + total + ' foto' : '';

        const showNav = total > 1;
        document.getElementById('hg-prev').style.display = showNav ? 'flex' : 'none';
        document.getElementById('hg-next').style.display = showNav ? 'flex' : 'none';

        const juaraEl = document.getElementById('hg-juara');
        if (juara) { juaraEl.textContent = '🏆 Juara ' + juara; juaraEl.style.display = 'inline-block'; }
        else { juaraEl.style.display = 'none'; }

        const ketEl = document.getElementById('hg-keterangan');
        if (keterangan) { ketEl.textContent = keterangan; ketEl.style.display = 'block'; }
        else { ketEl.style.display = 'none'; }
    }

    function closeHomeGaleri() {
        document.getElementById('home-lightbox').style.display = 'none';
        document.body.style.overflow = '';
        hgCurrent = null; hgFotoIdx = 0;
    }

    function homeGaleriPrev() {
        hgFotoIdx = (hgFotoIdx - 1 + hgCurrent.fotos.length) % hgCurrent.fotos.length;
        showHomeGaleri();
    }

    function homeGaleriNext() {
        hgFotoIdx = (hgFotoIdx + 1) % hgCurrent.fotos.length;
        showHomeGaleri();
    }

    document.addEventListener('keydown', function(e) {
        if (document.getElementById('home-lightbox').style.display !== 'flex') return;
        if (e.key === 'ArrowLeft')  homeGaleriPrev();
        if (e.key === 'ArrowRight') homeGaleriNext();
        if (e.key === 'Escape')     closeHomeGaleri();
    });
</script>
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
