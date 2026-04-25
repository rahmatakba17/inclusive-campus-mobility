/**
 * bus-simulation.js — v3.0
 * Sistem antrian bus 2-aktif: Tamalanrea <-> Gowa
 * - Maksimal 2 bus bergerak bersamaan (1 berangkat, 1 pulang)
 * - Jalur berangkat (biru) berbeda dengan jalur pulang (oranye)
 * - Bus antri di Tamalanrea, bergiliran saat bus sebelumnya tiba
 */

const BusSimulation = (() => {

    // ====================================================
    //  RUTE A: Berangkat (Tamalanrea → Gowa) — Jalur Timur
    // ====================================================
    const ROUTE_GO = [
        { code: 'TAMAL',     name: 'Terminal UNHAS Tamalanrea', lat: -5.1326, lng: 119.4880 },
        { code: 'ANTANG',    name: 'Halte BTP / Antang',        lat: -5.1612, lng: 119.4770 },
        { code: 'PALLANGGA', name: 'Halte Pallangga',           lat: -5.1980, lng: 119.4590 },
        { code: 'GOWA',      name: 'UNHAS Kampus Gowa',         lat: -5.2303, lng: 119.4520 },
    ];

    // ====================================================
    //  RUTE B: Pulang (Gowa → Tamalanrea) — Jalur Barat
    // ====================================================
    const ROUTE_RETURN = [
        { code: 'GOWA',    name: 'UNHAS Kampus Gowa',           lat: -5.2303, lng: 119.4520 },
        { code: 'SAMATA',  name: 'Halte Samata / Hertasning',   lat: -5.1950, lng: 119.4760 },
        { code: 'PETTARA', name: 'Halte Jl. AP. Pettarani',    lat: -5.1600, lng: 119.4930 },
        { code: 'TAMAL',   name: 'Terminal UNHAS Tamalanrea',   lat: -5.1326, lng: 119.4880 },
    ];

    // Semua terminal unik (untuk marker peta)
    const ALL_TERMINALS = [
        { code: 'TAMAL',     name: 'Terminal UNHAS Tamalanrea', lat: -5.1326, lng: 119.4880, type: 'origin',      description: 'Terminal utama keberangkatan' },
        { code: 'ANTANG',    name: 'Halte BTP / Antang',        lat: -5.1612, lng: 119.4770, type: 'stop',        description: 'Jalur berangkat (biru)' },
        { code: 'PALLANGGA', name: 'Halte Pallangga',           lat: -5.1980, lng: 119.4590, type: 'stop',        description: 'Jalur berangkat (biru)' },
        { code: 'GOWA',      name: 'UNHAS Kampus Gowa',         lat: -5.2303, lng: 119.4520, type: 'destination', description: 'Terminal akhir / istirahat sopir' },
        { code: 'SAMATA',    name: 'Halte Samata / Hertasning', lat: -5.1950, lng: 119.4760, type: 'stop',        description: 'Jalur pulang (oranye)' },
        { code: 'PETTARA',   name: 'Halte Jl. AP. Pettarani',  lat: -5.1600, lng: 119.4930, type: 'stop',        description: 'Jalur pulang (oranye)' },
    ];

    // ====================================================
    //  TIMING SIMULASI (dalam menit simulasi)
    //  Speed 20x: 1 menit simul = 3 detik real
    // ====================================================
    const SPEED        = 20;    // 20x real time
    const TIME_GO      = 25;    // menit simul Tamalanrea → Gowa (~75 detik real)
    const TIME_REST_GOWA  = 20; // Ditingkatkan secukupnya agar ada waktu pesan tiket (~1 menit real)
    const TIME_RETURN  = 25;    // menit simul Gowa → Tamalanrea (~75 detik real)
    const TIME_REST_TAMAL = 5;  // short rest di Tamalanrea (~15 detik real)
    const CYCLE_ACTIVE = TIME_GO + TIME_REST_GOWA + TIME_RETURN + TIME_REST_TAMAL; // 75 menit simul

    // Untuk maks 2 bus aktif bersamaan:
    // DISPATCH_INTERVAL ≥ TIME_GO / 2 → pakai 13 menit simul
    const DISPATCH_INTERVAL      = 13;                  // selang antar keberangkatan bus
    const BUS_COUNT              = 13;
    const FULL_SCHEDULE_CYCLE    = BUS_COUNT * DISPATCH_INTERVAL; // 169 menit simul

    let busStates = {};

    // ====================================================
    //  HELPERS
    // ====================================================

    /** Waktu simulasi dalam menit (WITA × SPEED) */
    function getSimMinutes() {
        const now  = new Date();
        // Fallback aman untuk semua browser (terutama Safari/iOS)
        // Makassar selalu UTC+8 (tanpa DST)
        const utcMs = now.getTime() + (now.getTimezoneOffset() * 60000);
        const wita  = new Date(utcMs + (3600000 * 8));

        const realSec = wita.getHours() * 3600 + wita.getMinutes() * 60 + wita.getSeconds()
                      + wita.getMilliseconds() / 1000;
        return (realSec / 60) * SPEED;
    }

    /** Interpolasi posisi lat/lng di sepanjang rute pada progress [0,1] */
    function interpolateRoute(route, progress) {
        const p      = Math.max(0, Math.min(1, progress));
        const segs   = route.length - 1;
        const segPos = p * segs;
        const si     = Math.min(Math.floor(segPos), segs - 1);
        const t      = segPos - si;
        const from   = route[si];
        const to     = route[si + 1];
        return {
            lat: from.lat + (to.lat - from.lat) * t,
            lng: from.lng + (to.lng - from.lng) * t,
        };
    }

    /** Nama halte terdekat di rute berdasarkan progress */
    function routeLabel(route, progress) {
        const segs = route.length - 1;
        const si   = Math.min(Math.floor(progress * segs), segs - 1);
        return { from: route[si].name, next: route[Math.min(si + 1, segs)].name };
    }

    // ====================================================
    //  INIT
    // ====================================================
    function init(busesFromDB) {
        busesFromDB.forEach((bus, index) => {
            busStates[bus.id] = {
                ...bus,
                plate:          bus.plate_number,
                db_available:   bus.available_seats,
                // Offset agar bus berangkat bergiliran
                departureOffset: index * DISPATCH_INTERVAL,
            };
        });
    }

    // ====================================================
    //  KALKULASI POSISI PER BUS
    // ====================================================
    function calculatePosition(busState) {
        const simNow   = getSimMinutes();
        const schedPos = ((simNow - busState.departureOffset) % FULL_SCHEDULE_CYCLE + FULL_SCHEDULE_CYCLE) % FULL_SCHEDULE_CYCLE;

        // ---- Sedang dalam siklus aktif (jalan + istirahat) ----
        if (schedPos < CYCLE_ACTIVE) {
            const t = schedPos;

            if (t < TIME_GO) {
                // FASE 1: BERANGKAT Tamalanrea → Gowa
                const progress = t / TIME_GO;
                const pos      = interpolateRoute(ROUTE_GO, progress);
                const lbl      = routeLabel(ROUTE_GO, progress);
                return {
                    ...pos,
                    trip_status:      'jalan',
                    direction:        'go',
                    current_terminal: null,
                    from_terminal:    lbl.from,
                    next_terminal:    lbl.next,
                    eta_minutes:      Math.round(TIME_GO - t),
                };

            } else if (t < TIME_GO + TIME_REST_GOWA) {
                // FASE 2: ISTIRAHAT di Gowa
                const gowa = ROUTE_GO[ROUTE_GO.length - 1];
                const waitGowa = TIME_GO + TIME_REST_GOWA - t;
                const qIdx = waitGowa / DISPATCH_INTERVAL;
                return {
                    lat: gowa.lat - (qIdx * 0.002), // offset ke selatan
                    lng: gowa.lng + (qIdx * 0.001), 
                    trip_status:      'standby',
                    direction:        'rest_gowa',
                    current_terminal: gowa.name,
                    from_terminal:    null,
                    next_terminal:    ROUTE_RETURN[1].name,
                    eta_minutes:      Math.round(waitGowa),
                };

            } else if (t < TIME_GO + TIME_REST_GOWA + TIME_RETURN) {
                // FASE 3: PULANG Gowa → Tamalanrea (jalur berbeda)
                const rp       = (t - TIME_GO - TIME_REST_GOWA) / TIME_RETURN;
                const pos      = interpolateRoute(ROUTE_RETURN, rp);
                const lbl      = routeLabel(ROUTE_RETURN, rp);
                return {
                    ...pos,
                    trip_status:      'jalan',
                    direction:        'return',
                    current_terminal: null,
                    from_terminal:    lbl.from,
                    next_terminal:    lbl.next,
                    eta_minutes:      Math.round(TIME_RETURN - (t - TIME_GO - TIME_REST_GOWA)),
                };

            } else {
                // FASE 4: ISTIRAHAT singkat di Tamalanrea sebelum masuk antrian
                const waitRest = CYCLE_ACTIVE - t;
                const tamal = ROUTE_GO[0];
                const waitTotal = (FULL_SCHEDULE_CYCLE - CYCLE_ACTIVE) + waitRest; 
                const qIdx = waitTotal / DISPATCH_INTERVAL;
                return {
                    lat:              tamal.lat + (qIdx * 0.0015),
                    lng:              tamal.lng - (qIdx * 0.0005),
                    trip_status:      'standby',
                    direction:        'rest_tamal',
                    current_terminal: tamal.name,
                    from_terminal:    null,
                    next_terminal:    ROUTE_GO[1].name,
                    eta_minutes:      Math.round(waitTotal),
                };
            }

        } else {
            // ---- ANTRIAN di Tamalanrea (menunggu giliran) ----
            const waitLeft = FULL_SCHEDULE_CYCLE - schedPos;
            const qIdx = waitLeft / DISPATCH_INTERVAL;
            const tamal = ROUTE_GO[0];
            return {
                lat:              tamal.lat + (qIdx * 0.0015),
                lng:              tamal.lng - (qIdx * 0.0005),
                trip_status:      'standby',
                direction:        'queue',
                current_terminal: tamal.name,
                from_terminal:    null,
                next_terminal:    ROUTE_GO[1].name,
                eta_minutes:      Math.round(waitLeft),
            };
        }
    }

    // ====================================================
    //  PUBLIC API
    // ====================================================
    function getAllPositions() {
        return Object.values(busStates).map(bs => {
            const pos = calculatePosition(bs);
            return {
                id:           bs.id,
                bus_number:   bs.bus_number,
                bus_code:     bs.bus_code,
                name:         bs.name,
                plate:        bs.plate ?? bs.plate_number,
                driver_name:  bs.driver_name,
                driver_id:    bs.driver_id,
                driver_on_board:         bs.driver_on_board     || false,
                current_user_is_driver:  bs.current_user_is_driver || false,
                db_available: bs.db_available ?? bs.available_seats,
                user_has_booking: bs.user_has_booking || false,
                user_booking_notes: bs.user_booking_notes || null,
                ...pos,
                // Jangan bisa dipesan jika DB driver men-set istirahat manual
                is_bookable:  (bs.trip_status !== 'istirahat') && (pos.trip_status === 'standby' || pos.direction === 'rest_gowa'),
            };
        });
    }

    function getRoutes() {
        return { go: ROUTE_GO, return: ROUTE_RETURN };
    }

    function getTerminals() {
        return ALL_TERMINALS;
    }

    function isOperational() { return true; } // Demo mode: 24 jam

    function clearUserBooking(busId) {
        if (busStates[busId]) {
            busStates[busId].user_has_booking    = false;
            busStates[busId].user_booking_notes  = null;
            busStates[busId]._locally_cleared    = true; // flag: jangan di-override oleh refresh DB
        }
    }

    /**
     * Perbarui user-specific fields dari fresh API data.
     * Jika sudah di-clearUserBooking (locally cleared), jangan kembalikan user_has_booking = true.
     * Ini mencegah race condition di mana DB belum sempat update sebelum refresh 15s.
     */
    function refreshUserBookingState(busesFromDB) {
        busesFromDB.forEach(bus => {
            if (busStates[bus.id]) {
                // Jika booking sudah di-clear secara lokal, HANYA update jika DB juga confirms selesai
                if (busStates[bus.id]._locally_cleared) {
                    // Accept DB confirmation jika false (selesai), tapi jangan restore ke true
                    if (bus.user_has_booking === false) {
                        busStates[bus.id].user_has_booking   = false;
                        busStates[bus.id].user_booking_notes = null;
                    }
                    // DB masih bilang true → DB belum update, tetap pertahankan cleared state
                } else {
                    // Normal update dari DB
                    busStates[bus.id].user_has_booking   = bus.user_has_booking  || false;
                    busStates[bus.id].user_booking_notes = bus.user_booking_notes || null;
                }
                // Selalu update data umum dan status terbaru
                busStates[bus.id].db_available           = bus.available_seats;
                busStates[bus.id].booked_passengers      = bus.booked_passengers;
                busStates[bus.id].trip_status            = bus.trip_status;
                busStates[bus.id].driver_name            = bus.driver_name;
                busStates[bus.id].driver_id              = bus.driver_id;
                busStates[bus.id].driver_on_board        = bus.driver_on_board;
                busStates[bus.id].current_user_is_driver = bus.current_user_is_driver;
            }
        });
    }

    return { init, getAllPositions, getRoutes, getTerminals, isOperational, clearUserBooking, refreshUserBookingState };
})();

window.BusSimulation = BusSimulation;
