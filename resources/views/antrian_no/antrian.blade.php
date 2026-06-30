   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

   <!DOCTYPE html>
   <html lang="en">

   <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

      <script type="text/javascript">
        // Refresh 4 panel angka via 1 request JSON (sebelumnya 4 request
        // .load HTML terpisah). setTimeout recursive, skip DOM update kalau
        // nilai tidak berubah, pause saat tab tidak terlihat.
        var PANEL_ALL_URL = "<?php echo e(route('antrian.panel_all')); ?>";
        var PANEL_REFRESH_MS = 10000;

        // Cache text terakhir per jenis untuk skip update.
        var panelCache = { Laptop: '', Gadget: '', CPU: '', Printer: '' };
        var panelTargets = {
            Laptop: '#refresh_L',
            Gadget: '#refresh_G',
            CPU:    '#refresh_C',
            Printer:'#refresh_P'
        };

        function refreshPanels() {
            if (document.hidden) {
                setTimeout(refreshPanels, PANEL_REFRESH_MS);
                return;
            }
            $.getJSON(PANEL_ALL_URL, function (data) {
                Object.keys(panelTargets).forEach(function (jenis) {
                    var info = data[jenis];
                    if (!info) return;
                    var txt = info.kode + info.no;
                    if (txt !== panelCache[jenis]) {
                        $(panelTargets[jenis]).html('<h1>' + txt + '</h1>');
                        panelCache[jenis] = txt;
                    }
                });
                setTimeout(refreshPanels, PANEL_REFRESH_MS);
            }).fail(function () {
                setTimeout(refreshPanels, PANEL_REFRESH_MS);
            });
        }
        $(document).ready(function() {
          setTimeout(refreshPanels, PANEL_REFRESH_MS);
        });
      </script>

      <script type="text/javascript">
        // ======= Auto-update video & running text =======
        // Dipoll tiap 10s. Video/text hanya di-reload kalau isinya benar-benar
        // berubah (dicek via cache string) supaya marquee tidak restart tanpa
        // alasan dan video tidak ter-rewind.
        //
        // PENTING: cache di-init dari nilai yang sudah dirender server agar
        // poll pertama TIDAK memicu reload video/text yang sedang aktif
        // (reload video besar bikin tab berat).
        var CONTENT_URL     = "<?php echo e(route('antrian.content')); ?>";
        var CONTENT_POLL_MS = 10000;
        var INIT_VIDEO = <?php echo json_encode($videos->first()->video ?? null); ?>;
        var INIT_TEXTS = <?php echo json_encode($texts->pluck('text')->values()->all()); ?>;
        var lastVideoName   = INIT_VIDEO;
        var lastTextsKey    = JSON.stringify(INIT_TEXTS);

        // Escape HTML supaya teks dari DB tidak dieksekusi sebagai markup.
        function escapeHtml(s) {
          return $('<div>').text(s == null ? '' : s).html();
        }

        function applyTexts(texts) {
          var html = (texts || []).map(function (t) {
            return '<marquee behavior="scroll" scrollamount="6" id="fot">' +
                     '<h3 id="fontfot">' + escapeHtml(t) + '</h3>' +
                   '</marquee>';
          }).join('');
          $('#texts-container').html(html);
        }

        function pollContent() {
          if (document.hidden) {
            setTimeout(pollContent, CONTENT_POLL_MS);
            return;
          }
          $.getJSON(CONTENT_URL, function (data) {
            // --- Video ---
            if (data.video && data.video !== lastVideoName) {
              var v = document.getElementById('video-el');
              if (v) {
                v.src = 'video/' + data.video;
                v.load();
                var p = v.play();
                if (p && p.catch) p.catch(function () {}); // autoplay bisa ditolak
              }
              lastVideoName = data.video;
            }

            // --- Running text ---
            var key = JSON.stringify(data.texts || []);
            if (key !== lastTextsKey) {
              applyTexts(data.texts || []);
              lastTextsKey = key;
            }

            setTimeout(pollContent, CONTENT_POLL_MS);
          }).fail(function () {
            setTimeout(pollContent, CONTENT_POLL_MS);
          });
        }

        $(document).ready(function () {
          setTimeout(pollContent, CONTENT_POLL_MS);
        });
      </script>

     <title>Document</title>
     <style>
       body {
         background-color: rgb(0, 0, 0);
       }

       * {
         box-sizing: border-box;
       }

       /* Create three equal columns that floats next to each other */
       .column {
         float: left;
         /* width: 33.33%; */
         /* width: 50%; */
         /*width: 744px;       for microsoft edge */
         width: 771px;
         padding: 0px;
         height: 343px;
         /* Should be removed. Only for demonstration */
       }

       /* Clear floats after the columns */
       .row:after {
         content: "";
         display: table;
         clear: both;
       }

       #footer {
         background-color: aqua;
         /* height: 4%; */
         height: 32px;
         /* width: 100%;         for microsoft edge*/
         /* width: 100%; */
         flex-wrap: wrap;
         display: flex;
       }

       h1 {
         /* font-size: 190px;  sample one*/
         font-size: 150px;
         text-align: center;
       }

       #h2_C {
         background-color: rgb(0, 153, 51);
         color: aliceblue;
         text-align: center;
         font-weight: bold;
         padding: 11px;
         margin: 0px -12px;
         max-width: 120%;
         border-radius: 5px;
       }

       #h2_G {
         background-color: rgb(220, 0, 0);
         color: aliceblue;
         text-align: center;
         font-weight: bold;
         padding: 11px;
         margin: 0px -12px;
         max-width: 120%;
         border-radius: 5px;
       }

       #h2_P {
         background-color: rgb(255, 102, 0);
         color: aliceblue;
         text-align: center;
         font-weight: bold;
         padding: 11px;
         margin: 0px -12px;
         max-width: 120%;
         border-radius: 5px;
       }

       #h2_L {
         background-color: rgb(0, 51, 153);
         color: aliceblue;
         text-align: center;
         font-weight: bold;
         padding: 11px;
         margin: 0px -12px;
         max-width: 120%;
         border-radius: 5px;
       }

       .container-fluid {
         width: 100%;
       }

       .row {
         height: 40%;

       }

       .col-sm {
         background-color: #EDB835;
         border: 4px solid black;
         border-radius: 8px;
         margin-top: 40px;
         /* height:100%; */
       }

       .col-md-4 {
         border: 5px solid black;
         border-radius: 8px;
         /* background:url('dist/img/MAJU CARE.png'); */
         /* background-size:500px; */
         background-size: 100% 100%;
         height: 151%;
       }

       .col-md-8 {
         border-radius: 5px;
         /* height:300px; */
         height: 519px;
         align-items: end;
         width: 66%;
       }

       #title {
         color: yellow;
       }

       #jam {
         text-align: center;
         font-size: 95px;
         color: white;
         height: 100px;
       }

       #img {
         align-items: center;
         width: 500px;
         height: 300px;
       }

       #btn {
         color: rgba(0, 0, 0, 0.5);
       }

       #video {
         width: 800px;
       }

       #text {
         background: url(dist/img/bg-menu.jpg);
         color: aliceblue;
         text-align: center;
         border: 3px solid black;
         padding: 11px;
         max-width: 100%;
         border-radius: 5px;
         font-size: 20px;
       }

       <style>#footer {

         color: white;
       }

        #fot {
          background-color: rgb(190, 194, 81);
          height: 60px;
          overflow: visible;
        }

        #fontfot {
          /* font-size:40px; */
          font-family: "Gill Sans Extrabold", sans-serif;
          margin: 0;
          line-height: 60px;
          font-size: 28px;
        }
     </style>
     </style>
   </head>

   <body>

     <div class="container-fluid">
       <div class="row">
         <div class="col-md-4">
           <br>
           <img id="img" src="dist/img/MAJU CARE.png" alt="">
           <br>
           <br>
           <p id="jam" class="nav-link" data-widget="fullscreen" href="#" type="hidden" role="button">00 : 00 : 00</p>
         </div>
           <video id="video-el" class="col-md-8" controls muted autoplay loop>
             @foreach ($videos as $row)
               <source src="video/{{ $row->video }}" type="video/mp4">
             @endforeach
           </video>
        </div>
        <!-- NOTE: -> KODE REFRESH BERADA DI ID HTML CONTOH="refresh_L" ITU ADALAH PARAMETER/DESTINATION REFRESH MODE -->

        <div class="row fixed-bottom" id="mgn">
          <div class="col-sm">
            <h2 id="h2_L">LAPTOP</h2>
            <div id="refresh_L">
            </div>
          </div>
          <div class="col-sm">
            <h2 id="h2_G">GADGET</h2>
            <div id="refresh_G">
            </div>
          </div>
          <div class="col-sm">
            <h2 id="h2_C">KOMPUTER</h2>
            <div id="refresh_C">
            </div>
          </div>
          <div class="col-sm">
            <h2 id="h2_P">PRINTER</h2>
            <div id="refresh_P">
            </div>
          </div>
         <div id="texts-container" class="w-100">
           @foreach ($texts as $text)
             <marquee behavior="scroll" scrollamount="6" id="fot">
               <h3 id="fontfot">{{ $text->text }}</h3>
             </marquee>
           @endforeach
         </div>
         </div>
      </div>

     <!-- ============ SISTEM SUARA PANGGILAN TERPUSAT ============
         Hanya dashboard antrian (TV umum) yang memutar suara.
         Teller hanya menandai antrian "sudah" via tombol Panggil,
         suara otomatis diputar di sini sehingga tidak perlu speaker
         di tiap loket teller.
    -->
     <div id="audio-unlock-hint" style="position:fixed;top:0;left:0;right:0;background:#ffcc00;color:#000;text-align:center;padding:8px;font-weight:bold;z-index:9999;display:none;">
       Klik di mana saja untuk mengaktifkan suara panggilan.
     </div>

     <script type="text/javascript">
       (function() {
          var NEXT_CALL_URL = "{{ route('antrian.next_call') }}";
          var MARK_BASE = "{{ url('antrian/mark-announced') }}/"; // + id
          var CSRF_TOKEN = "{{ csrf_token() }}";
         var AUDIO_DIR = "audio/";

         // Mapping jenis -> nama file suara loket (case-sensitive).
         var JENIS_AUDIO = {
           Laptop: "laptop",
           Gadget: "gadget",
           CPU: "CPU",
           Printer: "printer"
         };

         // ======= Unlock autoplay (browser butuh gestur) =======
         var audioUnlocked = false;

         function unlockAudio() {
           if (audioUnlocked) return;
           var a = new Audio(AUDIO_DIR + "awal.ogg");
           a.volume = 0;
           a.play().then(function() {
               a.pause();
               audioUnlocked = true;
               hideHint();
             })
             .catch(function() {
               audioUnlocked = false;
             });
         }

         function showHint() {
           document.getElementById('audio-unlock-hint').style.display = 'block';
         }

         function hideHint() {
           document.getElementById('audio-unlock-hint').style.display = 'none';
         }

         $.ajaxSetup({
           headers: {
             'X-CSRF-TOKEN': CSRF_TOKEN
           }
         });

         // ======= Susun urutan clip ogg untuk sebuah nomor =======
         // Replikasi logika pengucapan Bahasa Indonesia:
         // awal -> nomor-urut -> huruf kode -> angka -> loket -> nama loket
         function buildClips(no, kode, jenis) {
           var n = parseInt(no, 10);
           var clips = ["awal.ogg", "nomor-urut.ogg", kode + ".ogg"];

           if (n === 100) {
             clips.push("100.ogg");
           } else if (n === 10 || n === 11) {
             clips.push(n + ".ogg");
           } else if (n >= 12 && n <= 19) {
             clips.push((n % 10) + ".ogg", "belas.ogg");
           } else if (n >= 20 && n <= 99) {
             var tens = Math.floor(n / 10);
             var ones = n % 10;
             clips.push(tens + ".ogg", "puluh.ogg");
             if (ones > 0) clips.push(ones + ".ogg"); // bug lama: "dua puluh nol" -> diperbaiki
           } else if (n >= 0 && n <= 9) {
             clips.push("0.ogg", n + ".ogg"); // "nol x" sesuai rekaman lama
           }

           clips.push("loket.ogg", (JENIS_AUDIO[jenis] || (jenis || "")) + ".ogg");
           return clips;
         }

          // ======= Putar berurutan =======
          var isAnnouncing = false;
          var CLIP_GAP_MS = 0; // jeda antar clip (ms) - 0 = serapat mungkin

          function playSequence(clips, onDone) {
            // Buat 1 Audio per clip & preload paralel -> saat play() tidak
            // ada delay network (file kecil .ogg langsung ke-cache browser).
            var players = clips.map(function(c) {
              var a = new Audio(AUDIO_DIR + c);
              a.preload = 'auto';
              a.load();
              return a;
            });

            var i = 0;
            function next() {
              if (i >= players.length) {
                if (onDone) onDone();
                return;
              }
              var a = players[i];
              a.volume = 1;
              a.onended = function() {
                i++;
                if (CLIP_GAP_MS > 0) setTimeout(next, CLIP_GAP_MS);
                else next();
              };
              var p = a.play();
              if (p && p.catch) {
                p.catch(function() { i++; next(); }); // clip gagal -> skip
              }
            }
            next();
          }

          // ======= Poll antrian yang menunggu diumumkan =======
          // Pakai setTimeout recursive (bukan setInterval) supaya hanya ada
          // 1 poll in-flight. Saat idle cek tiap 5s, habis announce cek
          // cepat 500ms (untuk antrian beruntun).
          var POLL_IDLE_MS = 5000;
          var POLL_AFTER_ANNOUNCE_MS = 500;

          function schedulePoll(delay) {
            setTimeout(pollNextCall, delay);
          }

          function pollNextCall() {
            if (!audioUnlocked) {
              schedulePoll(POLL_IDLE_MS);
              return;
            }
            // Set flag SYNCRONOUS sebelum AJAX mencegah race dobel suara.
            isAnnouncing = true;

            $.getJSON(NEXT_CALL_URL, function(res) {
              if (res.empty) {
                isAnnouncing = false;
                schedulePoll(POLL_IDLE_MS);
                return;
              }
              var clips = buildClips(res.no_antrian, res.kode, res.jenis);
              playSequence(clips, function() {
                $.post(MARK_BASE + res.id).always(function() {
                  isAnnouncing = false;
                  schedulePoll(POLL_AFTER_ANNOUNCE_MS);
                });
              });
            }).fail(function() {
              isAnnouncing = false;
              schedulePoll(POLL_IDLE_MS);
            });
          }

          // Tampilkan hint bila belum unlock dalam 1 detik.
          setTimeout(function() {
            if (!audioUnlocked) showHint();
          }, 1000);
          document.addEventListener('click', unlockAudio);
          document.addEventListener('keydown', unlockAudio);
          document.addEventListener('touchstart', unlockAudio);

          schedulePoll(1500); // mulai polling pertama 1.5s setelah load
        })();
      </script>
   </body>

   </html>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
   <!-- SCRIPT JAM  -->
   <script type="text/javascript">
     window.onload = function() {
       jam();
     }

     function jam() {
       var e = document.getElementById('jam'),
         d = new Date(),
         h, m, s;
       h = d.getHours();
       m = set(d.getMinutes());
       s = set(d.getSeconds());

       e.innerHTML = h + ':' + m + ':' + s;

       setTimeout('jam()', 1000);
       if (m > 1) {

       }
     }

     function set(e) {
       e = e < 10 ? '0' + e : e;
       return e;
     }
   </script>

   <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

   <!-- SCRIPT FULL SCREEN  -->
   <script src="plugins/jquery/jquery.min.js"></script>
   <script src="dist/js/adminlte.js"></script>