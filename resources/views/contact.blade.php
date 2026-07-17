@extends('layouts.app')

@section('title', 'Kontak - Syifa Boxing Camp')
@section('meta_description', 'Hubungi Syifa Boxing Camp untuk informasi pendaftaran, jadwal latihan, dan kerjasama. Kami siap membantu kamu!')
@section('og_title', 'Hubungi Kami - Syifa Boxing Camp')
@section('og_description', 'Hubungi Syifa Boxing Camp untuk informasi pendaftaran, jadwal latihan, dan kerjasama. Kami siap membantu kamu!')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO ===== --}}
<section class="contact-hero-section">
    <div class="container">
        <div class="contact-hero-inner">
            <h1 class="contact-hero-title c-reveal c-fadeup" style="--cd:0s">
                Hubungi Syifa,<br>
                <span class="contact-hero-accent">Kami Siap Membantu.</span>
            </h1>
            <p class="contact-hero-desc c-reveal c-fade" style="--cd:0.2s">
                Jika Anda memiliki pertanyaan seputar latihan, pendaftaran, atau ingin bergabung bersama kami, jangan ragu untuk menghubungi kami.
            </p>
        </div>
    </div>
</section>

{{-- ===== CARD INFO ===== --}}
<section class="contact-cards-section">
    <div class="container">
        <div class="contact-cards-grid">

            {{-- WhatsApp --}}
            <div class="contact-info-card c-reveal c-fadeleft" style="--cd:0s">
                <div class="contact-info-icon" style="background:#dcfce7;">
                    <i class="fab fa-whatsapp" style="color:#16a34a;"></i>
                </div>
                <h5 class="contact-info-title">WhatsApp</h5>
                <p class="contact-info-desc">Hubungi kami via WhatsApp untuk respons cepat seputar latihan dan pendaftaran</p>
                <a href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" class="contact-info-btn" style="background:#dcfce7; color:#16a34a;">Chat WhatsApp</a>
            </div>

            {{-- Lokasi Sasana --}}
            <div class="contact-info-card c-reveal c-fadeup" style="--cd:0.15s">
                <div class="contact-info-icon" style="background:#fce4e4;">
                    <i class="fas fa-map-marker-alt" style="color:#cc2929;"></i>
                </div>
                <h5 class="contact-info-title">Lokasi Sasana</h5>
                <p class="contact-info-desc">Kunjungi kami langsung di sasana untuk informasi lebih lengkap</p>
                @if($settings['maps_url'])
                <a href="{{ $settings['maps_url'] }}" target="_blank" class="contact-info-link" style="color:#cc2929;">
                    <i class="fas fa-map-marker-alt me-1"></i> {{ $settings['nama_tempat_latihan'] ?: $settings['alamat'] }}
                </a>
                <a href="{{ $settings['maps_url'] }}" target="_blank" class="contact-info-btn" style="background:#fce4e4; color:#cc2929;">Buka Google Maps</a>
                @else
                <p class="contact-info-link" style="color:#cc2929;">
                    <i class="fas fa-map-marker-alt me-1"></i> {{ $settings['nama_tempat_latihan'] ?: $settings['alamat'] }}
                </p>
                @endif
            </div>

            {{-- Instagram --}}
            @if($settings['instagram'])
            <div class="contact-info-card c-reveal c-faderight" style="--cd:0.3s">
                <div class="contact-info-icon" style="background:#e0e7ff;">
                    <i class="fab fa-instagram" style="color:#4f46e5;"></i>
                </div>
                <h5 class="contact-info-title">Instagram Resmi</h5>
                <p class="contact-info-desc">Ikuti Instagram kami untuk update terbaru event, prestasi atlet, dan kegiatan sasana</p>
                <a href="{{ $settings['instagram'] }}" target="_blank" class="contact-info-link" style="color:#4f46e5;">
                    <i class="fab fa-instagram me-1"></i> {{ $settings['instagram'] }}
                </a>
                <a href="{{ $settings['instagram'] }}" target="_blank" class="contact-info-btn" style="background:#e0e7ff; color:#4f46e5;">Follow Instagram</a>
            </div>
            @endif

        </div>
    </div>
</section>

{{-- ===== FORM ===== --}}
<section class="contact-form-section">
    <div class="container">
        <div class="contact-form-wrap c-reveal c-fadeup" style="--cd:0.1s">
            <h2 class="contact-form-title">Kirim Pesan untuk Syifa Boxing Camp</h2>

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="contact-form-row">
                    <div class="contact-form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" placeholder="Masukan nama anda"
                               value="{{ old('nama') }}" required>
                    </div>
                    <div class="contact-form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Masukan email anda"
                               value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="contact-form-group">
                    <label>Pesan Anda</label>
                    <textarea name="pesan" rows="6" placeholder="Masukan pesan anda" required>{{ old('pesan') }}</textarea>
                </div>
                <div class="contact-form-footer">
                    <button type="submit" class="contact-submit-btn">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@push('scripts')
<script>
    const cRevealEls = document.querySelectorAll('.c-reveal');
    const cObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('c-visible');
                cObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    cRevealEls.forEach(el => cObserver.observe(el));
</script>
@endpush
