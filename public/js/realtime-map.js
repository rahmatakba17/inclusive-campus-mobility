/**
 * realtime-map.js — v3.0
 * Leaflet wrapper untuk peta bus UNHAS.
 * Mendukung 2 rute berbeda (berangkat vs pulang) dengan warna terpisah.
 */

const RealtimeMap = (() => {
    let map           = null;
    let busMarkers    = {};
    let terminalMarkers = {};
    let routeLines    = [];

    // Warna per status
    const STATUS_COLOR = {
        jalan:     '#22c55e',
        standby:   '#f59e0b',
        istirahat: '#ef4444',
    };

    // Warna label rute
    const ROUTE_COLOR = {
        go:     '#3b82f6',   // biru — berangkat
        return: '#f97316',   // oranye — pulang
    };

    // ============================================================
    //  Init peta
    // ============================================================
    function initMap(containerId = 'map-container') {
        map = L.map(containerId, {
            center:      [-5.1800, 119.4700],
            zoom:        12,
            zoomControl: true,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18,
        }).addTo(map);

        return map;
    }

    // ============================================================
    //  Gambar KEDUA rute dengan warna berbeda
    // ============================================================
    function drawRoutes(routes) {
        // Hapus rute lama
        routeLines.forEach(l => map.removeLayer(l));
        routeLines = [];

        // Rute A — Berangkat (biru solid)
        const goCoords  = routes.go.map(t => [t.lat, t.lng]);
        const goLine    = L.polyline(goCoords, {
            color:     ROUTE_COLOR.go,
            weight:    5,
            opacity:   0.85,
            dashArray: null,
        }).addTo(map);
        goLine.bindTooltip('🔵 Jalur Berangkat (Tamalanrea → Gowa)', { sticky: true });
        routeLines.push(goLine);

        // Rute B — Pulang (oranye putus-putus)
        const retCoords = routes.return.map(t => [t.lat, t.lng]);
        const retLine   = L.polyline(retCoords, {
            color:     ROUTE_COLOR.return,
            weight:    5,
            opacity:   0.8,
            dashArray: '10 6',
        }).addTo(map);
        retLine.bindTooltip('🟠 Jalur Pulang (Gowa → Tamalanrea)', { sticky: true });
        routeLines.push(retLine);

        // Fit bounds ke kedua rute
        const group = L.featureGroup([goLine, retLine]);
        map.fitBounds(group.getBounds(), { padding: [40, 40] });
    }

    // ============================================================
    //  Gambar terminal / halte
    // ============================================================
    function drawTerminals(terminals) {
        const TYPE_COLOR = {
            origin:      '#1e3a5f',
            destination: '#c41e3a',
            stop:        '#6b7280',
        };

        terminals.forEach(terminal => {
            if (terminalMarkers[terminal.code]) return; // jangan duplikat

            const color = TYPE_COLOR[terminal.type] || '#6b7280';
            const big   = terminal.type === 'origin' || terminal.type === 'destination';
            const size  = big ? 30 : 22;

            const icon  = L.divIcon({
                className: '',
                html: `
                    <div style="
                        width:${size}px; height:${size}px;
                        background:${color}; border:3px solid #fff;
                        border-radius:50%; display:flex; align-items:center; justify-content:center;
                        box-shadow:0 2px 8px rgba(0,0,0,0.35); cursor:pointer;
                    ">
                        <svg width="${size - 10}" height="${size - 10}" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        </svg>
                    </div>`,
                iconSize:   [size, size],
                iconAnchor: [size / 2, size],
            });

            const typeLabel = { origin: '🚏 Terminal Awal', destination: '🏁 Terminal Akhir', stop: '🚌 Halte' };

            terminalMarkers[terminal.code] = L.marker([terminal.lat, terminal.lng], { icon })
                .addTo(map)
                .bindPopup(`
                    <div style="font-family:sans-serif; min-width:180px;">
                        <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                            ${typeLabel[terminal.type] ?? '📍 Halte'}
                        </div>
                        <div style="font-size:15px;font-weight:900;color:#1e3a5f;">${terminal.name}</div>
                        ${terminal.description ? `<div style="font-size:12px;color:#6b7280;margin-top:4px;">${terminal.description}</div>` : ''}
                    </div>
                `, { maxWidth: 250 });
        });
    }

    // ============================================================
    //  Update / buat marker bus
    // ============================================================
    function updateBusMarkers(buses) {
        buses.forEach(bus => {
            if (!bus.lat || !bus.lng) return;

            // rest_gowa adalah bus istirahat di Gowa yg masih bisa dipesan (bukan 'istirahat' biasa)
            const isRestGowa = bus.direction === 'rest_gowa';
            const effectiveStatus = isRestGowa ? 'standby' : bus.trip_status;
            const color      = STATUS_COLOR[effectiveStatus] || '#6b7280';
            const busNum     = String(bus.bus_number).padStart(2, '0');
            const isMoving   = bus.trip_status === 'jalan';
            const isGoing    = bus.direction === 'go';    // berangkat → biru
            const isReturn   = bus.direction === 'return'; // pulang → oranye

            // Warna body bus: biru jika berangkat, oranye jika pulang, abu jika antri, kuning jika standby/rest_gowa
            let bodyColor = color;
            if (isGoing)     bodyColor = ROUTE_COLOR.go;
            if (isReturn)    bodyColor = ROUTE_COLOR.return;
            if (isRestGowa)  bodyColor = STATUS_COLOR.standby; // amber: standby di Gowa

            // Arrow arah perjalanan
            const arrow = isGoing
                ? `<span style="font-size:9px;opacity:0.9;">↓</span>`
                : isReturn
                  ? `<span style="font-size:9px;opacity:0.9;">↑</span>`
                  : '';

            // === BADGE ICON LOGIKA ===
            // Sopir: HANYA tampil jika sopir yang sedang login adalah pengemudi bus ini
            // Penumpang: tampil jika user_has_booking && bus belum sampai tujuan
            const showDriverIcon = bus.current_user_is_driver;
            const showPassengerIcon = bus.user_has_booking;

            // Tentukan posisi badge (kiri=sopir, kanan=penumpang, atau masing-masing jika sendirian)
            // Sopir → atas-kiri, kuning, ikon setir
            const driverBadge = showDriverIcon ? `
                <div style="
                    position:absolute; top:-8px; left:-8px;
                    background:#f59e0b; border-radius:50%;
                    width:18px; height:18px;
                    display:flex; align-items:center; justify-content:center;
                    border:2px solid #fff;
                    box-shadow:0 2px 6px rgba(245,158,11,0.6);
                    z-index:10;
                " title="Sopir sedang mengemudi">
                    <i class="fas fa-steering-wheel" style="font-size:8px; color:#fff;"></i>
                </div>` : '';

            // Penumpang → atas-kanan, merah, ikon orang
            const passengerBadge = showPassengerIcon ? `
                <div style="
                    position:absolute; top:-8px; right:-8px;
                    background:#ef4444; border-radius:50%;
                    width:18px; height:18px;
                    display:flex; align-items:center; justify-content:center;
                    border:2px solid #fff;
                    box-shadow:0 2px 6px rgba(239,68,68,0.5);
                    z-index:10;
                " title="Anda ada di bus ini">
                    <i class="fas fa-user" style="font-size:8px; color:#fff;"></i>
                </div>` : '';

            const icon = L.divIcon({
                className: '',
                html: `
                    <div style="position:relative; display:inline-flex; height:30px;">
                        ${isMoving ? `
                        <div style="
                            position:absolute; inset:-5px;
                            border-radius:18px;
                            background:${bodyColor}40;
                            animation:busping 1.5s infinite;
                        "></div>` : ''}
                        <div style="
                            position:relative; z-index:2;
                            background:${bodyColor};
                            border:2px solid rgba(255,255,255,0.9);
                            border-radius:18px;
                            display:flex; align-items:center; justify-content:center; gap:4px;
                            box-shadow:0 3px 10px rgba(0,0,0,0.4);
                            font-size:11px; font-weight:900; color:#fff;
                            padding:0 9px; height:100%; white-space:nowrap; letter-spacing:0.5px;
                        ">
                            <i class="fas fa-bus-alt" style="font-size:10px;opacity:0.85;"></i>
                            ${busNum}${arrow}
                            ${driverBadge}
                            ${passengerBadge}
                        </div>
                    </div>`,
                iconSize:   [64, 30],
                iconAnchor: [32, 15],
            });

            // Popup content
            const statusLabel = {
                jalan:     isGoing   ? '🔵 Berangkat ke Gowa'      : '🟠 Pulang ke Tamalanrea',
                standby:   bus.direction === 'queue'     ? '🕐 Antri – Menunggu Giliran'
                         : bus.direction === 'rest_tamal' ? '🟡 Standby di Terminal Tamalanrea'
                         : bus.direction === 'rest_gowa'  ? '🟡 Standby di Terminal Gowa'
                         : '🟡 Standby di Terminal',
                istirahat: '🔴 Istirahat',
            };

            const etaText = bus.eta_minutes != null
                ? `<div style="margin-top:5px;font-size:12px;color:#6b7280;">
                       ⏱ ${(bus.trip_status === 'standby' && bus.direction === 'queue') ? 'Berangkat dalam' : 'ETA'}: <b>${bus.eta_minutes} mnt</b>
                   </div>`
                : '';

            const routeInfo = isMoving
                ? `<div style="font-size:12px;color:#4b5563;margin-top:4px;">📍 ${bus.from_terminal} → ${bus.next_terminal}</div>`
                : bus.current_terminal
                  ? `<div style="font-size:12px;color:#4b5563;margin-top:4px;">📍 ${bus.current_terminal}</div>`
                  : '';

            // Badge labels di popup
            const driverLabel = showDriverIcon
                ? `<span style="background:#f59e0b;color:white;font-size:8px;padding:2px 5px;border-radius:4px;margin-left:4px;vertical-align:middle;display:inline-flex;align-items:center;gap:3px;">
                       <i class="fas fa-steering-wheel"></i> ${bus.current_user_is_driver ? 'ANDA (SOPIR)' : 'SOPIR'}
                   </span>`
                : '';

            const passengerLabel = showPassengerIcon
                ? `<span style="background:#ef4444;color:white;font-size:8px;padding:2px 5px;border-radius:4px;margin-left:4px;vertical-align:middle;display:inline-flex;align-items:center;gap:3px;">
                       <i class="fas fa-user"></i> ANDA
                   </span>`
                : '';

            const popupContent = `
                <div style="font-family:sans-serif;min-width:210px;padding:4px;">
                    <div style="font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;">${bus.bus_code ?? 'BUS-' + busNum}</div>
                    <div style="font-size:16px;font-weight:900;color:#1e3a5f;margin:2px 0;display:flex;align-items:center;flex-wrap:wrap;gap:4px;">
                        ${bus.name}
                        ${driverLabel}
                        ${passengerLabel}
                    </div>
                    <div style="font-size:11px;color:#6b7280;margin-bottom:6px;">🚗 ${bus.driver_name ?? '—'} &bull; ${bus.plate ?? '—'}</div>
                    <div style="font-size:13px;font-weight:700;color:${bodyColor};">${statusLabel[bus.trip_status] ?? bus.trip_status}</div>
                    ${routeInfo}
                    ${etaText}
                    ${isMoving && bus.booked_passengers != null ? `
                    <div style="margin-top:5px;font-size:12px;color:#3b82f6;font-weight:700;">
                        👥 ${bus.booked_passengers} penumpang dalam bus
                    </div>` : ''}
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #f1f5f9;display:flex;gap:8px;justify-content:space-between;">
                        <span style="font-size:12px;color:#1e3a5f;font-weight:700;">💺 Sisa: ${bus.db_available ?? '?'}</span>
                        <span style="font-size:11px;color:${bus.is_bookable ? '#22c55e' : '#ef4444'};font-weight:700;">
                            ${bus.is_bookable 
                                ? (isRestGowa ? '✅ Bisa Dipesan (→ Perintis)' : '✅ Bisa Dipesan')
                                : '🚫 Tidak Bisa Dipesan'}
                        </span>
                    </div>
                </div>`;

            if (busMarkers[bus.id]) {
                busMarkers[bus.id].setLatLng([bus.lat, bus.lng]);
                busMarkers[bus.id].setIcon(icon);
                busMarkers[bus.id].setPopupContent(popupContent);
            } else {
                busMarkers[bus.id] = L.marker([bus.lat, bus.lng], { icon })
                    .addTo(map)
                    .bindPopup(popupContent, { maxWidth: 280 });
            }
        });
    }

    function getMap() { return map; }

    return { initMap, drawRoutes, drawTerminals, updateBusMarkers, getMap };
})();

// Animasi ping bus bergerak
const _mapStyle = document.createElement('style');
_mapStyle.textContent = `
    @keyframes busping {
        0%   { transform:scale(1); opacity:0.7; }
        70%  { transform:scale(2.2); opacity:0; }
        100% { transform:scale(1); opacity:0; }
    }
`;
document.head.appendChild(_mapStyle);

window.RealtimeMap = RealtimeMap;
