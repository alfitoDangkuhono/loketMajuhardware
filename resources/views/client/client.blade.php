<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loket Antrian - Maju Care</title>
    @include('client.fullscren')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        :root {
            --c-laptop:  #003399;
            --c-gadget:  #dc0000;
            --c-cpu:     #009933;
            --c-printer: #ff6600;
            --c-accent:  #ffcc00;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }

        body {
            font-family: Verdana, 'Segoe UI', sans-serif;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Background + overlay gelap supaya kartu & teks terbaca (tidak bentrok) */
            background:
                linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)),
                url('{{ asset('dist/img/bg.jpg') }}') center/cover no-repeat fixed;
            background-color: #111;
        }

        /* ---------- Paper low alert ---------- */
        .paper-alert {
            background: linear-gradient(90deg, #ff9800 0%, #f57c00 100%);
            color: #fff;
            text-align: center;
            padding: 14px 16px;
            font-size: clamp(.95rem, 1.6vw, 1.25rem);
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .35);
            animation: pulse-alert 2s ease-in-out infinite;
        }
        .paper-alert i { margin-right: 8px; }
        @keyframes pulse-alert {
            0%, 100% { box-shadow: 0 3px 10px rgba(245, 124, 0, .35); }
            50%      { box-shadow: 0 3px 18px rgba(245, 124, 0, .75); }
        }

        /* ---------- Header ---------- */
        .kiosk-header {
            text-align: center;
            padding: 3vh 1rem 1vh;
        }
        .kiosk-header img {
            height: clamp(60px, 9vw, 120px);
            margin-bottom: .8rem;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, .5));
        }
        .kiosk-header h1 {
            font-weight: 800;
            font-size: clamp(1.5rem, 3.4vw, 2.8rem);
            margin: 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, .6);
        }
        .kiosk-header p {
            margin: .6rem 0 0;
            font-size: clamp(.9rem, 1.6vw, 1.35rem);
            opacity: .92;
        }

        /* ---------- Grid kartu ---------- */
        .kiosk-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2vh 1rem;
        }

        .loket-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: clamp(1rem, 2.4vw, 2.4rem);
            width: 100%;
            max-width: 1300px;
        }

        @media (max-width: 992px) {
            .loket-grid { grid-template-columns: repeat(2, 1fr); max-width: 640px; }
        }
        @media (max-width: 560px) {
            .loket-grid { grid-template-columns: 1fr; max-width: 340px; }
        }

        .loket-card {
            background: var(--c-accent);
            border-radius: 24px;
            padding: 1.4rem 1.1rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .loket-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .45);
        }
        .loket-card img {
            width: 62%;
            height: auto;
            object-fit: contain;
        }

        .loket-card form { width: 100%; }

        .loket-btn {
            display: block;
            width: 100%;
            border: none;
            border-radius: 16px;
            color: #ffffff;
            font-weight: 700;
            font-size: clamp(1.1rem, 2vw, 1.8rem);
            padding: .85rem 1rem;
            margin: 1rem 0;
            cursor: pointer;
            transition: filter .15s ease;
        }
        .loket-btn:hover  { filter: brightness(1.12); }
        .loket-btn:active { filter: brightness(.92); }

        .loket-btn.laptop  { background: var(--c-laptop); }
        .loket-btn.gadget  { background: var(--c-gadget); }
        .loket-btn.cpu     { background: var(--c-cpu); }
        .loket-btn.printer { background: var(--c-printer); }

        /* ---------- Footer ---------- */
        .kiosk-footer {
            text-align: center;
            padding: 1vh 1rem 2vh;
            font-size: clamp(.8rem, 1.3vw, 1rem);
            opacity: .85;
        }
    </style>
</head>
<body>
    @if (!empty($paperCritical) && $paperCritical)
        <div class="paper-alert" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            Mohon maaf, kertas tiket sedang menipis. Hubungi loket untuk bantuan.
        </div>
    @endif

    <header class="kiosk-header">
        <img src="{{ asset('dist/img/MAJU CARE.png') }}" alt="Maju Care">
        <h1>MAJU CARE SERVICE CENTER</h1>
        <p>Silakan pilih layanan untuk mengambil nomor antrian</p>
    </header>

    <main class="kiosk-main">
        <div class="loket-grid">
            <div class="loket-card">
                <img src="{{ asset('dist/img/LAPTOP.png') }}" alt="Laptop">
                <form action="{{ route('cetak_laptop') }}" target="_blank" method="get">
                    <button type="submit" class="loket-btn laptop">Laptop</button>
                </form>
            </div>

            <div class="loket-card">
                <img src="{{ asset('dist/img/Gadget.png') }}" alt="Gadget">
                <form action="{{ route('cetak_Gadget') }}" target="_blank" method="get">
                    <button type="submit" class="loket-btn gadget">Gadget</button>
                </form>
            </div>

            <div class="loket-card">
                <img src="{{ asset('dist/img/KOMPUTER.png') }}" alt="Komputer">
                <form action="{{ route('cetak_CPU') }}" target="_blank" method="get">
                    <button type="submit" class="loket-btn cpu">Komputer</button>
                </form>
            </div>

            <div class="loket-card">
                <img src="{{ asset('dist/img/PRINTER.png') }}" alt="Printer">
                <form action="{{ route('cetak_Printer') }}" target="_blank" method="get">
                    <button type="submit" class="loket-btn printer">Printer</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="kiosk-footer">Jl. Pahlawan No. 38-40, Kota Madiun</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
