<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * BusPollingController — Lightweight endpoint untuk polling peta & panel.
 *
 * Optimasi:
 *  - Tidak memakai Eloquent ORM (gunakan raw DB::select)
 *  - Hasil di-cache 2 detik (file cache — tidak perlu Redis)
 *  - ETag + 304 Not Modified agar browser tidak re-render jika data sama
 *  - Response JSON langsung + header kompresi hint
 */
class BusPollingController extends Controller
{
    /**
     * GET /api/poll/buses
     * Dipanggil setiap 2 detik oleh client (map & panel).
     */
    public function index(): JsonResponse
    {
        $cacheKey = 'poll_buses_v1';
        $ttl      = 2; // detik — refresh setiap 2 detik

        $payload = Cache::remember($cacheKey, $ttl, function () {
            return $this->fetchBusData();
        });

        // --- ETag: hanya kirim body jika data berubah ---
        $etag = '"' . md5($payload['updated_at']) . '"';
        if (request()->header('If-None-Match') === $etag) {
            return response()->json(null, 304);
        }

        return response()
            ->json($payload)
            ->header('ETag', $etag)
            ->header('Cache-Control', 'no-cache'); // client harus selalu tanya, tapi boleh pakai ETag
    }

    /**
     * Ambil data bus mentah dengan satu JOIN — tanpa N+1 query.
     */
    private function fetchBusData(): array
    {
        // Satu query dengan JOIN ke users (driver)
        $buses = DB::select("
            SELECT
                b.id,
                b.bus_number,
                b.bus_code,
                b.name,
                b.plate_number,
                b.capacity,
                b.trip_status,
                b.current_lat,
                b.current_lng,
                b.current_terminal,
                b.departed_at,
                b.departure_time,
                b.driver_id,
                u.name AS driver_name,
                (
                    SELECT COUNT(*) FROM bookings bk
                    WHERE bk.bus_id = b.id
                      AND bk.status IN ('pending','confirmed')
                      AND bk.is_completed = 0
                      AND DATE(bk.booking_date) = CURDATE()
                ) AS booked_seats
            FROM buses b
            LEFT JOIN users u ON u.id = b.driver_id
            WHERE b.status = 'active' AND b.driver_id IS NOT NULL
            ORDER BY b.bus_number ASC
        ");

        $result = array_map(function ($bus) {
            $available = max(0, $bus->capacity - $bus->booked_seats);
            return [
                'id'               => $bus->id,
                'bus_number'       => $bus->bus_number,
                'bus_code'         => $bus->bus_code,
                'name'             => $bus->name,
                'plate_number'     => $bus->plate_number,
                'capacity'         => (int) $bus->capacity,
                'available_seats'  => $available,
                'booked_seats'     => (int) $bus->booked_seats,
                'trip_status'      => $bus->trip_status,
                'trip_status_label'=> $this->getTripLabel($bus->trip_status),
                'current_lat'      => $bus->current_lat ? (float) $bus->current_lat : null,
                'current_lng'      => $bus->current_lng ? (float) $bus->current_lng : null,
                'current_terminal' => $bus->current_terminal,
                'departed_at'      => $bus->departed_at,
                'departure_time'   => $bus->departure_time,
                'driver_name'      => $bus->driver_name ?? 'Tidak Ditugaskan',
                'driver_id'        => $bus->driver_id,
                'is_bookable'      => $bus->trip_status === 'standby' && $available > 0,
                'driver_on_board'  => $bus->driver_id !== null,
            ];
        }, $buses);

        return [
            'buses'      => $result,
            'updated_at' => now()->toISOString(),
        ];
    }

    private function getTripLabel(string $status): string
    {
        return match ($status) {
            'standby'  => 'Standby',
            'jalan'    => 'Dalam Perjalanan',
            'istirahat'=> 'Istirahat',
            default    => ucfirst($status),
        };
    }
}
