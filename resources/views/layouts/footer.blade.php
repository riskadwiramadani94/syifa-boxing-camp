<footer style="background:#ffffff; border-top: 1px solid #eaeaea; padding: 60px 0 0;">
    <div class="container">
        <div class="text-center">

            {{-- Logo --}}
            <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Logo Syifa Boxing Camp" width="100" style="object-fit:contain; display:inline-block; margin-bottom:16px;">

            {{-- Nama Sasana --}}
            <h6 style="font-weight:800; font-size:1.1rem; color:#1a2a4a; margin-bottom:8px;">{{ $siteSettings['nama_sasana'] }}</h6>

            {{-- Deskripsi --}}
            <p style="font-size:0.87rem; color:#64748b; line-height:1.75; max-width:540px; margin:0 auto 20px;">
                {{ \Illuminate\Support\Str::limit($siteSettings['deskripsi'] ?: 'Sasana tinju profesional yang membina dan mengembangkan atlet berprestasi sejak 1998, dari tingkat daerah hingga nasional.', 120) }}
                <a href="/about" style="color:#cc2929; font-weight:600; text-decoration:none; white-space:nowrap;">Baca Selengkapnya →</a>
            </p>

            {{-- Info Kontak --}}
            <div style="display:flex; justify-content:center; flex-wrap:wrap; gap:20px; font-size:0.87rem; color:#64748b; margin-bottom:24px;">
                @if($siteSettings['whatsapp'])
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-phone" style="color:#cc2929;"></i>
                    <span>+{{ $siteSettings['whatsapp'] }}</span>
                </div>
                @endif
                @if($siteSettings['email'])
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-envelope" style="color:#cc2929;"></i>
                    <span>{{ $siteSettings['email'] }}</span>
                </div>
                @endif
                @if($siteSettings['alamat'])
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-map-marker-alt" style="color:#cc2929;"></i>
                    <span>{{ $siteSettings['alamat'] }}</span>
                </div>
                @endif
            </div>

            {{-- Menu --}}
            <nav style="display:flex; justify-content:center; flex-wrap:wrap; gap:28px; margin-bottom:24px;">
                <a href="/" class="footer-nav-link">Beranda</a>
                <a href="/about" class="footer-nav-link">Tentang Kami</a>
                <a href="/gallery" class="footer-nav-link">Galeri</a>
                <a href="/event" class="footer-nav-link">Event</a>
                <a href="/contact" class="footer-nav-link">Kontak</a>
            </nav>

            {{-- Sosmed --}}
            <div class="d-flex justify-content-center gap-2 mb-4">
                @if($siteSettings['instagram'])
                <a href="{{ $siteSettings['instagram'] }}" target="_blank" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                @endif
                @if($siteSettings['tiktok'])
                <a href="{{ $siteSettings['tiktok'] }}" target="_blank" class="social-link" title="TikTok"><i class="fab fa-tiktok"></i></a>
                @endif
                @if($siteSettings['whatsapp'])
                <a href="https://wa.me/{{ $siteSettings['whatsapp'] }}" target="_blank" class="social-link" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                @endif
                @if($siteSettings['facebook'])
                <a href="{{ $siteSettings['facebook'] }}" target="_blank" class="social-link" title="Facebook" style="background:#1877f2;"><i class="fab fa-facebook-f"></i></a>
                @endif
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div style="border-top:1px solid #eaeaea; margin-top:8px; padding:18px 0; text-align:center;">
            <p style="color:#94a3b8; font-size:0.82rem; margin:0;">&copy; {{ date('Y') }} SYIFA Boxing Camp. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script>
    const footerCols = document.querySelectorAll('.footer-col');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    footerCols.forEach(col => observer.observe(col));
</script>
