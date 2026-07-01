#!/usr/bin/env bash
# =============================================================================
# setup.sh - Setup SEKALI JALAN (native, tanpa Docker) untuk loket-majuhardware
# di server Linux.
#
# Yang dilakukan:
#   1. Install PHP 8.2 + ekstensi (pdo_pgsql, gd, mbstring, zip, dll)
#   2. Install Composer
#   3. Install PostgreSQL & buat database
#   4. Install Node.js (untuk build asset Vite)
#   5. Konfigurasi .env
#   6. composer install + npm run build
#   7. php artisan migrate
#   8. Set permission storage
#
# Didukung: Debian/Ubuntu (apt), RHEL/Rocky/Fedora (dnf/yum).
#
# Override konfigurasi via environment, contoh:
#   DB_PASS=rahasia APP_PORT=8000 ./setup.sh
#   SKIP_DB=1 ./setup.sh            # lewati install & setup PostgreSQL
#   PHP_VERS=8.3 ./setup.sh
# =============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ----------------------------- konfigurasi ---------------------------------
PHP_VERS="${PHP_VERS:-8.2}"
DB_NAME="${DB_NAME:-antrian_mh}"
DB_USER="${DB_USER:-postgres}"
DB_PASS="${DB_PASS:-postgres}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-5432}"
APP_HOST="${APP_HOST:-0.0.0.0}"
APP_PORT="${APP_PORT:-8000}"
: "${SKIP_DB:=0}"
: "${SKIP_NODE:=0}"

SERVER_IP="$(hostname -I 2>/dev/null | awk '{print $1}')"
APP_URL="http://${SERVER_IP:-localhost}:${APP_PORT}"

# ------------------------------- helpers -----------------------------------
C_G="\033[0;32m"; C_Y="\033[1;33m"; C_R="\033[0;31m"; C_C="\033[0;36m"; C_B="\033[1m"; C_X="\033[0m"
log()   { echo -e "${C_G}[setup]${C_X} $*"; }
warn()  { echo -e "${C_Y}[setup]${C_X} $*"; }
err()   { echo -e "${C_R}[setup ERROR]${C_X} $*" >&2; }
step()  { echo; echo -e "${C_C}${C_B}==> $*${C_X}"; }

# Jalankan sebagai root bila tidak sedang root
sudo_sh() { if [ "$(id -u)" -eq 0 ]; then "$@"; else sudo "$@"; fi; }

detect_pm() {
    if command -v apt-get >/dev/null 2>&1; then echo apt
    elif command -v dnf  >/dev/null 2>&1; then echo dnf
    elif command -v yum  >/dev/null 2>&1; then echo yum
    else echo none; fi
}

# ----------------------------- instalasi OS --------------------------------
install_php_apt() {
    step "Install PHP $PHP_VERS + ekstensi (Debian/Ubuntu)"
    sudo_sh apt-get update -y
    sudo_sh apt-get install -y ca-certificates lsb-release curl gnupg software-properties-common apt-transport-https
    # Ubuntu: tambah PPA ondrej/php bila paket versi yang diminta belum tersedia
    if [ -f /etc/os-release ] && grep -q '^ID_\(.*\)=.*ubuntu' /etc/os-release 2>/dev/null \
       && ! apt-cache search --names-only "^php$PHP_VERS-cli\$" | grep -q .; then
        sudo_sh add-apt-repository -y ppa:ondrej/php
        sudo_sh apt-get update -y
    fi
    sudo_sh apt-get install -y \
        "php$PHP_VERS-cli" "php$PHP_VERS-pgsql" "php$PHP_VERS-mbstring" \
        "php$PHP_VERS-gd"  "php$PHP_VERS-xml"   "php$PHP_VERS-zip" \
        "php$PHP_VERS-curl" "php$PHP_VERS-bcmath" "php$PHP_VERS-intl" \
        unzip
    # Jadikan versi ini default `php`
    if command -v "php$PHP_VERS" >/dev/null 2>&1; then
        sudo_sh update-alternatives --set php "$(command -v "php$PHP_VERS")" 2>/dev/null || true
    fi
}

install_php_rpm() {
    step "Install PHP + ekstensi (dnf/yum)"
    sudo_sh "${PM}" install -y \
        php php-pgsql php-mbstring php-gd php-xml php-zip php-curl php-bcmath php-intl unzip
}

install_composer() {
    if command -v composer >/dev/null 2>&1; then log "Composer sudah terinstall."; return; fi
    step "Install Composer"
    curl -sS https://getcomposer.org/installer | php
    sudo_sh install -m 0755 composer.phar /usr/local/bin/composer
    rm -f composer.phar
}

install_postgres_apt() {
    step "Install & jalankan PostgreSQL"
    sudo_sh apt-get install -y postgresql postgresql-contrib
    sudo_sh systemctl enable --now postgresql
}

install_postgres_rpm() {
    step "Install & inisialisasi PostgreSQL"
    sudo_sh "${PM}" install -y postgresql-server postgresql-contrib
    sudo_sh postgresql-setup --initdb 2>/dev/null || true
    sudo_sh systemctl enable --now postgresql
}

