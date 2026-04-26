<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Terminal;
use App\Models\Tip;
use Illuminate\Http\JsonResponse;

class SimulationController extends Controller
{
    /**
     * GET /api/simulation/buses
     * Return status & posisi semua bus (untuk polling peta & panel)
     */
    public function buses(): JsonResponse
    {
        \App\Models\Booking::cleanupExpiredUnconfirmed();
        
        $userActiveBookings = [];
        $currentUserRole  = null;
        $currentUserId    = null;

        if (auth()->check()) {
            $currentUserId   = auth()->id();
            $currentUserRole = auth()->user()->role;

            // Auto-selesaikan booking lama yang menggantung
            Booking::where('user_id', $currentUserId)
                ->where('is_completed', false)
                ->whereDate('booking_date', '<', today()->subDays(1))
                ->update(['is_completed' => true]);

            $userActiveBookings = Booking::where('user_id', $currentUserId)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('is_completed', false)
                ->get()
                ->groupBy('bus_id');
        }

        $buses = Bus::with('driver:id,name')
            ->where('status', 'active')->whereNotNull('driver_id')
            ->orderBy('bus_number')
            ->get()
            ->map(function (Bus $bus) use ($userActiveBookings, $currentUserId, $currentUserRole) {
                $bookedToday = $bus->bookings()
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where('is_completed', false)
                    ->count();

                return [
                    'id'               => $bus->id,
                    'bus_number'       => $bus->bus_number,
                    'bus_code'         => $bus->bus_code,
                    'name'             => $bus->name,
                    'plate_number'     => $bus->plate_number,
                    'capacity'         => $bus->capacity,
                    'available_seats'  => max(0, $bus->capacity - $bookedToday),
                    'booked_seats'     => $bookedToday,
                    'booked_passengers'=> $bookedToday,
                    'trip_status'      => $bus->trip_status,
                    'trip_status_label'=> $bus->trip_status_label,
                    'current_lat'      => $bus->current_lat,
                    'current_lng'      => $bus->current_lng,
                    'current_terminal' => $bus->current_terminal,
                    'departed_at'      => $bus->departed_at?->toISOString(),
                    'departure_time'   => $bus->departure_time,
                    'is_bookable'         => $bus->isBookable(),
                    'user_has_booking'     => isset($userActiveBookings[$bus->id]),
                    'user_booking_notes'   => isset($userActiveBookings[$bus->id]) ? $userActiveBookings[$bus->id]->first()->notes : null,
                    'driver_name'         => $bus->driver?->name ?? 'Tidak Ditugaskan',
                    'driver_id'           => $bus->driver_id,
                    // Sopir yang login sedang mengemudi bus ini
                    'current_user_is_driver' => $currentUserRole === 'sopir' && $currentUserId === $bus->driver_id,
                    // Apakah sopir assigned aktif (selalu true jika driver_id ada)
                    'driver_on_board'     => $bus->driver_id !== null,
                ];
            });

        return response()->json([
            'buses'      => $buses,
            'updated_at' => now()->toISOString(),
        ]);
    }

    /**
     * GET /api/simulation/bus/{id}/seats
     * Return data kursi real-time bus tertentu
     */
    public function seats(Bus $bus): JsonResponse
    {
        \App\Models\Booking::cleanupExpiredUnconfirmed();
        
        $bookedSeats = Booking::where('bus_id', $bus->id)
            ->whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->pluck('seat_number')
            ->toArray();

        return response()->json([
            'bus_id'       => $bus->id,
            'capacity'     => $bus->capacity,
            'booked_seats' => $bookedSeats,
            'available'    => $bus->capacity - count($bookedSeats),
            'is_bookable'  => $bus->isBookable(),
            'trip_status'  => $bus->trip_status,
            'updated_at'   => now()->toISOString(),
        ]);
    }

    /**
     * GET /api/simulation/terminals
     * Return semua data terminal
     */
    public function terminals(): JsonResponse
    {
        $terminals = Terminal::orderBy('order')->get();
        return response()->json(['terminals' => $terminals]);
    }

    /**
     * GET /api/simulation/status
     * Summary operasional (dipakai di welcome & map)
     */
    public function status(): JsonResponse
    {
        $buses = Bus::where('status', 'active')->whereNotNull('driver_id')->get();
        return response()->json([
            'total'     => $buses->count(),
            'jalan'     => $buses->where('trip_status', 'jalan')->count(),
            'standby'   => $buses->where('trip_status', 'standby')->count(),
            'istirahat' => $buses->where('trip_status', 'istirahat')->count(),
            'updated_at'=> now()->toISOString(),
        ]);
    }

    /**
     * GET /api/admin/tips
     * Return 10 tip terbaru 24 jam terakhir dengan nama pengirim disamarkan
     */
    public function adminTips(): JsonResponse
    {
        $tips = Tip::with('bus:id,name,bus_number')
            ->where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->take(10)
            ->get()
            ->map(function (Tip $tip) {
                return [
                    'id'        => $tip->id,
                    'bus_name'  => 'Seorang Sopir',
                    'bus_number'=> '??',
                    'amount'    => $tip->amount,
                    'time'      => $tip->created_at->diffForHumans(),
                    'time_full' => $tip->created_at->format('H:i'),
                ];
            });

        $totalToday = Tip::whereDate('created_at', today())->sum('amount');
        $countToday = Tip::whereDate('created_at', today())->count();

        return response()->json([
            'tips'         => $tips,
            'total_today'  => $totalToday,
            'count_today'  => $countToday,
            'updated_at'   => now()->toISOString(),
        ]);
    }

    /**
     * GET /api/simulation/stats
     * Stats ringkasan untuk dashboard (booking + tip hari ini)
     */
    public function liveStats(): JsonResponse
    {
        \App\Models\Booking::cleanupExpiredUnconfirmed();

        $buses = Bus::where('status', 'active')->whereNotNull('driver_id')->get();

        $bookingsToday = Booking::whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $tipToday = Tip::whereDate('created_at', today())->sum('amount');
        $tipCount = Tip::whereDate('created_at', today())->count();

        return response()->json([
            'fleet' => [
                'total'     => $buses->count(),
                'jalan'     => $buses->where('trip_status', 'jalan')->count(),
                'standby'   => $buses->where('trip_status', 'standby')->count(),
                'istirahat' => $buses->where('trip_status', 'istirahat')->count(),
            ],
            'bookings_today' => $bookingsToday,
            'tip_today_amount' => $tipToday,
            'tip_today_count'  => $tipCount,
            'updated_at' => now()->toISOString(),
        ]);
    }

    /**
     * POST /api/simulation/bus/{bus}/auto-finish
     * Dipanggil otomatis oleh frontend (JS) ketika bus mencapai tujuan (eta=0 atau status rest)
     * Mengubah state bus ke standby dan menandai semua manifest belum selesai menjadi selesai.
     */
    public function autoFinish(\Illuminate\Http\Request $request, Bus $bus): JsonResponse
    {
        $dir = $request->query('dir');
        // [PERBAIKAN BUG] Selesaikan seluruh data (termasuk auto-cancel)
        $updatedRows = \App\Models\Booking::completeAllForBus($bus->id);

        // [SIMULATION FIX] Kembalikan state bus ke standby di database sesuai instruksi docblock
        $bus->update(['trip_status' => 'standby']);

        return response()->json([
            'success'          => true,
            'message'          => 'Trip otomatis diselesaikan.',
            'bookings_updated' => $updatedRows,
        ]);
    }
}
