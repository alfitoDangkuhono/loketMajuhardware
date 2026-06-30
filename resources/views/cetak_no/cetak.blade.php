<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian {{ $huruf }}</title>
    <style>
        * { font-size: 12px; font-family: 'Times New Roman'; }
        h3 { font-size: 30px; }
        .content { width: 100%; }

        @media screen {
            body {
                background: #f1f3f5;
                margin: 0;
                padding: 24px;
            }
            .ticket {
                background: #fff;
                width: 280px;
                margin: 0 auto;
                padding: 20px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
                border-radius: 6px;
            }
            .badge {
                position: fixed;
                top: 16px;
                left: 50%;
                transform: translateX(-50%);
                padding: 8px 18px;
                border-radius: 999px;
                font-family: 'Segoe UI', sans-serif;
                font-size: 13px;
                font-weight: 600;
                color: #fff;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            }
            .badge--ok   { background: #2ecc71; }
            .badge--warn { background: #f39c12; }
            .ticket-foot {
                font-family: 'Segoe UI', sans-serif;
                text-align: center;
                color: #6c757d;
                font-size: 12px;
                margin-top: 18px;
            }
        }

        @media print {
            .badge, .ticket-foot { display: none !important; }
            body { background: #fff !important; margin: 0; padding: 0; }
            .ticket { box-shadow: none; padding: 0; margin: 0; width: 100%; border-radius: 0; }
        }
    </style>
</head>
<body>
    @if (!empty($auto_printed))
        <div class="badge badge--ok">Tiket telah dicetak ke printer thermal</div>
    @else
        <div class="badge badge--warn">Printer thermal tidak terdeteksi &mdash; cetak manual</div>
    @endif

    <div class="ticket">
        <div class="content">
            <center>
                <p><u>MAJU CARE<br>SERVICE CENTER</u><br>JL. Pahlawan No. 38-40, Kota Madiun</p>
                <p>Loket : {{ $jenis }}</p><br>
                <h3>{{ $huruf }} {{ sprintf('%02d', $nomor) }}</h3>
                <p>{{ $tgl }}<br>TERIMA KASIH<br>ATAS KUNJUNGAN ANDA</p>
            </center>
            <p>==============</p>
        </div>
    </div>

    <p class="ticket-foot">
        @if (!empty($auto_printed))
            Jendela akan tertutup otomatis&hellip;
        @else
            Tekan <b>Ctrl + P</b> jika dialog cetak tidak muncul.
        @endif
    </p>

    <script>
        (function () {
            var autoPrinted = {{ !empty($auto_printed) ? 'true' : 'false' }};

            if (autoPrinted) {
                // Sudah dicetak thermal -> cukup preview, lalu tutup jendela
                setTimeout(function () {
                    window.close();
                    // Fallback: kembali ke kiosk kalau window.close() diblokir
                    window.location.href = "{{ route('client') }}";
                }, 2500);
            } else {
                // Fallback browser print dialog
                window.print();
                window.onfocus    = function () { window.close(); };
                window.onmousemove = function () { window.close(); };
            }
        })();
    </script>
</body>
</html>
