#!/usr/bin/env bash
# =============================================================================
# serve.sh - Menjalankan aplikasi Laravel (loket-majuhardware) secara NATIVE
# (php artisan serve), TANPA Docker.
#
# Cara pakai:
#   ./serve.sh serve      jalankan langsung di foreground (Ctrl+C untuk stop)
#   ./serve.sh install    pasang service systemd (auto-start saat server boot)
#   ./serve.sh uninstall  lepas service systemd
#   ./serve.sh start      start service (butuh 'install' dulu)
#   ./serve.sh stop       stop service
#   ./serve.sh restart    restart service
#   ./serve.sh status     lihat status service
#   ./serve.sh logs       tail log service (Ctrl+C keluar)
#   ./serve.sh migrate    jalankan php artisan migrate
#   ./serve.sh tinker     php artisan tinker (buat akun admin, dll)
#
# Override host/port via environment:  APP_PORT=8000 ./serve.sh serve
# =============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

SERVICE_NAME="loket-majuhardware-app"
SERVICE_FILE="/etc/systemd/system/${SERVICE_NAME}.service"
TEMPLATE="${SCRIPT_DIR}/loket-majuhardware-app.service"

APP_HOST="${APP_HOST:-0.0.0.0}"
APP_PORT="${APP_PORT:-8000}"

C_G="\033[0;32m"; C_Y="\033[1;33m"; C_R="\033[0;31m"; C_C="\033[0;36m"; C_B="\033[1m"; C_X="\033[0m"
log()  { echo -e "${C_G}[serve]${C_X} $*"; }
warn() { echo -e "${C_Y}[serve]${C_X} $*"; }
err()  { echo -e "${C_R}[serve]${C_X} $*" >&2; }

sudo_sh() { if [ "$(id -u)" -eq 0 ]; then "$@"; else sudo "$@"; fi; }

preflight() {
    command -v php >/dev/null 2>&1 || { err "PHP tidak ditemukan. Jalankan ./setup.sh dulu."; exit 1; }
    [ -f .env ] || { err ".env tidak ada. Jalankan ./setup.sh atau: cp .env.example .env && php artisan key:generate"; exit 1; }
}

service_installed() { [ -f "$SERVICE_FILE" ]; }

# Jalankan langsung tanpa systemd (foreground)
do_serve() {
    preflight
    log "Menjalankan: php artisan serve --host=${APP_HOST} --port=${APP_PORT}"
    echo -e "    ${C_C}Buka: http://$(hostname -I 2>/dev/null | awk '{print $1}' || echo localhost):${APP_PORT}${C_X}  (Ctrl+C untuk berhenti)"
    exec php artisan serve --host="$APP_HOST" --port="$APP_PORT"
}

# Generate & pasang unit systemd dari template (isi path/user/php aktual)
do_install() {
    preflight
    [ -f "$TEMPLATE" ] || { err "Template $TEMPLATE tidak ditemukan."; exit 1; }

    local tmp user phpbin
    user="$(whoami)"
    phpbin="$(command -v php)"
    tmp="$(mktemp)"
    sed -e "s|__WORKDIR__|${SCRIPT_DIR}|g" \
        -e "s|__USER__|${user}|g" \
        -e "s|__PHP__|${phpbin}|g" \
        -e "s|__HOST__|${APP_HOST}|g" \
        -e "s|__PORT__|${APP_PORT}|g" \
        "$TEMPLATE" > "$tmp"

    sudo_sh install -m 0644 "$tmp" "$SERVICE_FILE"
    rm -f "$tmp"
    sudo_sh systemctl daemon-reload
    sudo_sh systemctl enable "$SERVICE_NAME"
    log "Service '${SERVICE_NAME}' terpasang & di-enable."
    warn "Jalankan:  ${C_C}./serve.sh start${C_X}"
}

do_uninstall() {
    sudo_sh systemctl disable --now "$SERVICE_NAME" 2>/dev/null || true
    sudo_sh rm -f "$SERVICE_FILE"
    sudo_sh systemctl daemon-reload
    log "Service '${SERVICE_NAME}' dilepas."
}

do_start() {
    preflight
    service_installed || { err "Service belum dipasang. Jalankan: ./serve.sh install"; exit 1; }
    sudo_sh systemctl start "$SERVICE_NAME"
    log "Started."
    do_status
}
do_stop()    { sudo_sh systemctl stop "$SERVICE_NAME" 2>/dev/null || true; log "Stopped."; }
do_restart() {
    preflight
    service_installed || { err "Service belum dipasang. Jalankan: ./serve.sh install"; exit 1; }
    sudo_sh systemctl restart "$SERVICE_NAME"
    log "Restarted."
    do_status
}
do_status()  { sudo_sh systemctl status "$SERVICE_NAME" --no-pager -l 2>/dev/null || true; }
do_logs()    { sudo_sh journalctl -u "$SERVICE_NAME" -f --no-pager; }
do_migrate() { preflight; php artisan migrate --force; }
do_tinker()  { preflight; exec php artisan tinker; }

do_help() {
    sed -n '3,21p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//'
}

main() {
    local cmd="${1:-help}"
    case "$cmd" in
        serve)              do_serve ;;
        install)            do_install ;;
        uninstall|remove)   do_uninstall ;;
        start)              do_start ;;
        stop)               do_stop ;;
        restart|reload)     do_restart ;;
        status)             do_status ;;
        logs|log)           do_logs ;;
        migrate)            do_migrate ;;
        tinker)             do_tinker ;;
        -h|--help|help)     do_help ;;
        *)
            err "Perintah tidak dikenal: $cmd"
            do_help
            exit 1
            ;;
    esac
}
main "$@"
