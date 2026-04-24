<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Real-Time Bus Kampus Non-Merdeka</title>
    <meta name="description" content="Pantau posisi dan status 13 armada Bus Kampus Kampus Non-Merdeka secara real-time.">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/css/leaflet.css') }}" />
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    {{-- Icons --}}
    <link rel="stylesheet" href="{{ asset('vendor/css/all.min.css') }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0f1923; color: #f8fafc; height: 100dvh; overflow: hidden; display: flex; flex-direction: column; }

        /* TOPBAR */
        .topbar {
            background: linear-gradient(135deg, #1e3a5f, #0f2137);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 12px 20px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0; z-index: 1000;
        }
        .topbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .topbar-brand img { width: 32px; height: 32px; }
        .topbar-brand .title { font-weight: 900; font-size: 15px; color: #fff; }
        .topbar-brand .subtitle { font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; }
        .topbar-actions { display: flex; gap: 8px; align-items: center; }
        .btn-sm {
            padding: 7px 14px; border-radius: 10px; font-size: 11px; font-weight: 700;
            border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.08);
            color: #fff; text-decoration: none; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; gap: 6px;
        }
        .btn-sm:hover { background: rgba(255,255,255,0.18); }
        .btn-sm.active { background: #c41e3a; border-color: #c41e3a; }

        /* LAYOUT */
        .map-layout { display: flex; flex: 1; overflow: hidden; }

        /* SIDEBAR */
        .sidebar {
            width: 320px; flex-shrink: 0;
            background: #131e2b;
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }
        .sidebar-header h2 { font-size: 13px; font-weight: 900; color: #fff; margin-bottom: 4px; }
        .sidebar-header .meta { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; }

        /* Stats row */
        .stats-row { display: flex; gap: 8px; padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }
        .stat-item { flex: 1; background: rgba(255,255,255,0.04); border-radius: 10px; padding: 8px; text-align: center; }
        .stat-num { font-size: 18px; font-weight: 900; }
        .stat-lbl { font-size: 9px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .stat-green { color: #22c55e; }
        .stat-yellow { color: #f59e0b; }
        .stat-red { color: #ef4444; }
        .stat-blue { color: #60a5fa; }

        /* Bus list */
        .bus-list { flex: 1; overflow-y: auto; padding: 8px; }
        .bus-list::-webkit-scrollbar { width: 4px; }
        .bus-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .bus-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; padding: 10px 12px; margin-bottom: 6px;
            cursor: pointer; transition: all 0.2s;
        }
        .bus-card:hover { background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.15); }
        .bus-card.active { background: rgba(30,58,95,0.5); border-color: #3b82f6; }

        .bus-card-header { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
        .bus-badge {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 900;
        }
        .bus-badge.jalan     { background: rgba(34,197,94,0.2); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
        .bus-badge.standby   { background: rgba(245,158,11,0.2); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
        .bus-badge.istirahat { background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

        .bus-name { font-size: 12px; font-weight: 700; color: #fff; }
        .bus-driver { font-size: 10px; color: rgba(255,255,255,0.4); }
        .bus-meta { display: flex; justify-content: space-between; align-items: center; }
        .bus-status-pill {
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            padding: 2px 7px; border-radius: 20px;
        }
        .pill-jalan     { background: rgba(34,197,94,0.15); color: #22c55e; }
        .pill-standby   { background: rgba(245,158,11,0.15); color: #f59e0b; }
        .pill-istirahat { background: rgba(239,68,68,0.15); color: #ef4444; }

        .bus-seats { font-size: 10px; color: rgba(255,255,255,0.5); display: flex; align-items: center; gap: 4px; }
        .bus-book-btn {
            font-size: 9px; font-weight: 700; padding: 3px 8px; border-radius: 6px;
            background: #1e3a5f; color: #93c5fd; border: none; cursor: pointer;
            text-decoration: none; display: inline-flex; align-items: center; gap: 3px;
            transition: all 0.2s;
        }
        .bus-book-btn:hover { background: #c41e3a; color: #fff; }
        .bus-book-btn.disabled { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.2); cursor: not-allowed; }

        /* MAP CONTAINER */
        #map-container { flex: 1; z-index: 1; }

        /* LIVE BADGE */
        .live-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(239,68,68,0.15); color: #ef4444;
            border: 1px solid rgba(239,68,68,0.3);
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
            padding: 3px 10px; border-radius: 20px;
        }
        .live-dot { width: 6px; height: 6px; background: #ef4444; border-radius: 50%; animation: livepulse 1s infinite; }
        @keyframes livepulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

        /* Legend */
        .legend { padding: 12px 16px; border-top: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }
        .legend h3 { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .legend-items { display: flex; gap: 12px; flex-wrap: wrap; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: 10px; color: rgba(255,255,255,0.6); }
        .legend-dot { width: 8px; height: 8px; border-radius: 50%; border: 2px solid #fff; }

        /* MOBILE TOGGLE */
        .mobile-toggle { display: none; }
        @media (max-width: 768px) {
            .sidebar { position: absolute; z-index: 500; left: 0; top: 57px; bottom: 0; transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .mobile-toggle { display: flex; }
        }

        /* Operational banner */
        .op-banner {
            padding: 6px 16px;
            font-size: 11px; font-weight: 600; text-align: center;
            flex-shrink: 0;
        }
        .op-operational { background: rgba(34,197,94,0.12); color: #86efac; border-bottom: 1px solid rgba(34,197,94,0.15); }
        .op-closed { background: rgba(239,68,68,0.12); color: #fca5a5; border-bottom: 1px solid rgba(239,68,68,0.15); }
    </style>
</head>
<body>
@php
    $isEmbed = request()->boolean('embed');
@endphp

@if(!$isEmbed)
{{-- TOPBAR --}}
<div class="topbar">
    <a href="{{ route('home') }}" class="topbar-brand">
        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="h-8 w-auto grayscale invert drop-shadow-[0_2px_4px_rgba(245,158,11,0.5)]" alt="Logo">
        <div>
            <div class="title">Bus Kampus Non-Merdeka</div>
            <div class="subtitle">Peta Armada Real-Time</div>
        </div>
    </a>
    <div class="topbar-actions">
        <div class="live-badge">
            <span class="live-dot"></span> LIVE
        </div>
        <button class="btn-sm mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">
            <i class="fas fa-list"></i> Bus
        </button>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn-sm"><i class="fas fa-cog"></i> Admin</a>
            @elseif(auth()->user()->role === 'sopir')
                <a href="{{ route('sopir.dashboard') }}" class="btn-sm"><i class="fas fa-steering-wheel"></i> Dashboard</a>
            @else
                <a href="{{ route('user.buses') }}" class="btn-sm active"><i class="fas fa-ticket-alt"></i> Pesan Tiket</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="{{ route('user.buses') }}" class="btn-sm active" onclick="window.location='{{ route('login') }}'"><i class="fas fa-ticket-alt"></i> Pesan</a>
        @endauth
        <a href="{{ route('home') }}" class="btn-sm"><i class="fas fa-home"></i></a>
    </div>
</div>
@endif

@if(!$isEmbed)
{{-- OPERATIONAL BANNER --}}
<div class="op-banner" id="op-banner">
    <i class="fas fa-clock mr-1"></i> Memeriksa status operasional...
</div>
@endif

<div class="map-layout">
    @if(!$isEmbed)
    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-satellite-dish" style="color:#3b82f6; margin-right:6px;"></i>Status Armada</h2>
            <div class="meta" id="last-update">Memuat data...</div>
        </div>

        {{-- STATS --}}
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num stat-blue" id="stat-total">—</div>
                <div class="stat-lbl">Total</div>
            </div>
            <div class="stat-item">
                <div class="stat-num stat-green" id="stat-jalan">—</div>
                <div class="stat-lbl">Jalan</div>
            </div>
            <div class="stat-item">
                <div class="stat-num stat-yellow" id="stat-standby">—</div>
                <div class="stat-lbl">Standby</div>
            </div>
            <div class="stat-item">
                <div class="stat-num stat-red" id="stat-istirahat">—</div>
                <div class="stat-lbl">Istirahat</div>
            </div>
        </div>

        {{-- BUS LIST --}}
        <div class="bus-list" id="bus-list">
            <div style="padding:20px; text-align:center; color:rgba(255,255,255,0.3); font-size:13px;">
                <i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Memuat data bus...
            </div>
        </div>

        {{-- LEGEND --}}
        <div class="legend">
            <h3>Keterangan</h3>
            <div class="legend-items">
                <div class="legend-item"><div class="legend-dot" style="background:#22c55e;"></div> Sedang Jalan</div>
                <div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div> Standby</div>
                <div class="legend-item"><div class="legend-dot" style="background:#ef4444;"></div> Istirahat</div>
                <div class="legend-item"><div class="legend-dot" style="background:#1e3a5f; border-color:#3b82f6;"></div> Terminal</div>
            </div>
        </div>
    </div>
    @endif

    {{-- MAP --}}
    <div id="map-container"></div>
</div>

{{-- Scripts --}}
<script src="{{ asset('vendor/js/leaflet.js') }}"></script>
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script src="{{ asset('js/realtime-map.js') }}?v={{ filemtime(public_path('js/realtime-map.js')) }}"></script>
<script>
(async () => {
    // 1. Init peta
    RealtimeMap.initMap('map-container');

    // 2. Gambar KEDUA rute dari BusSimulation (go=biru, return=oranye)
    const routes = BusSimulation.getRoutes();
    RealtimeMap.drawRoutes(routes);
    RealtimeMap.drawTerminals(BusSimulation.getTerminals());

    // 3. Load data bus dari DB (nama, sopir, kursi) + periodic user booking refresh
    let dbBuses = [];
    async function refreshFromAPI() {
        try {
            const res  = await fetch('/api/simulation/buses');
            const data = await res.json();
            return data.buses;
        } catch(e) {
            console.error('Gagal memuat data bus:', e);
            return null;
        }
    }

    const initialBuses = await refreshFromAPI();
    if (initialBuses) {
        dbBuses = initialBuses;
        BusSimulation.init(dbBuses);
    }

    // Setiap 15 detik: refresh user_has_booking & user_booking_notes dari DB
    // Ini mencegah icon 'ANDA' tersisa jika user reload setelah bus sudah tiba
    setInterval(async () => {
        const freshBuses = await refreshFromAPI();
        if (freshBuses) {
            BusSimulation.refreshUserBookingState(freshBuses);
        }
    }, 15000);

    let lastBusStates  = {};
    let firstTickDone   = false; // flag: tick pertama hanya untuk merekam state awal
    const autoFinishedBuses = new Set(); // persists antara tick — hindari double-call

    // 4. Update loop setiap 1.5 detik (halus krn simulation speed 20x)
    function tick() {
        @if(!$isEmbed)
        const banner = document.getElementById('op-banner');
        if (banner) {
            banner.className = 'op-banner op-operational';
            banner.innerHTML = '<i class="fas fa-circle" style="color:#22c55e;font-size:8px;margin-right:6px;"></i>'
                             + '<b>Simulasi Aktif 24 Jam</b> &bull; 🔵 Jalur Berangkat &nbsp;|&nbsp; 🟠 Jalur Pulang &bull; Maks 2 Bus Bergerak Sekaligus';
        }
        @endif
        
        const positions = BusSimulation.getAllPositions();

        // ===== DETEKSI TIBA DI TUJUAN & AUTO-FINISH =====
        positions.forEach(b => {
            // Evaluasi finish semua bus agar tiket guest & user lain tetap auto-complete oleh central map
            if (autoFinishedBuses.has(b.id)) return;

            const prevDir = lastBusStates[b.id]; // undefined pada tick pertama
            const curDir  = b.direction;

            // Pada tick pertama, hanya rekam state awal — JANGAN trigger autoFinish.
            // Ini mencegah tiket baru langsung selesai hanya karena bus sedang standby di terminal.
            if (!firstTickDone) {
                lastBusStates[b.id] = curDir;
                return;
            }

            // Deteksi edge (transisi nyata dari satu arah ke arah lain)
            const arrivedGowa  = prevDir === 'go'     && curDir === 'rest_gowa';
            const arrivedTamal = prevDir === 'return' && curDir === 'rest_tamal';

            // DIHAPUS: alreadyAtTerminal — ini penyebab utama bug tiket langsung selesai.
            // Auto-finish HANYA dipicu oleh transisi nyata, bukan kondisi saat halaman dibuka.

            if (arrivedGowa || arrivedTamal) {
                autoFinishedBuses.add(b.id); // tandai: bus ini sudah selesai pengecekan sesi ini

                const notes = (b.user_booking_notes || '').toLowerCase();
                const isReturnTrip = notes.includes('gowa ->') || notes.includes('gowa->');

                let shouldClearLocally = false;
                if (arrivedGowa && !isReturnTrip) {
                    // Tiba di Gowa, selesaikan trip dari Perintis
                    shouldClearLocally = true;
                } else if (arrivedTamal && isReturnTrip) {
                    // Tiba di Tamalanrea, selesaikan trip dari Gowa
                    shouldClearLocally = true;
                }

                if (shouldClearLocally) {
                    // Hapus icon ANDA seketika (lokal, tanpa tunggu server)
                    BusSimulation.clearUserBooking(b.id);
                    b.user_has_booking = false;
                }

                // Update DB agar is_completed = true sesuai arah
                fetch(`/api/simulation/bus/${b.id}/auto-finish?dir=${curDir}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(res => res.json())
                  .then(data => {
                    if (data.bookings_updated > 0) {
                        console.log(`[AutoFinish] Bus ${b.id}: ${data.bookings_updated} booking diselesaikan`);
                        if (shouldClearLocally) {
                            window.parent.postMessage({ type: 'TRIP_COMPLETED', busId: b.id }, '*');
                        }
                    }
                  })
                  .catch(err => console.error('[AutoFinish] Error:', err));
            }

            lastBusStates[b.id] = curDir;
        });

        // Setelah iterasi pertama selesai, tandai bahwa state awal sudah terekam
        if (!firstTickDone) firstTickDone = true;


        @if(!$isEmbed)
        // Hitung statistik (rest_gowa dihitung sebagai standby, bukan istirahat)
        const jalan     = positions.filter(b => b.trip_status === 'jalan').length;
        const standby   = positions.filter(b => b.trip_status === 'standby' || b.direction === 'rest_gowa').length;
        const istirahat = positions.filter(b => b.trip_status === 'istirahat' && b.direction !== 'rest_gowa').length;

        const statTotal = document.getElementById('stat-total');
        if (statTotal) {
            statTotal.textContent = positions.length;
            document.getElementById('stat-jalan').textContent     = jalan;
            document.getElementById('stat-standby').textContent   = standby;
            document.getElementById('stat-istirahat').textContent = istirahat;
            document.getElementById('last-update').textContent    = 'Update: ' + new Date().toLocaleTimeString('id-ID');
        }
        @endif

        // Update marker bus di peta
        RealtimeMap.updateBusMarkers(positions.map(b => ({
            id:           b.id,
            bus_number:   b.bus_number,
            bus_code:     b.bus_code,
            name:         b.name,
            plate:        b.plate,
            trip_status:  b.trip_status,
            direction:    b.direction,
            lat:          b.lat,
            lng:          b.lng,
            current_terminal: b.current_terminal,
            from_terminal:    b.from_terminal,
            next_terminal:    b.next_terminal,
            eta_minutes:  b.eta_minutes,
            db_available: b.db_available,
            booked_passengers: b.booked_passengers,
            is_bookable:  b.is_bookable,
            user_has_booking:       b.user_has_booking,
            driver_name:            b.driver_name,
            driver_id:              b.driver_id,
            driver_on_board:        b.driver_on_board,
            current_user_is_driver: b.current_user_is_driver,
        })));

        // Emit ke parent window (jika embedded dlm iframe)
        window.parent.postMessage({ type: 'BUS_UPDATE', buses: positions }, '*');

        @if(!$isEmbed)
        // Update sidebar bus list
        const bookingBase = @json(auth()->check() ? (auth()->user()->isSopir() ? '' : route('user.buses')) : route('login'));
        const listEl = document.getElementById('bus-list');
        if (listEl) {
            listEl.innerHTML = positions
                .sort((a, b) => a.bus_number - b.bus_number)
                .map(b => {
                    const isGo  = b.direction === 'go';
                    const isRet = b.direction === 'return';
                    const bodyColor = b.trip_status === 'jalan'
                        ? (isGo ? '#3b82f6' : '#f97316')
                        : (b.trip_status === 'standby' || b.direction === 'rest_gowa') ? '#f59e0b' : '#ef4444';
                    const dirLabel = isGo ? '↓ Ke Gowa' : isRet ? '↑ Ke Tamalanrea' : '';
                    return `
                    <div class="bus-card" onclick="focusBus(${b.id}, ${b.lat}, ${b.lng})">
                        <div class="bus-card-header">
                            <div class="bus-badge ${b.trip_status}" style="background:${bodyColor}22;color:${bodyColor};border-color:${bodyColor}55;">
                                ${String(b.bus_number).padStart(2,'0')}
                            </div>
                            <div>
                                <div class="bus-name">${b.name}
                                    ${(b.driver_on_board && b.trip_status !== 'standby') ? `<span style="background:#f59e0b;color:white;font-size:8px;padding:2px 4px;border-radius:4px;margin-left:4px;"><i class="fas fa-steering-wheel"></i> ${b.current_user_is_driver ? 'ANDA (SOPIR)' : 'SOPIR'}</span>` : ''}
                                    ${b.user_has_booking ? `<span style="background:#ef4444;color:white;font-size:8px;padding:2px 4px;border-radius:4px;margin-left:4px;"><i class="fas fa-user"></i> ANDA</span>` : ''}
                                </div>
                                <div class="bus-driver"><i class="fas fa-steering-wheel" style="opacity:.5;margin-right:3px;"></i>${b.driver_name}</div>
                            </div>
                        </div>
                        <div class="bus-meta">
                            <span class="bus-status-pill" style="background:${bodyColor}22;color:${bodyColor};">
                                ${b.trip_status === 'jalan'     ? `🟢 Jalan ${dirLabel}`
                                : b.trip_status === 'standby'   ? (b.direction === 'queue' ? '🕐 Antri Tamal' : '🟡 Standby Tamal')
                                : b.direction === 'rest_gowa'   ? `🟡 Standby Gowa`
                                : '🔴 Istirahat'}
                            </span>
                            <span class="bus-seats"><i class="fas fa-chair" style="margin-right:2px;"></i>${b.db_available ?? '?'} kursi${b.trip_status === 'jalan' && b.booked_passengers ? ` · <i class="fas fa-users" style="margin-right:2px;color:#3b82f6;"></i>${b.booked_passengers} org` : ''}</span>
                            ${b.is_bookable
                                ? `<a href="${bookingBase}/${b.id}${b.direction === 'rest_gowa' ? '?from=gowa' : ''}" class="bus-book-btn"><i class="fas fa-ticket-alt"></i> Pesan</a>`
                                : `<span class="bus-book-btn disabled"><i class="fas fa-ban"></i> Tidak bisa</span>`
                            }
                        </div>
                        ${b.trip_status === 'jalan' && b.from_terminal
                            ? `<div style="margin-top:5px;font-size:9px;color:rgba(255,255,255,0.35);">📍 ${b.from_terminal} → ${b.next_terminal} (${b.eta_minutes ?? '?'} mnt)</div>`
                            : b.current_terminal
                              ? `<div style="margin-top:5px;font-size:9px;color:rgba(255,255,255,0.35);">📍 ${b.current_terminal} · ${b.eta_minutes != null ? b.direction === 'queue' ? 'giliran ~'+b.eta_minutes+' mnt' : 'berangkat ~'+b.eta_minutes+' mnt' : ''}</div>`
                              : ''
                        }
                    </div>`;
                }).join('');
        }
        @endif
    }

    function focusBus(busId, lat, lng) {
        if (!lat || !lng) return;
        RealtimeMap.getMap().setView([lat, lng], 14, { animate: true });
    }

    tick();
    setInterval(tick, 1500); // update tiap 1.5 detik untuk smooth 20x speed
})();
</script>
</body>
</html>
