@extends('layouts.app')

@section('title', 'Jadwal Latihan - Syifa Boxing Camp')
@section('meta_description', 'Cek jadwal latihan rutin di Syifa Boxing Camp. Tersedia berbagai kelas tinju untuk pemula hingga atlet profesional.')
@section('og_title', 'Jadwal Latihan - Syifa Boxing Camp')
@section('og_description', 'Cek jadwal latihan rutin di Syifa Boxing Camp. Tersedia berbagai kelas tinju untuk pemula hingga atlet profesional.')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="sched-hero-section">
    <div class="container">
        <div class="sched-hero-inner">
            <h1 class="sched-hero-title">
                Jadwal<br>
                <span class="sched-hero-accent">Latihan.</span>
            </h1>
            <p class="sched-hero-desc">
                Pilih sesi latihan yang sesuai dengan waktu dan tingkat kemampuan Anda. Kami siap membimbing Anda menuju prestasi terbaik.
            </p>
        </div>
    </div>
</section>

{{-- ===== JADWAL MINGGUAN ===== --}}
<section class="sched-table-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Jadwal Mingguan</span>
            <h2 class="section-title">Program <span>Latihan</span></h2>
            <div class="divider divider-center"></div>
        </div>

        <div class="table-responsive sched-table-wrap">
            <table class="table sched-table text-center align-middle">
                <thead>
                    <tr>
                        <th class="sched-th-time">Waktu</th>
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jumat</th>
                        <th>Sabtu</th>
                        <th>Minggu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="sched-td-time">06.00 – 08.00</td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td>–</td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td>–</td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td><span class="sched-badge sched-badge-menengah">Menengah</span></td>
                        <td>–</td>
                    </tr>
                    <tr>
                        <td class="sched-td-time">08.00 – 10.00</td>
                        <td>–</td>
                        <td><span class="sched-badge sched-badge-menengah">Menengah</span></td>
                        <td>–</td>
                        <td><span class="sched-badge sched-badge-menengah">Menengah</span></td>
                        <td>–</td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td><span class="sched-badge sched-badge-lanjutan">Lanjutan</span></td>
                    </tr>
                    <tr>
                        <td class="sched-td-time">16.00 – 18.00</td>
                        <td><span class="sched-badge sched-badge-lanjutan">Lanjutan</span></td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td><span class="sched-badge sched-badge-lanjutan">Lanjutan</span></td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td><span class="sched-badge sched-badge-menengah">Menengah</span></td>
                        <td>–</td>
                        <td>–</td>
                    </tr>
                    <tr>
                        <td class="sched-td-time">18.00 – 20.00</td>
                        <td><span class="sched-badge sched-badge-menengah">Menengah</span></td>
                        <td><span class="sched-badge sched-badge-lanjutan">Lanjutan</span></td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td><span class="sched-badge sched-badge-lanjutan">Lanjutan</span></td>
                        <td><span class="sched-badge sched-badge-pemula">Pemula</span></td>
                        <td>–</td>
                        <td>–</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Keterangan --}}
        <div class="sched-legend">
            <span class="sched-legend-item">
                <span class="sched-badge sched-badge-pemula">●</span> Kelas Pemula
            </span>
            <span class="sched-legend-item">
                <span class="sched-badge sched-badge-menengah">●</span> Kelas Menengah
            </span>
            <span class="sched-legend-item">
                <span class="sched-badge sched-badge-lanjutan">●</span> Kelas Lanjutan
            </span>
        </div>
    </div>
</section>

{{-- ===== DESKRIPSI KELAS ===== --}}
<section class="sched-kelas-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Tentang Kelas</span>
            <h2 class="section-title">Deskripsi <span>Kelas</span></h2>
            <div class="divider divider-center"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="sched-kelas-card">
                    <div class="sched-kelas-icon sched-icon-pemula">
                        <i class="fas fa-fist-raised"></i>
                    </div>
                    <span class="sched-badge sched-badge-pemula mb-2 d-inline-block">Pemula</span>
                    <h5 class="sched-kelas-title">Kelas Pemula</h5>
                    <p class="sched-kelas-desc">
                        Cocok untuk yang baru memulai. Fokus pada teknik dasar, <em>footwork</em>, dan stamina awal.
                    </p>
                    <p class="sched-kelas-meta">
                        <i class="fas fa-clock me-1"></i> Durasi: 2 jam/sesi
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="sched-kelas-card">
                    <div class="sched-kelas-icon sched-icon-menengah">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <span class="sched-badge sched-badge-menengah mb-2 d-inline-block">Menengah</span>
                    <h5 class="sched-kelas-title">Kelas Menengah</h5>
                    <p class="sched-kelas-desc">
                        Untuk yang sudah memahami dasar. Fokus pada kombinasi pukulan, defense, dan sparring ringan.
                    </p>
                    <p class="sched-kelas-meta">
                        <i class="fas fa-clock me-1"></i> Durasi: 2 jam/sesi
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="sched-kelas-card">
                    <div class="sched-kelas-icon sched-icon-lanjutan">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <span class="sched-badge sched-badge-lanjutan mb-2 d-inline-block">Lanjutan</span>
                    <h5 class="sched-kelas-title">Kelas Lanjutan</h5>
                    <p class="sched-kelas-desc">
                        Untuk atlet berpengalaman. Fokus pada strategi pertandingan, sparring penuh, dan persiapan kompetisi.
                    </p>
                    <p class="sched-kelas-meta">
                        <i class="fas fa-clock me-1"></i> Durasi: 2 jam/sesi
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA DAFTAR ===== --}}
<section class="sched-cta-section">
    <div class="container text-center">
        <h2 class="sched-cta-title">Siap Bergabung?</h2>
        <p class="sched-cta-desc">Daftarkan diri Anda sekarang dan mulai perjalanan tinju bersama Syifa Boxing Camp.</p>
        <a href="https://wa.me/{{ \App\Models\SiteSettings::get('whatsapp') }}?text={{ urlencode('Halo Admin Syifa Boxing Camp 👋

