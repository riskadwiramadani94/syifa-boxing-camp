@extends('layouts.app')

@section('title', 'Event - Syifa Boxing Camp')
@section('meta_description', 'Ikuti berbagai event dan pertandingan tinju yang diselenggarakan oleh Syifa Boxing Camp. Cek jadwal dan daftarkan dirimu sekarang!')
@section('og_title', 'Event Tinju - Syifa Boxing Camp')
@section('og_description', 'Ikuti berbagai event dan pertandingan tinju yang diselenggarakan oleh Syifa Boxing Camp. Cek jadwal dan daftarkan dirimu sekarang!')
@section('og_image', asset('assets/images/og-image.png'))

@section('content')

{{-- ===== HERO PUTIH BESAR (gaya KONI) ===== --}}
<section class="event-hero-section">
    <div class="container">
        <div class="event-hero-inner">
            <h1 class="event-hero-title">
                Jelajahi Event<br>
                <span class="event-hero-accent">Tinju Syifa.</span>
            </h1>
            <p class="event-hero-desc">
                Dapatkan informasi terbaru tentang event, turnamen, dan pertandingan tinju yang diselenggarakan oleh Syifa Boxing Camp.
            </p>
        </div>
    </div>
</section>

{{-- ===== DAFTAR EVENT ===== --}}
<section class="event-list-section">
    <div class="container">
        <div class="row g-4 justify-content-center">

            @forelse($events as $event)
            <div class="col-md-4 event-animate" style="--delay: {{ $loop->index * 0.15 }}s">
                <div class="event-koni-card">

                    {{-- Gambar --}}
                    <div class="event-koni-img">
                        <img src="{{ foto_url($event->foto, asset('assets/logo/logo.jpg')) }}"
                             alt="{{ $event->judul }}">
                    </div>

                    {{-- Body --}}
                    <div class="event-koni-body">
                        <span class="event-koni-status {{ $event->status === 'selesai' ? 'closed' : ($event->status === 'dibuka' ? 'open' : 'soon') }}">
                            {{ $event->status === 'selesai' ? 'Telah Selesai' : ($event->status === 'dibuka' ? 'Pendaftaran Dibuka' : 'Segera Hadir') }}
                        </span>

                        <h5 class="event-koni-title">{{ $event->judul }}</h5>

                        <p class="event-koni-meta">
                            <i class="far fa-calendar-alt"></i>
                            @if($event->tanggal_mulai?->format('d M Y') === $event->tanggal_selesai?->format('d M Y'))
                                {{ $event->tanggal_mulai->translatedFormat('d F Y') }}
                            @else
                                {{ $event->tanggal_mulai->translatedFormat('d F Y') }} – {{ $event->tanggal_selesai->translatedFormat('d F Y') }}
                            @endif
                        </p>
                        <p class="event-koni-meta">
                            <i class="fas fa-trophy"></i>
                            {{ $event->lokasi }}
                        </p>


                    </div>

                    {{-- Footer tombol --}}
                    <div class="event-koni-footer">
                        <a href="{{ route('event.show', $event->slug) }}" class="event-koni-btn">Lihat Event →</a>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times fa-3x mb-3" style="color:#cbd5e1;"></i>
                <p class="text-muted">Belum ada event tersedia saat ini.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/event.css') }}">
@endpush

@push('scripts')
<script>
    const eventEls = document.querySelectorAll('.event-animate');
    const eventObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                eventObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    eventEls.forEach(el => eventObserver.observe(el));
</script>
@endpush
