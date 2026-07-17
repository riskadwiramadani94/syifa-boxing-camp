<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Syifa Boxing Camp</title>
    @filamentStyles
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        .video-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(100,0,0,0.65) 50%, rgba(0,0,0,0.9) 100%);
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background: rgba(15, 15, 15, 0.78);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 26, 26, 0.4);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow:
                0 0 0 1px rgba(139,26,26,0.2),
                0 25px 60px rgba(0,0,0,0.6),
                0 0 40px rgba(139,26,26,0.15);
        }

        .brand-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.75rem;
            gap: 0.6rem;
        }

        .brand-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(139,26,26,0.8));
            animation: pulse-glow 2.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { filter: drop-shadow(0 0 10px rgba(139,26,26,0.6)); }
            50%       { filter: drop-shadow(0 0 22px rgba(200,40,40,0.9)); }
        }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.45);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #8B1A1A, transparent);
            margin: 0 auto 1.5rem;
            border-radius: 99px;
        }

        .signin-title {
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            margin-bottom: 1.5rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.68rem;
            color: rgba(255,255,255,0.22);
            letter-spacing: 1px;
        }

        /* Partikel */
        .particle {
            position: fixed;
            border-radius: 50%;
            background: rgba(139,26,26,0.5);
            animation: float-up linear infinite;
            z-index: 1;
            pointer-events: none;
        }

        @keyframes float-up {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 0.5; }
            90%  { opacity: 0.2; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop playsinline>
        <source src="{{ asset('assets/videos/latihan.mp4') }}" type="video/mp4">
    </video>

    <div class="overlay"></div>

    @for ($i = 0; $i < 8; $i++)
        <div class="particle" style="
            left: {{ rand(5, 95) }}%;
            width: {{ rand(4, 10) }}px;
            height: {{ rand(4, 10) }}px;
            animation-duration: {{ rand(8, 18) }}s;
            animation-delay: {{ rand(0, 10) }}s;
        "></div>
    @endfor

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-header">
                <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Logo" class="brand-logo">
                <div class="brand-title">Syifa Boxing Camp</div>
                <div class="brand-subtitle">Management System</div>
            </div>

            <div class="divider"></div>

            <div class="signin-title">🥊 Masuk ke Panel Admin</div>

            <x-filament-panels::page.simple>
                {{ $this->content }}
            </x-filament-panels::page.simple>

            <div class="login-footer">
                © {{ date('Y') }} Syifa Boxing Camp · All rights reserved
            </div>
        </div>
    </div>

    @filamentScripts
</body>
</html>
