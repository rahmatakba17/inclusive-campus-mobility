/**
 * bus-tracker.js — Adaptive Polling + Smooth Leaflet Marker Animation
 * Bus Kampus UNHAS v3.1 | Performance Optimized
 *
 * Fitur:
 *  - Adaptive Polling Interval (Page Visibility API)
 *  - ETag-based berubah-deteksi (tidak re-render jika data sama)
 *  - Smooth marker animation (lerp sliding, bukan destroy/recreate)
 *  - Batching DOM update (requestAnimationFrame)
 *  - Singleton marker registry (tidak buat marker baru setiap poll)
 */

// ── Singleton Marker Registry ─────────────────────────────────────────────────
// Menyimpan marker Leaflet agar tidak di-destroy dan re-create setiap poll.
// { bus_id: { marker: L.Marker, lat: float, lng: float } }
const busMarkers = {};

// ── Leaflet Map Instance (inisialisasi di luar fungsi poll) ───────────────────
let map = null;

function initMap(centerLat = -5.137, centerLng = 119.432) {
    map = L.map('bus-map', {
        center: [centerLat, centerLng],
        zoom: 14,
        // Matikan animasinya yang expensive pada tile loading
        fadeAnimation: true,
        zoomAnimation: true,
        markerZoomAnimation: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        // Aktifkan tile caching agresif di browser
        keepBuffer: 4,
        updateWhenIdle: false,
        updateWhenZooming: false,
    }).addTo(map);
}

// ── Smooth Marker Animation (Lerp / Linear Interpolation) ────────────────────
// Daripada langsung teleport marker ke koordinat baru (yang menyebabkan jank),
// kita geser perlahan selama ~800ms menggunakan requestAnimationFrame.
function animateMarkerTo(marker, targetLat, targetLng, durationMs = 800) {
    const startLat = marker.getLatLng().lat;
    const startLng = marker.getLatLng().lng;
    const startTime = performance.now();

    // Jika jarak sangat kecil (< 0.0001 derajat ≈ ~11m), langsung set — skip animasi
    if (Math.abs(targetLat - startLat) < 0.0001 && Math.abs(targetLng - startLng) < 0.0001) {
        marker.setLatLng([targetLat, targetLng]);
        return;
    }

    function step(currentTime) {
        const elapsed  = currentTime - startTime;
        const progress = Math.min(elapsed / durationMs, 1); // 0.0 → 1.0

        // Ease-out cubic untuk gerakan natural (melambat di akhir)
        const ease = 1 - Math.pow(1 - progress, 3);

        const lat = startLat + (targetLat - startLat) * ease;
        const lng = startLng + (targetLng - startLng) * ease;

        marker.setLatLng([lat, lng]);

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    }

    requestAnimationFrame(step);
}

// ── Batch DOM Update via rAF ──────────────────────────────────────────────────
// Semua perubahan UI (panel kartu bus, status badge) dikumpulkan dan diupdate
// dalam satu frame — mencegah layout thrashing / reflow berulang.
function updateBusCard(bus) {
    const card = document.getElementById(`bus-card-${bus.id}`);
    if (!card) return;

    // Baca nilai lama sebelum menulis (mencegah forced layout)
    const prevStatus = card.dataset.tripStatus;
    const prevSeats  = card.dataset.availableSeats;

    // Hanya update DOM jika ada perubahan nyata
    if (prevStatus === bus.trip_status && prevSeats === String(bus.available_seats)) {
        return; // Skip — tidak ada yang berubah
    }

    // Tulis semua perubahan dalam satu batch (tidak query DOM lagi)
    requestAnimationFrame(() => {
        card.dataset.tripStatus    = bus.trip_status;
        card.dataset.availableSeats = bus.available_seats;

        const statusEl = card.querySelector('[data-role="status-badge"]');
        const seatsEl  = card.querySelector('[data-role="seats-count"]');

        if (statusEl) statusEl.textContent = bus.trip_status_label;
        if (seatsEl)  seatsEl.textContent  = bus.available_seats;
    });
}

// ── Adaptive Polling Engine ───────────────────────────────────────────────────
const POLL_INTERVAL_ACTIVE   = 2000;  // 2 detik saat tab aktif
const POLL_INTERVAL_HIDDEN   = 15000; // 15 detik saat tab background
const POLL_INTERVAL_INACTIVE = 8000;  // 8 detik saat user tidak interaksi

let pollTimer     = null;
let lastEtag      = null;
let isPolling     = false;

function getCurrentInterval() {
    if (document.hidden) return POLL_INTERVAL_HIDDEN;
    return POLL_INTERVAL_ACTIVE;
}

async function pollBuses() {
    if (isPolling) return; // Hindari concurrent request
    isPolling = true;

    try {
        const headers = { 'Accept': 'application/json' };
        if (lastEtag) headers['If-None-Match'] = lastEtag;

        const res = await fetch('/api/poll/buses', {
            headers,
            signal: AbortSignal.timeout(5000), // Timeout 5 detik
        });

        // 304 Not Modified — data tidak berubah, tidak perlu update UI
        if (res.status === 304) {
            scheduleNextPoll();
            return;
        }

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        lastEtag = res.headers.get('ETag');
        const data = await res.json();

        // Update peta dan panel — dijamin berjalan di RAF batch
        processBusData(data.buses);

    } catch (err) {
        if (err.name !== 'AbortError') {
            console.warn('[BusTracker] Poll error:', err.message);
        }
    } finally {
        isPolling = false;
        scheduleNextPoll();
    }
}

function scheduleNextPoll() {
    clearTimeout(pollTimer);
    pollTimer = setTimeout(pollBuses, getCurrentInterval());
}

function processBusData(buses) {
    buses.forEach(bus => {
        // ── Update atau Buat Marker Map ──────────────────────────
        if (bus.current_lat && bus.current_lng && map) {
            if (busMarkers[bus.id]) {
                // Marker sudah ada → animasikan ke posisi baru (smooth, tidak recreate)
                animateMarkerTo(busMarkers[bus.id].marker, bus.current_lat, bus.current_lng);
            } else {
                // Marker baru → buat sekali, simpan di registry
                const icon = L.divIcon({
                    className: '',
                    html: `<div class="bus-dot" data-status="${bus.trip_status}" title="${bus.name}"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                });
                const marker = L.marker([bus.current_lat, bus.current_lng], { icon }).addTo(map);
                marker.bindPopup(`<b>${bus.name}</b><br>${bus.trip_status_label}`);
                busMarkers[bus.id] = { marker, lat: bus.current_lat, lng: bus.current_lng };
            }
        }

        // ── Update UI Panel Bus (kartu / list) ───────────────────
        updateBusCard(bus);
    });
}

// ── Page Visibility API — Adaptive Interval ───────────────────────────────────
document.addEventListener('visibilitychange', () => {
    clearTimeout(pollTimer);
    if (document.hidden) {
        // Tab disembunyikan → perlambat polling secara drastis
        pollTimer = setTimeout(pollBuses, POLL_INTERVAL_HIDDEN);
    } else {
        // Tab aktif kembali → langsung poll sekarang
        pollBuses();
    }
});

// ── Init ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('bus-map')) {
        initMap();
    }
    pollBuses(); // Poll pertama langsung tanpa delay
});
