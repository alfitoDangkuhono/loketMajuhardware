<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loket {{ $jenis }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <style>
        body { background-color: #74797b; }
        .card { border: 2px solid black; width: 50%; }
        #font { font-family:"Gill Sans Extrabold", sans-serif; font-size:90px; font-weight:bold; }
        #jam { color:white; border-radius:3px; font-size:30px; }
        #mess { font-family:"Gill Sans Extrabold", sans-serif; font-size:30px; font-weight:bold; }
        #main { border: 2px solid black; width: 50%; border-radius:5px; }
        #title_main { background-color: black; border-radius: 0px; margin:auto -12px; padding:5px; }
    </style>
    <script type="text/javascript">
        $(function () {
            // Panel "No mendatang" / "No selesai" di-refresh via JSON (bukan
            // .load HTML) supaya payload kecil. Polling pakai setTimeout
            // recursive 3s, hanya update DOM kalau nilai berubah, dan pause
            // otomatis saat tab tidak terlihat (hemat CPU/jaringan).
            var REFRESH_URL = "{{ route('move', $jenis) }}";
            var REFRESH_MS   = 3000;
            var KODE         = "{{ $kode }}";
            var lastMendatang = "{{ $kode }}{{ sprintf('%02d', $noDtBelum) }}";
            var lastSelesai   = "{{ $kode }}{{ sprintf('%02d', $noDtSudah) }}";

            function pollRefresh() {
                if (document.hidden) {
                    setTimeout(pollRefresh, REFRESH_MS);
                    return;
                }
                $.getJSON(REFRESH_URL, function (res) {
                    var m = KODE + res.mendatang;
                    var s = KODE + res.selesai;
                    if (m !== lastMendatang) {
                        $("#no-mendatang").text(m);
                        lastMendatang = m;
                    }
                    if (s !== lastSelesai) {
                        $("#no-selesai").text(s);
                        lastSelesai = s;
                    }
                    setTimeout(pollRefresh, REFRESH_MS);
                }).fail(function () {
                    setTimeout(pollRefresh, REFRESH_MS);
                });
            }

            // CSRF untuk tombol Panggil (POST).
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Tombol Panggil: tandai antrian sebagai "sudah" di server.
            // Suara TIDAK diputar di mesin teller -> diputar terpusat di dashboard antrian.
            $('#btn-panggil').on('click', function () {
                var btn = $(this);
                btn.prop('disabled', true).html('Memanggil...');
                $.post("{{ route('teller.call') }}", { jenis: "{{ $jenis }}" })
                    .done(function () {
                        window.location.reload();
                    })
                    .fail(function () {
                        alert('Tidak ada antrian untuk dipanggil.');
                        btn.prop('disabled', false).html('<i class="fas fa-bullhorn"></i> Panggil');
                    });
            });

            setTimeout(pollRefresh, REFRESH_MS);
        });

        // Jam digital.
        function set(e) { return e < 10 ? '0' + e : e; }
        function jam() {
            var d = new Date();
            document.getElementById('jam').innerHTML =
                d.getHours() + ':' + set(d.getMinutes()) + ':' + set(d.getSeconds());
            setTimeout(jam, 1000);
        }
        window.onload = jam;
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Loket {{ $jenis }}</a>
            <a id="jam">{{ date('H : i : s') }}</a>
        </div>
    </nav>

    <center>
        <div class="card-body">
            <br><br><br>
            <div class="card">
                <div class="navbar-dark bg-dark">
                    <center><h1 style="color:aliceblue">Loket {{ $jenis }}</h1></center>
                </div>
                <center><h3 id="mess">{{ $no == 0 ? '********* NO ANTRIAN BELUM MASUK *********' : '' }}</h3></center>
                <strong>
                    <h1 class="card-body" id="font">{{ $kode }}{{ sprintf('%02d', $no) }}</h1>
                </strong>
                <div class="d-flex justify-content-center">
                    <div class="grid gap-0 row-gap-3">
                        <button type="button" id="btn-panggil" class="btn btn-warning" {{ $no == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-bullhorn"></i> Panggil
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-success" onclick="window.location.reload()">
                            Antrian Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </center>
    <br><br>
    <div id="frsh">
        <div class="container-fluid">
            <div class="grid gap-0 row-gap-3">
                <div class="row">
                    <div class="col-sm-3 col-md-6 bg-warning" id="main">
                        <div id="title_main">
                            <center><h1 style="color:aliceblue">No mendatang</h1></center>
                        </div>
                        <br>
                        <strong>
                            <center><h1 class="card-body" id="font"><span id="no-mendatang">{{ $kode }}{{ sprintf('%02d', $noDtBelum) }}</span></h1></center>
                        </strong>
                    </div>
                    <div class="col-sm-9 col-md-6 bg-success" id="main">
                        <div id="title_main">
                            <center><h1 style="color:rgb(255, 247, 247)">No selesai</h1></center>
                        </div>
                        <br>
                        <strong>
                            <center><h1 class="card-body" id="font"><span id="no-selesai">{{ $kode }}{{ sprintf('%02d', $noDtSudah) }}</span></h1></center>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
