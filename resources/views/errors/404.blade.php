<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Syifa Boxing Camp</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/polosan_logo_syifa.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0d1117;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(139,26,26,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(139,26,26,0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .container-404 {
            position: relative;
            z-index: 1;
            max-width: 560px;
        }

        .logo-wrap {
            margin-bottom: 2rem;
        }
        .logo-wrap img {
            width: 80px;
            opacity: 0.9;
            animation: floatLogo 3s ease-in-out infinite;
        }
        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }

        .number-404 {
            font-size: clamp(6rem, 20vw, 10rem);
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #cc2929, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            animation: fadeUp 0.6s ease both;
        }

        .glove-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            animation: punch 1.2s ease-in-out infinite alternate;
        }
        @keyframes punch {
            from { transform: rotate(-10deg) scale(0.95); }
            to   { transform: rotate(10deg) scale(1.05); }
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.75rem;
            animation: fadeUp 0.6s ease 0.1s both;
        }

        p {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 2rem;
            animation: fadeUp 0.6s ease 0.2s both;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #cc2929;
            color: #fff;
            padding: 0.75rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
            animation: fadeUp 0.6s ease 0.3s both;
        }
        .btn-home:hover {
            background: #a81f1f;
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-contact {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: #25d366;
            border: 2px solid #25d366;
            padding: 0.7rem 1.6rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s;
            animation: fadeUp 0.6s ease 0.4s both;
        }
        .btn-contact:hover {
            background: #25d366;
            color: #fff;
            transform: translateY(-2px);
        }

        .divider {
            width: 50px;
            height: 3px;
            background: #cc2929;
            margin: 0 auto 1.5rem;
            border-radius: 2px;
            animation: fadeUp 0.6s ease 0.15s both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container-404">
        <div class="logo-wrap">
            <img src="{{ asset('assets/images/polosan_logo_syifa.png') }}" alt="Syifa Boxing Camp">
        </div>

        <div class="number-404">404</div>

        <span class="glove-icon">🥊</span>

        <h1>Pojok Ring Ini Kosong!</h1>
        <div class="divider"></div>
        <p>
            Halaman yang kamu cari tidak ditemukan atau sudah dipindahkan.<br>
            Kembali ke beranda dan terus semangat berlatih!
        </p>

        <div style="display:flex; flex-wrap:wrap; gap:12px; justify-content:center;">
            <a href="/" class="btn-home">
                <i class="fas fa-home"></i> Ke Beranda
            </a>
            @php $wa = \App\Models\SiteSettings::get('whatsapp', '6281234567890'); @endphp
            <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn-contact">
                <i class="fab fa-whatsapp"></i> Hubungi Kami
            </a>
        </div>
    </div>
</body>
</html>
