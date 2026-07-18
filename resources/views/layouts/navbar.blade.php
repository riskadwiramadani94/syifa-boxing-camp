<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Logo Syifa Boxing Camp" height="100" style="width:auto;">
            <div class="navbar-brand-text">
                <span class="navbar-brand-sub">Sasana Tinju</span>
                <span class="navbar-brand-main">SYIFA<br>BOXING CAMP</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('gallery') ? 'active' : '' }}" href="/gallery">Galeri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('video') ? 'active' : '' }}" href="/video">Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('event') ? 'active' : '' }}" href="/event">Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">Kontak</a>
                </li>
            </ul>


        </div>

    </div>
</nav>

<script>
    (function () {
        const navbarEl = document.getElementById('navbarNav');
        if (!navbarEl) return;

        function closeNavbar() {
            if (navbarEl.classList.contains('show')) {
                // pakai Bootstrap collapse API
                const bsCollapse = bootstrap.Collapse.getInstance(navbarEl);
                if (bsCollapse) bsCollapse.hide();
                else navbarEl.classList.remove('show');
            }
        }

        // 1. Tutup saat klik di luar navbar
        document.addEventListener('click', function (e) {
            const navbar = document.querySelector('.navbar');
            if (!navbar.contains(e.target)) {
                closeNavbar();
            }
        });

        // 2. Tutup saat scroll
        window.addEventListener('scroll', function () {
            closeNavbar();
        }, { passive: true });
    })();
</script>
