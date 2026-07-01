#!/usr/bin/env bash
# =============================================================================
# docker-entrypoint.sh
# Dijalankan setiap kali container start.
# Tugas:
#   1. Pastikan file .env ada
#   2. Tunggu PostgreSQL siap
#   3. Generate APP_KEY bila masih kosong
#   4. Jalankan migration (membuat schema) + seeder bila database masih kosong
#      Catatan: file finaldb.sql adalah dump MySQL/MariaDB (syntax tidak
#      kompatibel PostgreSQL) sehingga TIDAK dipakai. Schema dibuat via
#      `php artisan migrate` yang menghasilkan DDL PostgreSQL native & portable.
#   5. Set permission storage
#   6. Jalankan perintah utama container (apache)
# =============================================================================
set -e

: "${DB_HOST:=db}"
: "${DB_PORT:=5432}"
: "${DB_DATABASE:=antrian_mh}"
: "${DB_USERNAME:=postgres}"
: "${DB_PASSWORD:=}"

cd /var/www/html

# ----------------------------------------------------------------------------
# 1. Pastikan .env ada. Laravel akan baca environment variable dari container
#    lebih dulu, tapi .env tetap diperlukan (terutama APP_KEY & APP_URL).
# ----------------------------------------------------------------------------
if [ ! -f .env ]; then
    echo "[entrypoint] .env tidak ditemukan, menyalin dari .env.example"
    cp .env.example .env
fi

# Sinkronkan beberapa key penting dari environment container ke .env
update_env() {
    local key="$1"
    local val="$2"
    if grep -q "^${key}=" .env; then
        sed -i -E "s|^${key}=.*|${key}=${val}|" .env
    else
        echo "${key}=${val}" >> .env
    fi
}

update_env "APP_ENV"        "${APP_ENV:-production}"
update_env "APP_DEBUG"      "${APP_DEBUG:-false}"
update_env "APP_URL"        "${APP_URL:-http://localhost:8080}"
update_env "DB_CONNECTION"  "pgsql"
update_env "DB_HOST"        "${DB_HOST}"
update_env "DB_PORT"        "${DB_PORT}"
update_env "DB_DATABASE"    "${DB_DATABASE}"
update_env "DB_USERNAME"    "${DB_USERNAME}"
update_env "DB_PASSWORD"    "${DB_PASSWORD}"
update_env "CACHE_DRIVER"   "${CACHE_DRIVER:-file}"
update_env "SESSION_DRIVER" "${SESSION_DRIVER:-file}"
update_env "QUEUE_CONNECTION" "${QUEUE_CONNECTION:-sync}"

# ----------------------------------------------------------------------------
# 2. Tunggu PostgreSQL siap menerima koneksi
# ----------------------------------------------------------------------------
echo "[entrypoint] Menunggu PostgreSQL di ${DB_HOST}:${DB_PORT} ..."
max=60
for i in $(seq 1 "$max"); do
    if PGPASSWORD="${DB_PASSWORD}" pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" -q; then
        echo "[entrypoint] PostgreSQL siap."
        break
    fi
    if [ "$i" -eq "$max" ]; then
        echo "[entrypoint] WARNING: PostgreSQL belum siap setelah ${max}s, lanjut tetap menjalankan app."
    fi
    sleep 1
done

# ----------------------------------------------------------------------------
# 3. Generate APP_KEY bila masih kosong
# ----------------------------------------------------------------------------
if ! grep -q "^APP_KEY=." .env || grep -q "^APP_KEY=$" .env; then
    echo "[entrypoint] APP_KEY kosong, menjalankan php artisan key:generate"
    php artisan key:generate --force
fi

# ----------------------------------------------------------------------------
# 4. Migrate + seed.
#    - Deteksi database baru (tabel `migrations` belum ada) SEBELUM migrate,
#      karena `migrate` sendiri akan membuat tabel tsb.
#    - `migrate --force` hanya menjalankan migration yang belum dijalankan
#      (idempoten, aman dijalankan tiap start container).
#    - Seeder hanya dijalankan sekali: ketika database benar-benar baru.
# ----------------------------------------------------------------------------
db_has_migrations_table() {
    PGPASSWORD="${DB_PASSWORD}" psql -h "${DB_HOST}" -p "${DB_PORT}" \
        -U "${DB_USERNAME}" -d "${DB_DATABASE}" -tAc \
        "SELECT 1 FROM information_schema.tables WHERE table_name = 'migrations' LIMIT 1;" 2>/dev/null \
        | grep -q 1
}

FRESH_DB=0
if ! db_has_migrations_table; then
    FRESH_DB=1
    echo "[entrypoint] Database baru terdeteksi (belum ada tabel migrations)."
fi

echo "[entrypoint] Menjalankan php artisan migrate --force ..."
php artisan migrate --force || {
    echo "[entrypoint] WARNING: migrate gagal. Aplikasi tetap dijalankan, periksa log."
}

if [ "${FRESH_DB}" -eq 1 ]; then
    echo "[entrypoint] Database baru, menjalankan seeder ..."
    php artisan db:seed --force || true
else
    echo "[entrypoint] Database sudah ada migrasinya, skip seeder."
fi

# ----------------------------------------------------------------------------
# 5. Permission storage & cache
# ----------------------------------------------------------------------------
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Clear & cache config agar aplikasi lebih cepat (production)
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "[entrypoint] Selesai. Menjalankan: $*"
exec "$@"
