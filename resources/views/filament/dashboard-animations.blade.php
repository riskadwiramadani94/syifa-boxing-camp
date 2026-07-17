<style>
    /* ============================================
       SVG GUARD — Cegah SVG membesar di custom widget
    ============================================ */
    .fi-wi .fi-section svg:not([class*="w-"]):not([style*="width"]) {
        width: 1.25rem !important;
        height: 1.25rem !important;
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
    }

    /* Pastikan semua SVG inline di dalam item card tidak overflow */
    .fi-wi .fi-section .space-y-2 svg,
    .fi-wi .fi-section .space-y-4 svg {
        flex-shrink: 0;
        max-width: 2rem;
        max-height: 2rem;
    }

    /* ============================================
       CHART FIX — Bar chart tidak berantakan
    ============================================ */
    /* Paksa canvas chart memakai ukuran wajar */
    .fi-wi-chart canvas {
        max-height: 280px !important;
        height: 280px !important;
        width: 100% !important;
        animation: dashFadeUp 0.7s ease both;
        animation-delay: 0.4s;
    }

    /* Container chart beri fixed height */
    .fi-wi-chart .fi-section-content {
        min-height: 320px;
    }

    /* Legend dot ukuran normal */
    .fi-wi-chart canvas + div,
    .fi-wi-chart [data-chart-legend] {
        font-size: 0.8rem !important;
    }

    /* ============================================
       FADE + SLIDE UP — Semua Widget
    ============================================ */
    @keyframes dashFadeUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dashFadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* Semua widget container */
    .fi-wi {
        animation: dashFadeUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    /* Stagger delay tiap widget */
    .fi-wi:nth-child(1) { animation-delay: 0.05s; }
    .fi-wi:nth-child(2) { animation-delay: 0.12s; }
    .fi-wi:nth-child(3) { animation-delay: 0.20s; }
    .fi-wi:nth-child(4) { animation-delay: 0.28s; }
    .fi-wi:nth-child(5) { animation-delay: 0.36s; }
    .fi-wi:nth-child(6) { animation-delay: 0.44s; }

    /* ============================================
       HOVER SCALE — Stat Cards
    ============================================ */
    .fi-stats-overview-stat {
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                    box-shadow 0.22s ease,
                    border-color 0.22s ease !important;
        cursor: default;
    }

    .fi-stats-overview-stat:hover {
        transform: translateY(-4px) scale(1.025) !important;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35),
                    0 4px 12px rgba(245, 158, 11, 0.12) !important;
    }

    /* ============================================
       HOVER SCALE — Chart & Table & Custom Widget
    ============================================ */
    .fi-wi-chart .fi-section,
    .fi-wi-table .fi-section,
    .fi-wi .fi-section {
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                    box-shadow 0.22s ease !important;
    }

    .fi-wi-chart .fi-section:hover,
    .fi-wi-table .fi-section:hover,
    .fi-wi .fi-section:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.28) !important;
    }

    /* ============================================
       GLOW PULSE — Nilai Angka Stats
    ============================================ */
    @keyframes valuePop {
        0%   { transform: scale(0.8); opacity: 0; }
        65%  { transform: scale(1.08); opacity: 1; }
        100% { transform: scale(1);   opacity: 1; }
    }

    .fi-stats-overview-stat-value {
        display: inline-block;
        animation: valuePop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        animation-delay: 0.3s;
    }

    /* ============================================
       TABLE ROWS — Fade in bertahap
    ============================================ */
    @keyframes rowSlide {
        from { opacity: 0; transform: translateX(-12px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .fi-wi-table table tbody tr {
        animation: rowSlide 0.4s ease both;
    }

    .fi-wi-table table tbody tr:nth-child(1) { animation-delay: 0.50s; }
    .fi-wi-table table tbody tr:nth-child(2) { animation-delay: 0.62s; }
    .fi-wi-table table tbody tr:nth-child(3) { animation-delay: 0.74s; }
    .fi-wi-table table tbody tr:nth-child(4) { animation-delay: 0.86s; }
    .fi-wi-table table tbody tr:nth-child(5) { animation-delay: 0.98s; }

    /* Hover row highlight */
    .fi-wi-table table tbody tr {
        transition: background-color 0.15s ease;
    }

    /* ============================================
       HEADING — Animasi Section Heading
    ============================================ */
    .fi-section-header {
        animation: dashFadeIn 0.5s ease both;
        animation-delay: 0.2s;
    }

    /* ============================================
       PULSE DOT — Indicator aktif (dipakai di blade)
    ============================================ */
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(1.4); }
    }

    .animate-pulse {
        animation: pulseDot 1.8s ease-in-out infinite;
    }

    /* ============================================
       BADGE — Animasi muncul
    ============================================ */
    .fi-badge {
        animation: dashFadeIn 0.35s ease both;
        animation-delay: 0.6s;
    }

    /* ============================================
       STAT DESCRIPTION ICON — Fade in dari kiri
    ============================================ */
    @keyframes iconSlideIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .fi-stats-overview-stat-description {
        animation: iconSlideIn 0.45s ease both;
        animation-delay: 0.55s;
    }

    /* ============================================
       CHART LEGEND — Fade in dari atas
    ============================================ */
    .fi-wi-chart .fi-section-header-heading {
        animation: dashFadeUp 0.4s ease both;
        animation-delay: 0.3s;
    }

    /* ============================================
       EVENT & JADWAL WIDGET — Teks terlihat
       (Filament v3 light mode: teks harus gelap)
    ============================================ */

    /* Override warna teks putih agar terlihat di light mode */
    .fi-wi .fi-section p.text-white,
    .fi-wi .fi-section .text-white {
        color: #1e293b !important;
    }

    .fi-wi .fi-section p.text-gray-400,
    .fi-wi .fi-section .text-gray-400 {
        color: #64748b !important;
    }

    .fi-wi .fi-section .text-amber-400 {
        color: #d97706 !important;
    }

    .fi-wi .fi-section .text-blue-400 {
        color: #2563eb !important;
    }

    .fi-wi .fi-section .text-green-400 {
        color: #16a34a !important;
    }

    /* Background card item jadwal & event */
    .fi-wi .fi-section .bg-white\/5 {
        background-color: rgba(0, 0, 0, 0.04) !important;
    }

    .fi-wi .fi-section .border-white\/10 {
        border-color: rgba(0, 0, 0, 0.1) !important;
    }

    .fi-wi .fi-section .bg-amber-500\/10 {
        background-color: rgba(245, 158, 11, 0.08) !important;
    }

    .fi-wi .fi-section .border-amber-500\/20 {
        border-color: rgba(245, 158, 11, 0.25) !important;
    }

    .fi-wi .fi-section .hover\:bg-amber-500\/20:hover {
        background-color: rgba(245, 158, 11, 0.15) !important;
    }

    .fi-wi .fi-section .bg-green-500\/10 {
        background-color: rgba(34, 197, 94, 0.08) !important;
    }

    .fi-wi .fi-section .border-green-500\/20 {
        border-color: rgba(34, 197, 94, 0.25) !important;
    }

    .fi-wi .fi-section .bg-blue-500\/10 {
        background-color: rgba(59, 130, 246, 0.08) !important;
    }

    .fi-wi .fi-section .border-blue-500\/20 {
        border-color: rgba(59, 130, 246, 0.25) !important;
    }

    .fi-wi .fi-section .bg-amber-500\/20 {
        background-color: rgba(245, 158, 11, 0.12) !important;
    }

    .fi-wi .fi-section .bg-green-500\/20 {
        background-color: rgba(34, 197, 94, 0.12) !important;
    }

    .fi-wi .fi-section .bg-blue-500\/20 {
        background-color: rgba(59, 130, 246, 0.12) !important;
    }

    .fi-wi .fi-section .text-amber-400.bg-amber-500\/10 {
        background-color: rgba(245, 158, 11, 0.1) !important;
    }

    /* Badge status event */
    .fi-wi .fi-section .text-green-400.bg-green-500\/10 {
        background-color: rgba(34, 197, 94, 0.1) !important;
    }

    .fi-wi .fi-section .text-blue-400.bg-blue-500\/10 {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }

    .fi-wi .fi-section .text-gray-400.bg-gray-700\/50 {
        background-color: rgba(0, 0, 0, 0.07) !important;
        color: #64748b !important;
    }

    /* Border separator */
    .fi-wi .fi-section .border-t.border-white\/10 {
        border-color: rgba(0, 0, 0, 0.08) !important;
    }

    /* Dot animasi pulseDot warna amber */
    .fi-wi .fi-section .bg-amber-400 {
        background-color: #f59e0b !important;
    }

    .fi-wi .fi-section .bg-blue-400 {
        background-color: #3b82f6 !important;
    }

</style>

<script>
    /**
     * Counter Number Up Animation untuk Stat Cards
     * Jalankan setelah DOM siap + saat Livewire navigate
     */
    function runCounterAnimation() {
        const statValueEls = document.querySelectorAll('.fi-stats-overview-stat-value');

        statValueEls.forEach(function (el) {
            // Skip jika sudah dianimasi
            if (el.dataset.counted) return;
            el.dataset.counted = '1';

            const rawText = el.textContent.trim();

            // Cek apakah dimulai dengan angka murni (contoh: "1", "6", "2")
            const match = rawText.match(/^(\d[\d.,]*)(\s*.*)$/);
            if (!match) return;

            // Bersihkan angka dari format ribuan
            const cleanNum  = match[1].replace(/[.,]/g, '');
            const targetNum = parseInt(cleanNum, 10);
            const suffix    = match[2] || '';

            if (isNaN(targetNum) || targetNum === 0) return;

            const duration  = 1200; // ms
            const startTime = performance.now();

            function updateCounter(now) {
                const elapsed  = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const eased   = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(eased * targetNum);

                // Format ribuan kalau angka besar
                el.textContent = (current >= 1000
                    ? current.toLocaleString('id-ID')
                    : current) + suffix;

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    el.textContent = (targetNum >= 1000
                        ? targetNum.toLocaleString('id-ID')
                        : targetNum) + suffix;
                }
            }

            el.textContent = '0' + suffix;
            setTimeout(function () {
                requestAnimationFrame(updateCounter);
            }, 350); // mulai setelah fade-in stat card
        });
    }

    // Jalankan saat DOM pertama kali ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runCounterAnimation);
    } else {
        setTimeout(runCounterAnimation, 100);
    }

    // Jalankan ulang saat Livewire navigate (SPA behavior)
    document.addEventListener('livewire:navigated', function () {
        // Reset semua flag counter
        document.querySelectorAll('[data-counted]').forEach(function (el) {
            delete el.dataset.counted;
        });
        setTimeout(runCounterAnimation, 200);
    });
</script>