Saya ingin mendaftar sebagai member.

Nama saya: [isi nama]
Umur: [isi umur]
Sudah pernah boxing sebelumnya: [Ya/Tidak]

Mohon info lebih lanjut mengenai pendaftaran dan biayanya. Terima kasih 🙏') }}"
           target="_blank"
           class="btn-primary-custom btn-glow">
            <i class="fab fa-whatsapp"></i> Daftar Sekarang
        </a>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================
   SYIFA BOXING CAMP — SCHEDULE PAGE
============================================================ */

/* ===== HERO ===== */
.sched-hero-section {
    padding: 50px 0 40px;
    background: #ffffff;
    text-align: center;
}

.sched-hero-inner {
    max-width: 600px;
    margin: 0 auto;
}

.sched-hero-title {
    font-size: clamp(2.8rem, 6vw, 5rem);
    font-weight: 900;
    color: var(--navy);
    line-height: 1.15;
    margin-bottom: 20px;
    letter-spacing: -1px;
    animation: schedFadeUp 0.7s cubic-bezier(0.16,1,0.3,1) 0.1s both;
}

.sched-hero-accent {
    color: var(--red);
    display: block;
}

.sched-hero-desc {
    font-size: 0.95rem;
    color: var(--gray);
    line-height: 1.7;
    margin: 0 auto;
    animation: schedFadeUp 0.7s cubic-bezier(0.16,1,0.3,1) 0.28s both;
}

@keyframes schedFadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ===== TABEL ===== */
.sched-table-section {
    padding: 40px 0 60px;
    background: #ffffff;
}

.sched-table-wrap {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(26,42,74,0.07);
    border: 1px solid var(--border);
}

.sched-table {
    margin: 0;
    background: #ffffff;
}

.sched-table thead tr {
    background: var(--navy);
}

.sched-table thead th {
    color: #ffffff;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 14px 10px;
    border: none;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.sched-th-time {
    background: var(--red) !important;
    width: 130px;
}

.sched-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}

.sched-table tbody tr:hover {
    background: var(--bg);
}

.sched-table tbody td {
    padding: 12px 8px;
    font-size: 0.85rem;
    color: var(--gray);
    border: none;
    border-right: 1px solid var(--border);
}

.sched-table tbody td:last-child {
    border-right: none;
}

.sched-td-time {
    font-weight: 700;
    color: var(--navy) !important;
    font-size: 0.82rem !important;
    font-family: 'Courier New', monospace;
    white-space: nowrap;
}

/* ===== BADGE ===== */
.sched-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.sched-badge-pemula {
    background: #fee2e2;
    color: #b91c1c;
}

.sched-badge-menengah {
    background: #fef3c7;
    color: #92400e;
}

.sched-badge-lanjutan {
    background: #d1fae5;
    color: #065f46;
}

/* ===== LEGENDA ===== */
.sched-legend {
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.sched-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    color: var(--gray-dark);
    font-weight: 500;
}

/* ===== KELAS CARDS ===== */
.sched-kelas-section {
    padding: 60px 0 80px;
    background: var(--bg);
}

.sched-kelas-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 2px 16px rgba(26,42,74,0.06);
    border: 1px solid var(--border);
    height: 100%;
    transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.sched-kelas-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(26,42,74,0.12);
}

.sched-kelas-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 16px;
}

.sched-icon-pemula   { background: #fee2e2; color: #b91c1c; }
.sched-icon-menengah { background: #fef3c7; color: #92400e; }
.sched-icon-lanjutan { background: #d1fae5; color: #065f46; }

.sched-kelas-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 10px;
}

.sched-kelas-desc {
    font-size: 0.88rem;
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 12px;
}

.sched-kelas-meta {
    font-size: 0.82rem;
    color: var(--gray);
    margin: 0;
}

.sched-kelas-meta i {
    color: var(--red);
}

/* ===== CTA ===== */
.sched-cta-section {
    padding: 80px 0;
    background: var(--navy);
}

.sched-cta-title {
    font-size: 2.2rem;
    font-weight: 900;
    color: #ffffff;
    margin-bottom: 14px;
}

.sched-cta-desc {
    font-size: 0.97rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 30px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .sched-hero-title { font-size: 2.4rem; }
    .sched-table thead th,
    .sched-table tbody td { padding: 8px 5px; font-size: 0.7rem; }
    .sched-badge { padding: 3px 6px; font-size: 0.65rem; }
    .sched-td-time { font-size: 0.7rem !important; }
    .sched-legend { gap: 12px; }
    .sched-cta-title { font-size: 1.6rem; }
}

@media (max-width: 480px) {
    /* Di layar sangat kecil, sembunyikan kolom Minggu untuk hemat ruang */
    .sched-table thead th:last-child,
    .sched-table tbody td:last-child { display: none; }
    .sched-table thead th,
    .sched-table tbody td { padding: 7px 4px; font-size: 0.65rem; }
    .sched-badge { display: block; font-size: 0.6rem; padding: 2px 5px; }
}
</style>
@endpush
