<footer style="background:#ffffff; border-top: 1px solid #eaeaea; padding: 60px 0 0;">
    <div class="container">
        <div class="row g-4 align-items-start">

            {{-- Kolom Kiri: Logo + Nama + Deskripsi + Sosmed --}}
            <div class="col-lg-5 col-md-4 col-12 footer-col" style="padding-top:0;">
                <h6 style="font-weight:700; font-size:0.82rem; color:#1a2a4a; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:16px;">Tentang Kami</h6>
                <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Logo Syifa Boxing Camp" width="120" style="object-fit:contain; display:block; margin-bottom:10px;">
                <h6 style="font-weight:800; font-size:1rem; color:#1a2a4a; margin-bottom:8px;">{{ $siteSettings['nama_sasana'] }}</h6>
                <p style="font-size:0.87rem; color:#64748b; line-height:1.75; margin-bottom:16px;">
                    {{ \Illuminate\Support\Str::limit($siteSettings['deskripsi'] ?: 'Sasana tinju profesional yang membina dan mengembangkan atlet berprestasi sejak 1998, dari tingkat daerah hingga nasional.', 100) }}
                    <a href="/about" style="color:#cc2929; font-weight:600; text-decoration:none; white-space:nowrap;">Selengkapnya →</a>
                </p>
                {{-- Sosmed --}}
                <div class="d-flex gap-2">
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

            {{-- Kolom Tengah: Nav Links --}}
            <div class="col-lg-3 col-md-3 col-6 footer-col" style="padding-right: 8px;">
                <h6 style="font-weight:700; font-size:0.82rem; color:#1a2a4a; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:16px;">Menu</h6>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                    <li><a href="/" class="footer-nav-link">Beranda</a></li>
                    <li><a href="/about" class="footer-nav-link">Tentang Kami</a></li>
                    <li><a href="/gallery" class="footer-nav-link">Galeri</a></li>
                    <li><a href="/event" class="footer-nav-link">Event</a></li>
                    <li><a href="/contact" class="footer-nav-link">Kontak</a></li>
                </ul>
            </div>

            {{-- Kolom Kanan: Info Kontak --}}
            <div class="col-lg-3 col-md-5 col-6 footer-col" style="padding-left: 8px;">
                <h6 style="font-weight:700; font-size:0.82rem; color:#1a2a4a; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:16px;">Info Kontak</h6>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    @if($siteSettings['whatsapp'])
                    <div style="display:flex; align-items:flex-start; gap:12px; font-size:0.87rem; color:#64748b;">
                        <i class="fas fa-phone" style="color:#cc2929; width:16px; margin-top:2px;"></i>
                        <span>+{{ $siteSettings['whatsapp'] }}</span>
                    </div>
                    @endif
                    @if($siteSettings['email'])
                    <div style="display:flex; align-items:flex-start; gap:12px; font-size:0.87rem; color:#64748b;">
                        <i class="fas fa-envelope" style="color:#cc2929; width:16px; margin-top:2px;"></i>
                        <span>{{ $siteSettings['email'] }}</span>
                    </div>
                    @endif
                    @if($siteSettings['alamat'])
                    <div style="display:flex; align-items:flex-start; gap:12px; font-size:0.87rem; color:#64748b;">
                        <i class="fas fa-map-marker-alt" style="color:#cc2929; width:16px; margin-top:2px;"></i>
                        <span>{{ $siteSettings['alamat'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div style="border-top:1px solid #eaeaea; margin-top:48px; padding:18px 0; text-align:center;">
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