install_node() {
    [ "$SKIP_NODE" = "1" ] && { warn "SKIP_NODE=1 -> lewati Node."; return; }
    if command -v npm >/dev/null 2>&1; then log "Node/npm sudah ada ($(node -v))."; return; fi
    step "Install Node.js 20.x (untuk build asset Vite)"
    if curl -fsSL https://deb.nodesource.com/setup_20.x | sudo_sh -E bash - 2>/dev/null; then
        sudo_sh apt-get install -y nodejs 2>/dev/null || sudo_sh dnf install -y nodejs 2>/dev/null || true
    fi
    command -v npm >/dev/null 2>&1 || warn "npm belum tersedia -> build asset akan di-skip (pastikan public/build sudah ada)."
}

# ----------------------------- setup database ------------------------------
setup_db() {
    if [ "$SKIP_DB" = "1" ]; then warn "SKIP_DB=1 -> lewati setup PostgreSQL."; return; fi
    step "Setup database PostgreSQL ($DB_NAME)"

    local role_exists db_exists
    role_exists="$(sudo_sh -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='${DB_USER}'" 2>/dev/null || true)"
    if [ "${role_exists// /}" != "1" ]; then
        sudo_sh -u postgres psql -c "CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASS}';" || warn "Gagal membuat role ${DB_USER}."
    else
        sudo_sh -u postgres psql -c "ALTER ROLE ${DB_USER} WITH LOGIN PASSWORD '${DB_PASS}';" || true
    fi

    db_exists="$(sudo_sh -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'" 2>/dev/null || true)"
    if [ "${db_exists// /}" != "1" ]; then
        sudo_sh -u postgres createdb "$DB_NAME" || warn "Gagal membuat database ${DB_NAME}."
    fi
    sudo_sh -u postgres psql -d "$DB_NAME" -c "GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};" || true

    warn "Jika app nanti gagal konek via TCP (127.0.0.1), edit pg_hba.conf agar host all all 127.0.0.1/32 md5, lalu: sudo systemctl restart postgresql"
}

# --------------------------- konfigurasi aplikasi --------------------------
set_env() {
    if grep -q "^${1}=" .env; then sed -i -E "s|^${1}=.*|${1}=${2}|" .env
    else echo "${1}=${2}" >> .env; fi
}

configure_app() {
    step "Konfigurasi .env"
    [ -f .env ] || cp .env.example .env
    set_env APP_NAME "Loket Maju Hardware"
    set_env APP_ENV production
    set_env APP_DEBUG false
    set_env APP_URL "$APP_URL"
    set_env DB_CONNECTION pgsql
    set_env DB_HOST "$DB_HOST"
    set_env DB_PORT "$DB_PORT"
    set_env DB_DATABASE "$DB_NAME"
    set_env DB_USERNAME "$DB_USER"
    set_env DB_PASSWORD "$DB_PASS"

    if ! grep -q "^APP_KEY=." .env || grep -q "^APP_KEY=$" .env; then
        php artisan key:generate --force
    fi
}

install_deps() {
    step "Install dependency PHP (composer)"
    composer install --no-interaction --optimize-autoloader --no-progress

    if command -v npm >/dev/null 2>&1; then
        step "Build asset frontend (Vite)"
        npm install --no-audit --no-fund
        npm run build
    else
        warn "npm tidak ada -> skip build. Pastikan direktori public/build sudah berisi asset."
    fi
}

run_migrate() {
    step "Jalankan migration"
    php artisan migrate --force || warn "Migrate belum berhasil (kemungkinan DB belum siap). Jalankan manual: php artisan migrate"
}

fix_perms() {
    step "Set permission storage & cache"
    mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
    chmod -R 775 storage bootstrap/cache
}

# ----------------------------------- main ----------------------------------
main() {
    PM="$(detect_pm)"
    if [ "$PM" = "none" ]; then
        err "Tidak mendeteksi apt/dnf/yum. Distro tidak didukung otomatis — install dependency manual."
        exit 1
    fi
    step "Package manager terdeteksi: $PM"

    case "$PM" in
        apt) install_php_apt;   install_postgres_apt ;;
        dnf|yum) install_php_rpm; install_postgres_rpm ;;
    esac
    install_composer
    install_node
    setup_db
    configure_app
    install_deps
    run_migrate
    fix_perms

    step "SELESAI"
    log "Untuk menjalankan aplikasi:"
    echo -e "    ${C_C}./serve.sh serve${C_X}        # jalan langsung (foreground, Ctrl+C stop)"
    echo -e "    ${C_C}./serve.sh install${C_X}      # pasang service systemd (auto-start saat boot)"
    echo -e "    ${C_C}./serve.sh start${C_X}        # start service (setelah install)"
    echo
    log "Akses aplikasi: ${C_B}${APP_URL}${C_X}"
    echo -e "    Kiosk    : ${APP_URL}/uknown_5"
    echo -e "    Dashboard: ${APP_URL}/antrian"
    echo -e "    Admin    : ${APP_URL}/home  (buat akun via 'php artisan tinker')"
}
main "$@"
