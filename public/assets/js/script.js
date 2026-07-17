// ===== ACTIVE NAV LINK =====
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            link.classList.add('active');
        }
    });
});

// ===== SMOOTH SCROLL =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

// ===== SCROLL REVEAL — PER SECTION =====
document.addEventListener('DOMContentLoaded', function () {

    // Utility: set style awal + observe
    function prepare(el, styleObj) {
        Object.assign(el.style, styleObj);
        el.dataset.revealed = 'pending';
    }

    function animate(el, delay = 0) {
        el.style.transitionDelay = delay + 's';
        el.style.opacity = '1';
        el.style.transform = 'none';
        el.style.filter = 'none';
        el.dataset.revealed = 'done';
    }

    const baseTransition = 'opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1)';
    const zoomTransition = 'opacity 0.6s ease, transform 0.6s ease, filter 0.6s ease';

    // ── 1. SECTION TITLES & LABELS (semua halaman) ──────────────────────────
    document.querySelectorAll('.section-label, .section-title, .section-subtitle, .divider, .event-section-title').forEach(el => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(20px)',
            transition: baseTransition
        });
    });

    // ── 2. JADWAL — kartu masuk dari bawah, stagger kiri ke kanan ───────────
    document.querySelectorAll('.jadwal-card-item').forEach((el, i) => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(30px)',
            transition: baseTransition
        });
        el.dataset.stagger = i;
    });

    document.querySelectorAll('.jadwal-cta-bar').forEach(el => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(20px)',
            transition: baseTransition
        });
    });

    // ── 3. PELATIH — kartu masuk dari bawah, delay bertahap ─────────────────
    document.querySelectorAll('.pelatih-card, .pelatih-card-luxury').forEach((el, i) => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(40px)',
            transition: 'opacity 0.75s cubic-bezier(0.16,1,0.3,1), transform 0.75s cubic-bezier(0.16,1,0.3,1)'
        });
        el.dataset.stagger = i;
    });

    // ── 4. EVENT — zoom halus + fade ────────────────────────────────────────
    document.querySelectorAll('.event-card-new').forEach((el, i) => {
        prepare(el, {
            opacity: '0',
            transform: 'scale(0.93)',
            filter: 'blur(2px)',
            transition: zoomTransition
        });
        el.dataset.stagger = i;
    });

    // ── 5. PRESTASI — kiri/kanan bergantian ─────────────────────────────────
    document.querySelectorAll('.prestasi-list-card').forEach((el, i) => {
        const fromLeft = i % 2 === 0;
        prepare(el, {
            opacity: '0',
            transform: fromLeft ? 'translateX(-40px)' : 'translateX(40px)',
            transition: 'opacity 0.75s cubic-bezier(0.16,1,0.3,1), transform 0.75s cubic-bezier(0.16,1,0.3,1)'
        });
        el.dataset.stagger = i;
    });

    // ── 6. ABOUT CARDS ───────────────────────────────────────────────────────
    document.querySelectorAll('.about-card').forEach((el, i) => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(30px)',
            transition: baseTransition
        });
        el.dataset.stagger = i;
    });

    document.querySelectorAll('.about-section-card, .about-img-main, .foto-grid-premium .grid-box').forEach((el, i) => {
        prepare(el, {
            opacity: '0',
            transform: 'translateY(24px)',
            transition: baseTransition
        });
        el.dataset.stagger = i;
    });

    // ── OBSERVER ─────────────────────────────────────────────────────────────
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            if (el.dataset.revealed === 'done') return;

            const i = parseInt(el.dataset.stagger || 0);
            const delay = i * 0.09; // stagger 90ms antar item
            animate(el, delay);
            observer.unobserve(el);
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
    });

    // Observe semua elemen yang sudah di-prepare
    document.querySelectorAll('[data-revealed="pending"]').forEach(el => observer.observe(el));

});

// ===== SCROLL REVEAL ANIMATION =====
document.addEventListener('DOMContentLoaded', function () {

    // Elemen yang akan dianimasikan — tambahkan class 'reveal' di HTML
    // atau gunakan selector otomatis di bawah
    const autoTargets = [
        // Hero
        '.hero-badge',
        '.hero-title',
        '.hero-desc',
        '.hero-btns',
        '.hero-stats',

        // Section labels & titles
        '.section-label',
        '.section-title',
        '.section-subtitle',
        '.divider',

        // About singkat
        '.about-section-card',
        '.about-card',
        '.about-img-main',

        // Jadwal
        '.jadwal-card-item',
        '.jadwal-cta-bar',

        // Pelatih
        '.pelatih-card',

        // Event
        '.event-card-new',
        '.event-section-title',

        // Prestasi
        '.prestasi-list-card',

        // Generic reveal
        '.reveal',
    ];

    // Tambahkan class awal (tersembunyi) ke semua elemen target
    const allElements = [];
    autoTargets.forEach(selector => {
        document.querySelectorAll(selector).forEach(el => {
            if (!el.dataset.revealed) {
                el.dataset.revealed = 'pending';
                allElements.push(el);
            }
        });
    });

    // Set style awal: invisible + geser ke bawah
    allElements.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(28px)';
        el.style.transition = 'opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1)';
        el.style.transitionDelay = '0s'; // reset dulu, delay diatur saat masuk viewport
    });

    // Intersection Observer: jalankan animasi saat elemen masuk layar
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;

                // Hitung delay berdasarkan posisi antar elemen sesaudara (stagger)
                const siblings = el.parentElement
                    ? Array.from(el.parentElement.children).filter(c => c.dataset.revealed !== undefined)
                    : [];
                const index = siblings.indexOf(el);
                const stagger = index >= 0 ? index * 0.08 : 0;

                el.style.transitionDelay = `${stagger}s`;
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
                el.dataset.revealed = 'done';

                observer.unobserve(el); // animasi hanya sekali
            }
        });
    }, {
        threshold: 0.12,      // mulai saat 12% elemen terlihat
        rootMargin: '0px 0px -40px 0px', // sedikit sebelum benar-benar masuk
    });

    allElements.forEach(el => observer.observe(el));
});
