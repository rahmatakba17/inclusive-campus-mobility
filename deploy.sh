#!/usr/bin/env bash
# ============================================================
#  deploy.sh — Bus Kampus UNHAS v3.1 Production Deploy Script
#  Jalankan di server setelah git pull:
#    chmod +x deploy.sh && ./deploy.sh
# ============================================================

set -e  # Hentikan script jika ada error

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log()  { echo -e "${GREEN}[✔] $1${NC}"; }
warn() { echo -e "${YELLOW}[!] $1${NC}"; }
err()  { echo -e "${RED}[✘] $1${NC}"; exit 1; }

log "===== STARTING BUS KAMPUS PRODUCTION DEPLOY ====="

# ── 1. Aktifkan Maintenance Mode ────────────────────────────
log "Mengaktifkan maintenance mode..."
php artisan down --secret="bus-kampus-bypass-2025" || warn "Maintenance mode sudah aktif."

# ── 2. Install / Update PHP Dependencies ────────────────────
log "Menginstal PHP packages (production only, no dev)..."
composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

# ── 3. Build Frontend Assets ────────────────────────────────
log "Building frontend assets (Vite + TailwindCSS)..."
npm ci --omit=dev        # lebih cepat & deterministik dari npm install
npm run build

# ── 4. Run Database Migrations ─────────────────────────────
log "Menjalankan migrasi database..."
php artisan migrate --force

# ── 5. Cache Semuanya untuk Production ─────────────────────
log "Caching konfigurasi, route, view, dan events..."

php artisan config:cache    # Cache config/*.php → satu file PHP
php artisan route:cache     # Cache semua route → resolusi O(1)
php artisan view:cache      # Pre-compile semua Blade view
php artisan event:cache     # Cache event listener registration

# ── 6. OPCache Optimization ─────────────────────────────────
log "Mengoptimasi autoloader & OPCache preload..."
php artisan optimize        # Jalankan config:cache + route:cache + view:cache sekaligus

# ── 7. Symlink Storage ──────────────────────────────────────
log "Memastikan storage symlink tersedia..."
php artisan storage:link --force 2>/dev/null || warn "Symlink sudah ada, dilewati."

# ── 8. Set Permission yang Benar ────────────────────────────
log "Setting permission storage & bootstrap/cache..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
    warn "Tidak bisa chown (mungkin tidak punya akses root — abaikan di shared hosting)."

# ── 9. Bersihkan Old Cache Stale ────────────────────────────
log "Membersihkan file cache lama..."
php artisan cache:clear        # Bersihkan app cache (key-value)
php artisan view:clear         # Hapus compiled view lama sebelum di-cache ulang
php artisan config:clear       # Hapus config cache lama
php artisan optimize           # Re-build semua cache bersih

# ── 10. Tutup Maintenance Mode ──────────────────────────────
log "Menonaktifkan maintenance mode..."
php artisan up

log "===== DEPLOY SELESAI! APLIKASI ONLINE ====="
echo ""
echo -e "${YELLOW}[TIPS] Untuk akses saat maintenance mode aktif, tambahkan ?secret=bus-kampus-bypass-2025 ke URL Anda.${NC}"
