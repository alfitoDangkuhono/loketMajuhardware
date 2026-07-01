# Panduan Deploy ke Server Linux — Loket Maju Hardware

Dokumentasi cara menjalankan aplikasi **loket-majuhardware** (Laravel 10 + PostgreSQL) langsung di server Linux **tanpa Docker**.

> Ringkasan singkat juga ada di `README.md` → section "Deploy ke Server Linux". Dokumen ini versi lengkapnya.

---

## Daftar Isi
- [Prasyarat](#prasyarat)
- [Arsitektur File](#arsitektur-file)
- [Quick Start (3 langkah)](#quick-start-3-langkah)
- [Detail `setup.sh` (Setup Sekali Jalan)](#detail-setupsh-setup-sekali-jalan)
- [Menjalankan Aplikasi (`serve.sh`)](#menjalankan-aplikasi-servesh)
- [Konfigurasi Lanjutan](#konfigurasi-lanjutan)
- [Membuat Akun Admin Pertama](#membuat-akun-admin-pertama)
- [Auto-Start saat Server Boot (systemd)](#auto-start-saat-server-boot-systemd)
- [Update Aplikasi](#update-aplikasi)
- [Menjalankan di Port 80 / Domain / HTTPS (Nginx)](#menjalankan-di-port-80--domain--https-nginx)
- [Catatan Fitur Thermal Printer](#catatan-fitur-thermal-printer)
- [Troubleshooting](#troubleshooting)
- [Alternatif: Docker](#alternatif-docker)

---

## Prasyarat

- **Server Linux** dengan akses `root` / `sudo`.
- **Distro yang didukung otomatis:** Debian/Ubuntu (`apt`), RHEL/Rocky/Alma/Fedora (`dnf`/`yum`).
- **Koneksi internet** saat pertama kali setup (untuk install paket & dependency).
- **Port yang dipakai:** `8000` (aplikasi, bisa diubah), `5432` (PostgreSQL, internal).

Script `setup.sh` akan otomatis memasang: PHP 8.2 + ekstensi (`pdo_pgsql`, `gd`, `mbstring`, `zip`, `xml`, `curl`, `bcmath`, `intl`), Composer, PostgreSQL, dan Node.js.

---

## Arsitektur File

| File | Peran | Kapan dipakai |
|------|-------|---------------|
| `setup.sh` | Install dependency sistem + konfigurasi aplikasi | **Sekali** (atau ulang setelah perubahan besar) |
| `serve.sh` | Kontrol aplikasi (start/stop/status/dll) | **Setiap hari** |
| `loket-majuhardware-app.service` | Template systemd (diisi otomatis `serve.sh install`) | Saat ingin auto-start |

`serve.sh` punya dua mode menjalankan aplikasi:
1. **Foreground** (`./serve.sh serve`) — `php artisan serve` langsung, berhenti dengan `Ctrl+C`.
2. **Service** (`./serve.sh install` + `start`) — dikelola systemd, auto-restart & auto-start saat boot.

---

## Quick Start (3 langkah)

```bash
# 1. Dapatkan project di server
git clone <url-repo-ini> /opt/loket-majuhardware
cd /opt/loket-majuhardware
chmod +x setup.sh serve.sh

# 2. Setup (install PHP, Postgres, Node + config + migrate)
./setup.sh

# 3. Jalankan sebagai service
./serve.sh install     # pasang & enable systemd
./serve.sh start       # mulai
./serve.sh status      # cek status
```

Akses: **`http://<IP-SERVER>:8000`**

---

## Detail `setup.sh` (Setup Sekali Jalan)

`setup.sh` melakukan secara berurutan:

1. Install **PHP 8.2 + ekstensi** sesuai distro.
2. Install **Composer** (bila belum ada).
3. Install & jalankan **PostgreSQL**, lalu buat role + database `antrian_mh` (idempoten — aman dijalankan ulang).
4. Install **Node.js 20** untuk build aset Vite (bisa di-skip).
5. Salin & isi **`.env`** (DB, APP_URL, dll) bila belum ada.
6. `php artisan key:generate` bila `APP_KEY` masih kosong.
7. `composer install` + `npm install` + `npm run build`.
8. `php artisan migrate --force`.
9. Set **permission** `storage/` & `bootstrap/cache`.

### Variabel environment `setup.sh` (opsional, di-override sebelum perintah)

| Variabel | Default | Keterangan |
|----------|---------|------------|
| `PHP_VERS` | `8.2` | Versi PHP yang dipasang (apt) |
| `DB_NAME` | `antrian_mh` | Nama database |
| `DB_USER` | `postgres` | User database |
| `DB_PASS` | `postgres` | Password database |
| `DB_HOST` | `127.0.0.1` | Host database |
| `DB_PORT` | `5432` | Port database |
| `APP_HOST` | `0.0.0.0` | Bind host aplikasi |
| `APP_PORT` | `8000` | Port aplikasi |
| `SKIP_DB` | `0` | `1` = lewati install/setup PostgreSQL |
| `SKIP_NODE` | `0` | `1` = lewati install Node |

Contoh:
```bash
DB_PASS=rahasiaKuat APP_PORT=8080 ./setup.sh
SKIP_DB=1 ./setup.sh          # PostgreSQL sudah ada / remote
```

---

## Menjalankan Aplikasi (`serve.sh`)

### Mode A — Foreground (paling cepat untuk tes)
```bash
./serve.sh serve
```
Berhenti dengan `Ctrl+C`. Cocok untuk mengecek aplikasi langsung.

### Mode B — Service systemd (produksi, direkomendasikan)
```bash
./serve.sh install     # pasang unit systemd (sekali)
./serve.sh start       # mulai
./serve.sh status      # lihat status
./serve.sh logs        # tail log (Ctrl+C keluar)
```

Daftar lengkap perintah `serve.sh`:

| Perintah | Fungsi |
|----------|--------|
| `./serve.sh serve` | Jalankan langsung di foreground |
| `./serve.sh install` | Pasang & enable unit systemd |
| `./serve.sh uninstall` | Lepas unit systemd |
| `./serve.sh start` | Start service |
| `./serve.sh stop` | Stop service |
| `./serve.sh restart` | Restart service |
| `./serve.sh status` | Status service |
| `./serve.sh logs` | Tail log (`journalctl -f`) |
| `./serve.sh migrate` | `php artisan migrate --force` |
| `./serve.sh tinker` | `php artisan tinker` |

`serve.sh install` otomatis mengisi **path project**, **user**, dan **lokasi `php`** aktual ke `loket-majuhardware-app.service` — tidak perlu diedit manual.

---

## Konfigurasi Lanjutan

### Mengganti port / host
Edit `.env` di root project, lalu restart:
```bash
# .env
APP_URL=http://192.168.1.10:9000

./serve.sh restart     # bila pakai service (port di .service)
```
Atau jalankan ulang dengan override saat install:
```bash
APP_PORT=9000 ./serve.sh install && ./serve.sh start
```

### Membuka port di firewall
```bash
# Debian/Ubuntu (ufw)
sudo ufw allow 8000/tcp && sudo ufw reload

# RHEL/Rocky (firewalld)
sudo firewall-cmd --add-port=8000/tcp --permanent && sudo firewall-cmd --reload
```

### Database
- Default: database `antrian_mh`, user `postgres`, password `postgres`, di `127.0.0.1:5432`.
- Ubah kredensial lewat env saat `setup.sh` (lihat tabel di atas) atau edit `.env` manual.
- **Akses DB dari komputer lain** (mis. via pgAdmin/DBeaver): edit `pg_hba.conf` agar mengizinkan koneksi TCP, dan `postgresql.conf` agar `listen_addresses` sesuai, lalu restart:
  ```bash
  sudo systemctl restart postgresql
  ```

---

## Membuat Akun Admin Pertama

```bash
./serve.sh tinker
```
Lalu di dalam tinker:
```php
\App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@majucare.test',
    'password' => bcrypt('rahasia'),
]);
exit
```
Login di `http://<IP-SERVER>:8000/home`.

---

## Auto-Start saat Server Boot (systemd)

Saat `./serve.sh install` dijalankan, unit sudah otomatis **di-enable**, jadi aplikasi akan ikut menyala saat server boot. Untuk memastikan:
```bash
sudo systemctl is-enabled loket-majuhardware-app   # harus "enabled"
```
Manual (bila perlu):
```bash
sudo systemctl enable loket-majuhardware-app
```

Lokasi unit: `/etc/systemd/system/loket-majuhardware-app.service`

---

## Update Aplikasi

Setelah `git pull` (atau perubahan kode):
```bash
cd /opt/loket-majuhardware
git pull
composer install --no-dev --optimize-autoloader
npm install && npm run build        # bila ada perubahan aset frontend
php artisan migrate --force         # bila ada migration baru
php artisan optimize:clear
./serve.sh restart
```

---

## Menjalankan di Port 80 / Domain / HTTPS (Nginx)

`php artisan serve` adalah single-process. Untuk produksi dengan domain, port 80/443, atau HTTPS, gunakan **Nginx sebagai reverse proxy** di depan `artisan serve`:

1. Pasang Nginx: `sudo apt install -y nginx` (atau `dnf install -y nginx`).
2. Jalankan aplikasi di `127.0.0.1:8000` (bukan `0.0.0.0`):
   ```bash
   APP_HOST=127.0.0.1 ./serve.sh install && ./serve.sh start
   ```
3. Buat virtual host, mis. `/etc/nginx/sites-available/loket.conf`:
   ```nginx
   server {
       listen 80;
       server_name antrian.majucare.id;

       location / {
           proxy_pass         http://127.0.0.1:8000;
           proxy_set_header   Host              $host;
           proxy_set_header   X-Real-IP         $remote_addr;
           proxy_set_header   X-Forwarded-For   $proxy_add_x_forwarded_for;
           proxy_set_header   X-Forwarded-Proto $scheme;
       }
   }
   ```
4. Aktifkan & reload:
   ```bash
   sudo ln -s /etc/nginx/sites-available/loket.conf /etc/nginx/sites-enabled/
   sudo nginx -t && sudo systemctl reload nginx
   ```
5. (HTTPS) Gunakan Certbot: `sudo certbot --nginx -d antrian.majucare.id`.

> Untuk performa tertinggi (aset statis disajikan Nginx), pertimbangkan migrasi ke **PHP-FPM + Nginx** (bukan `artisan serve`).

---

## Catatan Fitur Thermal Printer

Fitur **cetak otomatis ke printer thermal (ESC/POS via USB)** dirancang untuk **Windows** (mode `usb`/`share`/`com`). Di server Linux, printer USB fisik umumnya tidak terpasang, sehingga sistem otomatis **fallback ke dialog cetak browser**. Untuk kiosk yang butuh cetak otomatis, jalankan aplikasi di mesin Windows.

---

## Troubleshooting

| Gejala | Penyebab | Solusi |
|--------|----------|--------|
| `php: command not found` | `setup.sh` belum dijalankan | Jalankan `./setup.sh` |
| `SQLSTATE[08006] ... connection refused` | PostgreSQL mati / kredensial salah | `sudo systemctl status postgresql`; cek `DB_*` di `.env` |
| `connection [postgres] not configured` | `DB_CONNECTION` bukan `pgsql` | Edit `.env` → `DB_CONNECTION=pgsql` |
| `No application encryption key found` | `APP_KEY` kosong | `php artisan key:generate` |
| Port 8000 tidak bisa diakses dari luar | Firewall blok | Buka port (lihat [Konfigurasi Lanjutan](#konfigurasi-lanjutan)) |
| `serve.sh start` → `Service belum dipasang` | Belum `install` | Jalankan `./serve.sh install` dulu |
| Aset (CSS/JS) hilang / 404 | `npm run build` belum jalan | `npm install && npm run build` |
| `permission denied` di `storage/logs` | Permission salah | `chmod -R 775 storage bootstrap/cache` |
| Service mati terus | Crashed | `./serve.sh logs` lalu periksa error |

Lihat log lengkap:
```bash
./serve.sh logs
# atau
sudo journalctl -u loket-majuhardware-app -f
```

---

## Alternatif: Docker

Bila ingin jalankan terisolasi tanpa memasang PHP/Postgres di sistem host, pakai Docker:
```bash
chmod +x deploy.sh
./deploy.sh        # auto-install Docker + start stack
```
Lihat `docker-compose.yaml` dan section "Opsi 2 — Docker" di `README.md`.
