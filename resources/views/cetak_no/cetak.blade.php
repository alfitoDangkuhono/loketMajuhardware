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
    </style>
</head>
<body>
    <script>
        window.print();
        window.onfocus  = function () { window.close(); };
        window.onmousemove = function () { window.close(); };
    </script>
    <center>
        <div class="content">
            <p><u>MAJU CARE<br>SERVICE CENTER</u><br>JL. Pahlawan No. 38-40, Kota Madiun</p>
            <p>Loket : {{ $jenis }}</p><br>
            <h3>{{ $huruf }} {{ sprintf('%02d', $nomor) }}</h3>
            <p>{{ $tgl }}<br>TERIMA KASIH<br>ATAS KUNJUNGAN ANDA</p>
        </div>
        <p>==============</p>
    </center>
</body>
</html>
