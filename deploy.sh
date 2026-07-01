#!/usr/bin/env bash
# =============================================================================
# deploy.sh - Skrip deployment "loket-majuhardware" di server Linux (Docker)
#
# Cara pakai:
#   ./deploy.sh                pull image dari Docker Hub & start container
#   ./deploy.sh --build        build image dari source (tanpa pull) & start
#   ./deploy.sh up             sama seperti default (pull + start)
#   ./deploy.sh down           hentikan container (data volume tetap)
#   ./deploy.sh restart        restart container
#   ./deploy.sh logs           tail log aplikasi (Ctrl+C untuk keluar)
#   ./deploy.sh status         tampilkan status container & URL akses
#   ./deploy.sh update         pull image terbaru dari Docker Hub & restart
#   ./deploy.sh build-only     build image saja tanpa start
#
# Environment (opsional, bisa di-set sebelum menjalankan):
#   SKIP_DOCKER_INSTALL=1      jangan auto-install Docker bila belum ada
#   COMPOSE_FILE=docker-compose.yaml   override nama file compose
# =============================================================================
set -euo pipefail

# ----------------------- konfigurasi ---------------------------------------
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yaml}"
: "${SKIP_DOCKER_INSTALL:=0}"

# Warna output
C_GREEN="\033[0;32m"; C_YELLOW="\033[1;33m"; C_RED="\033[0;31m"
C_CYAN="\033[0;36m"; C_BOLD="\033[1m"; C_RESET="\033[0m"

log()   { echo -e "${C_GREEN}[deploy]${C_RESET} $*"; }
warn()  { echo -e "${C_YELLOW}[deploy]${C_RESET} $*"; }
err()   { echo -e "${C_RED}[deploy ERROR]${C_RESET} $*" >&2; }
step()  { echo -e "\n${C_CYAN}${C_BOLD}==> $*${C_RESET}"; }

# ----------------------- cek Docker ----------------------------------------
ensure_docker() {
    if command -v docker >/dev/null 2>&1; then
        return 0
    fi

    if [ "${SKIP_DOCKER_INSTALL}" = "1" ]; then
        err "Docker tidak ditemukan dan SKIP_DOCKER_INSTALL=1. Install manual: https://docs.docker.com/engine/install/"
        exit 1
    fi

    warn "Docker belum terinstall. Menginstall otomatis via get.docker.com ..."
    if ! command -v curl >/dev/null 2>&1; then
        # fallback install curl (Debian/Ubuntu & RHEL)
        if command -v apt-get >/dev/null 2>&1; then
            sudo apt-get update -y && sudo apt-get install -y curl ca-certificates
        elif command -v dnf >/dev/null 2>&1; then
            sudo dnf install -y curl
        elif command -v yum >/dev/null 2>&1; then
            sudo yum install -y curl
        else
            err "Tidak ada package manager dikenali (apt/dnf/yum). Install Docker manual."
            exit 1
        fi
    fi

    curl -fsSL https://get.docker.com | sudo sh
    sudo systemctl enable --now docker

    # Tambah user saat ini ke grup docker agar tanpa sudo (efektif setelah relogin)
    if id -nG "$(whoami)" 2>/dev/null | grep -qw docker; then
        :
    else
        sudo usermod -aG docker "$(whoami)" || true
        warn "User ditambahkan ke grup docker. Jalankan 'newgrp docker' atau logout+login agar tanpa sudo."
    fi
}

# Pastikan docker bisa diakses (pakai sudo bila perlu)
DC() {
    if docker info >/dev/null 2>&1; then
        docker compose -f "$COMPOSE_FILE" "$@"
    else
        sudo docker compose -f "$COMPOSE_FILE" "$@"
    fi
}

# ----------------------- validasi ------------------------------------------
check_compose_file() {
    if [ ! -f "$COMPOSE_FILE" ]; then
        err "File $COMPOSE_FILE tidak ditemukan di $SCRIPT_DIR"
        exit 1
    fi
}

# ----------------------- aksi ----------------------------------------------
do_up() {
    check_compose_file
    step "Mempersiapkan container"
    log "Pull image (jika belum ada) ..."
    DC pull --ignore-pull-failures 2>/dev/null || warn "Sebagian image gagal di-pull (mungkin akan di-build lokal)."
    log "Menjalankan docker compose up -d ..."
    DC up -d
    do_status
}

do_up_build() {
    check_compose_file
    step "Build image dari source & start"
    DC up -d --build
    do_status
}

do_down()        { check_compose_file; step "Menghentikan container"; DC down; }
do_restart()     { check_compose_file; step "Restart container"; DC restart; }
do_build_only()  { check_compose_file; step "Build image"; DC build; }
do_logs()        { check_compose_file; step "Log (Ctrl+C untuk keluar)"; DC logs -f app; }

do_update() {
    check_compose_file
    step "Update: pull image terbaru & restart"
    DC pull
    DC up -d
    log "Update selesai."
    do_status
}

do_status() {
    step "Status container"
    DC ps || true
    echo ""
    echo -e "${C_BOLD}Akses aplikasi:${C_RESET}"
    echo -e "  - Aplikasi : ${C_CYAN}http://$(hostname -I 2>/dev/null | awk '{print $1}' || echo localhost):8080${C_RESET}"
    echo -e "  - Kiosk    : http://<IP-SERVER>:8080/uknown_5"
    echo -e "  - Dashboard: http://<IP-SERVER>:8080/antrian"
    echo -e "  - Admin    : http://<IP-SERVER>:8080/home"
    echo ""
    warn "Buat akun admin pertama:  ./deploy.sh shell  lalu 'php artisan tinker'"
    echo ""
}

do_shell() {
    check_compose_file
    log "Masuk shell container app ..."
    DC exec app bash
}

do_help() {
    sed -n '3,20p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//'
}

# ----------------------- main ----------------------------------------------
main() {
    local cmd="${1:-up}"
    case "$cmd" in
        up)          do_up ;;
        --build|build) do_up_build ;;
        build-only)  do_build_only ;;
        down|stop)   do_down ;;
        restart)     do_restart ;;
        logs)        do_logs ;;
        status|ps)   do_status ;;
        update)      do_update ;;
        shell|exec)  do_shell ;;
        -h|--help|help) do_help ;;
        *)
            err "Perintah tidak dikenal: $cmd"
            do_help
            exit 1
            ;;
    esac
}

ensure_docker
main "$@"
