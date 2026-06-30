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

- 🎟️ **Kiosk customer** — ambil & cetak tiket antrian otomatis (auto-print via `window.print()`).
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
| `/load/{jenis}` | GET | panel angka dashboard per jenis |
| `/teller/call` | POST | tandai antrian sebagai "sudah" (dipanggil teller) |
| `/move/{jenis}` | GET | panel refresh teller |
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

- **Hapus video:** fitur hapus di admin hanya menghapus baris di tabel `video`. File fisik di `public/video/` harus dihapus manual.
- **Reset antrian:** menekan tombol Reset akan mengosongkan **seluruh** tabel `table_no_antrian` (truncate). Nomor kembali dari 1.
- **Browser autoplay:** dashboard butuh 1x klik user untuk mengaktifkan audio (policy browser). Ada banner hint otomatis.
- **CSRF:** endpoint POST (`/teller/call`, `/antrian/mark-announced/{id}`) dilindungi CSRF; token disisipkan via meta `csrf-token`.

## Lisensi

Proyek internal untuk Maju Care Service Center. Tidak bersumber dari template publik — bebas disesuaikan kebutuhan operasional.
