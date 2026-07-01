# Loket Maju Hardware — Sistem Antrian

Aplikasi **Sistem Antrian** untuk **Maju Care Service Center** (Jl. Pahlawan No. 38-40, Kota Madiun). Customer mengambil tiket dari 4 layanan, teller memanggil nomor, dan pengumuman suara Bahasa Indonesia diputar **terpusat** di satu dashboard TV umum.

Dibangun dengan Laravel 10 + PostgreSQL.

---

## Daftar Isi
- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Alur Kerja](#alur-kerja)
- [Skema Database](#skema-database)
- [Persyaratan](#persyaratan)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Printer Thermal (Cetak Otomatis)](#printer-thermal-cetak-otomatis)
- [Peta Halaman & Endpoint](#peta-halaman--endpoint)
- [Sistem Suara Panggilan](#sistem-suara-panggilan)
- [Kustomisasi](#kustomisasi)
- [Catatan Penting](#catatan-penting)

---

## Tentang Aplikasi

Sistem antrian untuk service center perangkat keras/gadget dengan 4 jenis layanan, masing-masing memiliki kode huruf sendiri:

| Kode | Jenis | Loket |
|------|-------|-------|
| **L** | Laptop | Loket Laptop |
| **G** | Gadget (HP) | Loket Gadget |
| **C** | Komputer / CPU | Loket CPU |
| **P** | Printer | Loket Printer |

Dirancang untuk dijalankan pada satu mesin kiosk (mis. XAMPP / PhpWebStudy) dengan:
- **Satu layar TV umum** (dashboard antrian) + speaker untuk pengumuman suara.
- **Satu layar kiosk** customer untuk ambil tiket (printer thermal).
- **Empat layar teller** (satu per loket) — **tidak perlu speaker** masing-masing.

## Fitur Utama

- 🎟️ **Kiosk customer** — ambil tiket antrian, **cetak otomatis ke printer thermal USB** via ESC/POS dengan fallback ke dialog print browser bila printer tidak tersedia.
- 📺 **Dashboard antrian (TV umum)** — tampilan 4 kolom real-time, video promosi, running text, jam digital.
- 🔊 **Pengumuman suara terpusat** — suara panggilan diputar HANYA di dashboard, dengan pengucapan angka Bahasa Indonesia (belas, puluh, ratus) + nama loket. Tellermasih cukup klik "Panggil".
- 🖥️ **Loket teller** — menampilkan nomor berikutnya, tombol Panggil & Antrian Selanjutnya, panel "No mendatang / No selesai".
- 📊 **Admin dashboard** — ringkasan jumlah antrian per jenis, upload video & running text, reset antrian harian.
- 📋 **Riwayat & Export** — tabel riwayat per jenis + export Excel/PDF/Print via DataTables.

## Teknologi

- **Backend:** Laravel 10.10, PHP 8.1+
- **Database:** PostgreSQL (nama DB default: `antrian_mh`)
- **Frontend:** Blade, Bootstrap 5, AdminLTE 3, jQuery, DataTables (CDN)
- **Auth:** laravel/ui (login/register bawaan)
- **Thermal printing:** mike42/escpos-php (ESC/POS via USB share)
- **Audio:** file `.ogg` di `public/audio/`

## Alur Kerja

```
┌────────────┐   ambil tiket    ┌──────────────┐
│  Kiosk     │ ───────────────▶ │ table_no_     │
│  Customer  │  ClientController│ antrian       │
│ /uknown_5  │  INSERT st=''    │ (PostgreSQL)  │
└────────────┘                  └──────┬───────┘
                                       │
                  teller klik Panggil  │  POST /teller/call
                                       │  → st='sudah', dipanggil=0
                                       ▼
┌────────────┐  poll /antrian/   ┌──────────────┐
│  Dashboard │  next-call (3s)   │ table_no_     │
│  Antrian   │ ◀──────────────── │ antrian       │
│  (TV umum) │                   │ dipanggil=0   │
│  + Speaker │ putar suara .ogg  └──────────────┘
│            │ → POST mark-announced → dipanggil=1
└────────────┘
```

1. **Customer** klik layanan di kiosk → tiket tercetak, baris baru dengan `st=''` (menunggu).
2. **Teller** klik **Panggil** → baris terlama (`st=''`) diubah jadi `st='sudah', dipanggil=0, called_at=now()`. **Tidak ada suara** di mesin teller.
3. **Dashboard antrian** mem-poll `/antrian/next-call` tiap 3 detik → mengambil baris `dipanggil=0` → memutar urutan suara → menandai `dipanggil=1` agar tidak dobel.
4. **Admin** bisa reset antrian (truncate) tiap awal hari.

## Skema Database

### `table_no_antrian` (inti)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigIncrements | PK |
| `no_antrian` | integer | nomor urut per jenis |
| `huruf` | string | kode huruf (L/G/C/P) |
| `jenis` | string | Laptop / Gadget / CPU / Printer |
| `tgl` | dateTime | waktu ambil tiket |
| `waktu` | time | jam ambil tiket |
| `st` | string | `''` menunggu, `sudah` sudah dipanggil |
| `cntr` | integer | counter tampilan dashboard |
| `dipanggil` | boolean | `0` belum diumumkan, `1` sudah (untuk suara terpusat) |
| `called_at` | timestamp | waktu teller klik Panggil |
| `created_at/updated_at` | timestamps | — |

### Tabel lain
- `video` — daftar video promosi (kolom: `id`, `video`).
- `text_db` — daftar running text (kolom: `id`, `text`).
- `users` — akun login admin/teller (bawaan Laravel).

## Persyaratan

- **PHP 8.1+** dengan ekstensi: `pdo_pgsql`, `openssl`, `mbstring`, `gd`, `fileinfo`, `zip`, `cURL`.
- **PostgreSQL** (lokal atau remote).
- **Composer**.
- (Opsional) **Printer thermal ESC/POS** (mis. Epson TM-T82, Tiger T60, Goojprt) tersambung USB di mesin kiosk untuk cetak tiket otomatis.
- (Opsional) Node.js & npm hanya jika ingin membangun ulang aset Vite.

## Instalasi

```bash
# 1. Clone / ekstrak project, lalu masuk foldernya
cd loket-majuhardware

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env
php artisan key:generate

# 4. Aktifkan ekstensi pdo_pgsql di php.ini (jika belum)
#    Windows (XAMPP/PhpWebStudy): uncomment extension=pdo_pgsql & extension=pgsql
#    pastikan file php_pdo_pgsql.dll ada di folder ext/

# 5. Buat database PostgreSQL
#    psql -U postgres -c "CREATE DATABASE antrian_mh;"

# 6. Sesuaikan kredensial DB di .env (lihat contoh di bawah), lalu migrate
php artisan migrate

# 7. (Opsional) bangun ulang aset frontend
npm install && npm run build
```

### Contoh konfigurasi `.env`

```ini
APP_NAME="MajuHardware"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql        # WAJIB pakai "pgsql", bukan "postgres"
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=antrian_mh
DB_USERNAME=postgres
DB_PASSWORD=rahasia
```

> **Penting:** nama koneksi Laravel adalah `pgsql`. Menulis `DB_CONNECTION=postgres` akan error "connection [postgres] not configured".

### Membuat akun admin pertama

Buka halaman `/register` lewat browser, atau via tinker:

```bash
php artisan tinker
>>> \App\Models\User::create([
...     'name' => 'Admin',
...     'email' => 'admin@majucare.test',
...     'password' => bcrypt('rahasia'),
... ]);
```

## Menjalankan Aplikasi

### Opsi A — dengan Docker (paling mudah, tanpa install PHP/Composer/Node)

Cukup butuh **Docker / Docker Desktop**. Semua dependency (PHP, Apache, PostgreSQL, Composer, Node) sudah ada di dalam image.

```bash
# 1. Clone repo ini
git clone <url-repo-ini> loket-majuhardware
cd loket-majuhardware

# 2. Jalankan (image di-pull otomatis dari Docker Hub)
docker compose up -d
```

Buka **http://localhost:8080**.

- **Menu utama:** http://localhost:8080/
- **Kiosk customer:** http://localhost:8080/uknown_5
- **Dashboard antrian (TV):** http://localhost:8080/antrian
- **Admin dashboard:** http://localhost:8080/home (wajib login)

Pada first run, container otomatis:
1. menunggu PostgreSQL siap,
2. `php artisan key:generate`,
3. `php artisan migrate` (membuat schema tabel di PostgreSQL),
4. menjalankan seeder (sekali).

> **Catatan:** file `finaldb.sql` adalah dump MySQL/MariaDB lama (struktur saja, tidak kompatibel PostgreSQL) sehingga **tidak dipakai**. Schema database dibuat via migration Laravel yang portable.

#### Membuat akun admin pertama (via container)

```bash
docker compose exec app php artisan tinker
>>> \App\Models\User::create([
...     'name' => 'Admin',
...     'email' => 'admin@majucare.test',
...     'password' => bcrypt('rahasia'),
... ]);
>>> exit
```

#### Mengubah konfigurasi (port, password DB, dll.)

Edit nilai di bagian `environment:` pada `docker-compose.yaml`, atau buat file `.env` di root project untuk override variabel compose:

```env
IMAGE=majuhardware/loket-majuhardware:latest
DB_DATABASE=antrian_mh
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Lalu `docker compose up -d`.

#### Perintah Docker lainnya

```bash
docker compose logs -f app     # lihat log aplikasi
docker compose exec app bash   # masuk shell container
docker compose down            # hentikan (data volume tetap)
docker compose down -v         # hentikan + HAPUS data DB & storage
```

> **Printer thermal:** fitur cetak otomatis (ESC/POS) dirancang untuk Windows & butuh akses USB langsung — di dalam container tidak bisa mengakses printer fisik. Untuk kiosk yang butuh cetak otomatis, jalankan aplikasi langsung di Windows (Opsi B). Container ini cocok untuk **dashboard TV** & **teller**.

---

### Opsi B — tanpa Docker (XAMPP / PhpWebStudy)

```bash
php artisan serve
```


Lalu akses di browser:
- **Menu utama:** `http://localhost:8000/`
- **Kiosk customer:** `http://localhost:8000/uknown_5`
- **Dashboard antrian (TV):** `http://localhost:8000/antrian`
- **Loket teller:** `http://localhost:8000/uknown_7` (Laptop), `/uknown_8` (Printer), `/uknown_9` (Gadget), `/uknown_10` (CPU)
- **Admin dashboard:** `http://localhost:8000/home` (wajib login)

> Klik sekali di dashboard antrian untuk mengaktifkan suara (browser memerlukan gestur user sebelum memutar audio).

## Deploy ke Server Linux (produksi)

> 📖 **Versi lengkap & step-by-step:** lihat [`DEPLOY-LINUX.md`](./DEPLOY-LINUX.md) (Native & Docker, troubleshooting, Nginx, auto-start, dll).

Ada dua cara — pilih salah satu:

| | **Opsi 1 — Native (tanpa Docker)** | **Opsi 2 — Docker** |
|---|---|---|
| File | `setup.sh` + `serve.sh` + `loket-majuhardware-app.service` | `deploy.sh` + `loket-majuhardware.service` + `docker-compose.yaml` |
| Install | PHP, Composer, PostgreSQL, Node dipasang di server | Semua di dalam container Docker |
| Cocok untuk | Server yang ingin jalankan Laravel langsung | Deploy cepat & terisolasi |

---

### Opsi 1 — Native (jalankan Laravel langsung, TANPA Docker)

```bash
# 1. Unggah project ke server
git clone <url-repo-ini> /opt/loket-majuhardware
cd /opt/loket-majuhardware
chmod +x setup.sh serve.sh

# 2. Setup sekali jalan (install PHP/Composer/Postgres/Node + config + migrate)
./setup.sh
#    override konfigurasi (opsional):  DB_PASS=rahasia APP_PORT=8000 ./setup.sh

# 3a. Jalankan langsung (foreground, Ctrl+C stop)
./serve.sh serve

# 3b. ATAU pasang sebagai service (auto-start saat boot)
./serve.sh install
./serve.sh start
./serve.sh status
./serve.sh logs
```

Akses: `http://<IP-SERVER>:8000`

Perintah `serve.sh`:
| Perintah | Fungsi |
|----------|--------|
| `./serve.sh serve` | jalan langsung (foreground) |
| `./serve.sh install` | pasang & enable service systemd |
| `./serve.sh start` / `stop` / `restart` | kontrol service |
| `./serve.sh status` / `logs` | status & log (journalctl) |
| `./serve.sh migrate` / `tinker` | utilitas artisan |

> Catatan: `php artisan serve` adalah single-process. Cukup untuk kiosk/dashboard internal. Untuk beban tinggi gunakan PHP-FPM + Nginx.
> Bila DB sudah ada & hanya ingin setup aplikasi: `SKIP_DB=1 ./setup.sh`.

---

### Opsi 2 — Docker

```bash
# di server, mis. di /opt/loket-majuhardware
git clone <url-repo-ini> /opt/loket-majuhardware
cd /opt/loket-majuhardware
chmod +x deploy.sh
./deploy.sh            # auto-install Docker bila belum ada, lalu start
./deploy.sh status     # lihat status + URL akses
```

Akses: `http://<IP-SERVER>:8080`

Auto-start on boot (opsional):
```bash
sudo cp loket-majuhardware.service /etc/systemd/system/
# sesuaikan WorkingDirectory bila lokasi project berbeda dari /opt/loket-majuhardware
sudo systemctl daemon-reload
sudo systemctl enable --now loket-majuhardware
```

Perintah `deploy.sh`:
| Perintah | Fungsi |
|----------|--------|
| `./deploy.sh` / `up` | pull image Docker Hub + start |
| `./deploy.sh --build` | build image dari source + start |
| `./deploy.sh update` | pull image terbaru + restart |
| `./deploy.sh restart` / `down` / `logs` / `status` | utilitas manajemen |
| `./deploy.sh shell` | masuk shell container app |


## Printer Thermal (Cetak Otomatis)

Sistem kiosk dapat mencetak tiket langsung ke printer thermal USB tanpa dialog browser, menggunakan library **`mike42/escpos-php`** yang mengirim perintah ESC/POS langsung. Jika printer tidak tersedia / gagal, sistem otomatis fallback ke dialog print browser (`window.print()`) — customer tetap dapat tiketnya.

### Prasyarat

- Printer thermal yang support **ESC/POS** (mayoritas printer thermal 58mm/80mm di pasaran).
- Driver printer sudah terinstall di mesin kiosk (Windows).
- Printer terhubung via **USB** ke mesin yang sama tempat Laravel berjalan.

### 1. Install Library

```bash
composer require mike42/escpos-php
```

> Versi yang terinstall otomatis menyesuaikan PHP Anda. PHP tanpa `ext-intl` akan mendapat v2.x (cukup untuk cetak teks), PHP dengan `ext-intl` bisa pakai v4.x (dukungan font lebih kaya).

### 2. Pilih Mode Koneksi

Sistem mendukung **5 mode koneksi** (dipilih via `THERMAL_PRINTER_MODE` di `.env`):

| Mode | Use Case | Cara menemukan nilai `THERMAL_PRINTER_NAME` |
|------|----------|---------------------------------------------|
| **`share`** (default) | Windows + butuh akses via SMB | Nama share printer di Control Panel > Sharing |
| **`usb`** ⭐ | **Windows + USB langsung (tanpa share)** | Control Panel > Printer Properties > tab Ports → port tercentang (`USB001`, `USB002`, ...) |
| **`com`** | Printer serial / virtual COM port driver | Device Manager > Ports → `COM3`, `COM4`, dst. |
| **`network`** | Printer Wi-Fi / Ethernet | IP printer + port raw (default `9100`) → format: `192.168.1.50:9100` |
| **`file`** | Linux/Unix | Path device file, mis. `/dev/usb/lp0` |

#### 2a. Mode `usb` (direct USB, tanpa share) — paling direkomendasikan untuk kiosk

1. Sambungkan printer USB ke mesin kiosk, install driver.
2. Buka **Control Panel → Devices and Printers**.
3. Klik kanan printer thermal → **Printer Properties** → buka tab **Ports**.
4. Lihat port yang **tercentang** (contoh: `USB001`). Catat namanya.
5. Set di `.env`:
   ```ini
   THERMAL_PRINTER_MODE=usb
   THERMAL_PRINTER_NAME=USB001
   ```

#### 2b. Mode `share` (alternatif — butuh sharing)

1. Buka **Control Panel → Devices and Printers**.
2. Klik kanan printer thermal → **Printer properties**.
3. Buka tab **Sharing** → centang **Share this printer** → isi **Share name** (misal: `TM-T82`).
4. Set di `.env`:
   ```ini
   THERMAL_PRINTER_MODE=share
   THERMAL_PRINTER_NAME=TM-T82
   ```

> **Catatan Windows 10/11:** bila koneksi gagal di mode `share`, aktifkan fitur **SMB 1.0/CIFS** (Control Panel → Programs → Turn Windows features on or off → centang `SMB 1.0/CIFS File Sharing Support`).

#### 2c. Mode `network` (printer Wi-Fi/Ethernet)

1. Pastikan printer & mesin kiosk dalam jaringan yang sama.
2. Cek IP printer (sering via LCD printer atau tools bawaan vendor).
3. Set di `.env`:
   ```ini
   THERMAL_PRINTER_MODE=network
   THERMAL_PRINTER_NAME=192.168.1.50:9100
   ```

### 3. Konfigurasi `.env`

```ini
# Mode koneksi: share / usb / com / network / file
THERMAL_PRINTER_MODE=usb

# Nilai sesuai mode:
#   usb     -> USB001 (cek di Printer Properties > Ports)
#   share   -> nama share printer (mis. TM-T82)
#   com     -> COM3
#   network -> 192.168.1.50:9100
THERMAL_PRINTER_NAME=USB001

# Lebar kertas dalam jumlah karakter:
#   - 58mm → 32 karakter
#   - 80mm → 48 karakter
THERMAL_PRINTER_WIDTH=32
```

Biarkan `THERMAL_PRINTER_NAME=` kosong bila belum punya printer — sistem akan otomatis pakai fallback browser.

### 4. Konfigurasi Tambahan (opsional)

Isi header/footer tiket & lebar garis pemutus di `config/printing.php`:

```php
'header' => "MAJU CARE\nSERVICE CENTER\nJL. Pahlawan No. 38-40\nKota Madiun",
'footer' => "Terima kasih\natas kunjungan anda",
'width'  => 32,
```

Setelah mengubah config, jalankan:

```bash
php artisan config:clear
```

### 5. Test Cetak

1. Jalankan aplikasi: `php artisan serve`.
2. Buka kiosk: `http://localhost:8000/uknown_5`.
3. Klik salah satu layanan (Laptop / Gadget / Komputer / Printer).
4. Hasil yang diharapkan:
   - **Printer tersambut:** tiket tercetak otomatis di printer + preview muncul dengan **badge hijau** "Tiket telah dicetak ke printer thermal" → jendela tertutup otomatis 2,5 detik.
   - **Printer mati / nama salah / tidak dikonfigurasi:** preview muncul dengan **badge kuning** "Printer thermal tidak terdeteksi — cetak manual" → dialog print browser muncul untuk pilih printer.

### Alur Cetak (Auto + Fallback)

```
Customer klik layanan
    │
    ▼
ClientController::cetakTicket()
    │
    │  INSERT tiket ke DB
    ▼
ThermalPrinter::printTicket()  ← kirim ESC/POS
    │  (mode: usb / share / com / network / file)
    │
    ┌───────────────┴────────────────┐
  sukses                          gagal / .env kosong
    │                                │
    ▼                                ▼
view auto_printed=true         view auto_printed=false
+ badge hijau                  + badge kuning
+ auto-close 2.5 detik         + window.print() dialog
```

### Troubleshooting

| Gejala | Mode | Penyebab umum | Solusi |
|--------|------|---------------|--------|
| Selalu fallback ke browser | semua | `THERMAL_PRINTER_NAME` kosong / salah eja | Cek nilai di `.env` |
| `failed to open stream` | `usb` | Port salah atau dipakai aplikasi lain | Tutup app lain, cek ulang tab Ports. Coba `USB002`, `USB003` |
| `couldn't print to printer` | `share` | SMB 1.0 belum aktif | Aktifkan via "Turn Windows features on or off" |
| Connection timeout | `network` | IP salah / firewall blok port 9100 | Ping IP printer, buka port 9100 di firewall |
| Cetak tapi karakter aneh/kotak | semua | Driver salah / printer bukan ESC/POS | Pakai driver bawaan (Generic / Text Only) |
| Cetak tapi tidak ter-cut otomatis | semua | Printer tidak support auto-cut command | Tekan cut manual, atau edit `ThermalPrinter::printTicket()` |
| Karakter ter-putus di ujung | semua | Lebar kertas salah | Set `THERMAL_PRINTER_WIDTH` sesuai (58mm=32, 80mm=48) |
| `Class 'Mike42\Escpos\Printer' not found` | semua | composer autoload belum refreshed | `composer dump-autoload` |

> **Tip:** Mode `usb` lebih cepat (raw bytes langsung, tanpa lewat spooler) dan tidak butuh SMB/Share — paling cocok untuk kiosk single-machine. Mode `share` berguna kalau printer dipakai bersama dari beberapa mesin.

### File Terkait

- `app/Services/ThermalPrinter.php` — service cetak ESC/POS dengan 5 mode connector + error handling.
- `app/Http/Controllers/ClientController.php` — controller kiosk yang memanggil service + kirim flag `auto_printed` ke view.
- `config/printing.php` — konfigurasi mode, nama printer, lebar kertas, header/footer tiket.
- `resources/views/cetak_no/cetak.blade.php` — view tiket dengan styling 2-mode (preview + auto-printed).

## Peta Halaman & Endpoint

| Halaman | URL | Controller |
|---------|-----|------------|
| Menu utama | `/` | `AuthController@face` |
| Kiosk customer | `/uknown_5` | `ClientController@index` |
| Cetak tiket | `/cetak_no/cetak_laptop` (Gadget/CPU/Printer) | `ClientController@cetak*` |
| Dashboard antrian | `/antrian` | `AntrianController@index` |
| Loket teller | `/uknown_7..10` | `TellerPageController@loket*` |
| Admin dashboard | `/home` | `HomeController@index` |
| Riwayat per jenis | `/uknown_1..4` | `TableXxxController@index` |
| Export | `/convert_L/G/C/P` | `TableXxxController@export` |

### Endpoint internal (AJAX)
| Endpoint | Method | Fungsi |
|----------|--------|--------|
| `/antrian/next-call` | GET | ambil 1 antrian menunggu diumumkan (`dipanggil=0`) |
| `/antrian/mark-announced/{id}` | POST | tandai sudah diumumkan |
| `/antrian/panel-all` | GET | panel angka gabungan semua jenis (JSON) |
| `/teller/call` | POST | tandai antrian sebagai "sudah" (dipanggil teller) |
| `/move/{jenis}` | GET | panel refresh teller (JSON) |
| `/mango_L/G/C/P` | GET | panel riwayat (refresh) |

> URL memakai nama samar (`uknown_*`) secara sengaja untuk kiosk publik.

## Sistem Suara Panggilan

Suara panggilan **hanya diputar di dashboard antrian** (1 speaker), bukan di tiap loket teller.

- File audio: `public/audio/*.ogg` (`0.ogg`–`11.ogg`, `100.ogg`, `belas.ogg`, `puluh.ogg`, `ratus.ogg`, `loket.ogg`, `nomor-urut.ogg`, `awal.ogg`, plus `L/G/C/P.ogg` & `laptop/gadget/CPU/printer.ogg`).
- Urutan: `awal → nomor-urut → [huruf kode] → [angka] → loket → [nama loket]`.
- Logika pengucapan angka ada di JS dashboard (`resources/views/antrian_no/antrian.blade.php`), diputar berurutan via event `onended` (adaptif terhadap durasi tiap clip).
- Mekanisme anti-dobel: kolom `dipanggil` memastikan tiap panggilan hanya diumumkan sekali walau dashboard di-refresh.

## Kustomisasi

- **Template tiket cetak** → `resources/views/cetak_no/cetak.blade.php` (data dari `ClientController@cetakTicket`).
- **Tampilan kiosk** → `resources/views/client/client.blade.php` (responsif CSS Grid, background `public/dist/img/bg.jpg`).
- **Tampilan dashboard TV** → `resources/views/antrian_no/antrian.blade.php`.
- **Halaman teller** → `resources/views/pageteller/teller.blade.php` (1 view parameterized untuk 4 loket).
- **Video promosi** → upload via admin (`/uplod`), disimpan di `public/video/`.
- **Running text** → upload via admin (`/plod`).
- **Reset antrian harian** → tombol di admin dashboard (`/reset` → truncate tabel).

## Catatan Penting

- **Printer thermal:** fitur cetak otomatis butuh printer di-share di Windows dan nama share di-`THERMAL_PRINTER_NAME`. Bila gagal, sistem otomatis fallback ke dialog print browser — customer tetap dapat tiket.
- **Reset antrian:** menekan tombol Reset akan mengosongkan **seluruh** tabel `table_no_antrian` (truncate). Nomor kembali dari 1.
- **Browser autoplay:** dashboard butuh 1x klik user untuk mengaktifkan audio (policy browser). Ada banner hint otomatis.
- **CSRF:** endpoint POST (`/teller/call`, `/antrian/mark-announced/{id}`, `/logout`) dilindungi CSRF; token disisipkan via meta `csrf-token` atau `@csrf` di form.

## Lisensi

Proyek internal untuk Maju Care Service Center. Tidak bersumber dari template publik — bebas disesuaikan kebutuhan operasional.
