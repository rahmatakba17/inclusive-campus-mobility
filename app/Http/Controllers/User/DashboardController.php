<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // [SIMULATION FIX] Bersihkan ghost tickets dari siklus simulasi sebelumnya
        // (Siklus penuh simulasi = 3 menit real-time, jadi toleransi 30 menit)
        Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->where('created_at', '<', now()->subMinutes(30))
            ->update(['is_completed' => true]);

        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed'  => $user->bookings()->where('status', 'confirmed')->where('is_completed', false)->count(),
            'pending'    => $user->bookings()->where('status', 'pending')->where('is_completed', false)->count(),
            'cancelled'  => $user->bookings()->where('status', 'cancelled')->count(),
            'completed'  => $user->bookings()->where('is_completed', true)->count(),
        ];

        $all_recent = $user->bookings()->with('bus')->latest()->take(20)->get();

        // Pisahkan berdasarkan arah rute yang tersimpan di notes
        $recent_perintis = $all_recent->filter(function($b) {
            $notes = strtolower($b->notes ?? '');
            // Default (tidak ada info Gowa) = dari Perintis
            return !str_contains($notes, 'gowa -> kampus perintis')
                && !str_contains($notes, 'gowa->kampus perintis')
                && !str_contains($notes, 'gowa -> perintis');
        })->take(5)->values();

        $recent_gowa = $all_recent->filter(function($b) {
            $notes = strtolower($b->notes ?? '');
            return str_contains($notes, 'gowa -> kampus perintis')
                || str_contains($notes, 'gowa->kampus perintis')
                || str_contains($notes, 'gowa -> perintis');
        })->take(5)->values();

        // Untuk backward-compat tetap kirim recent_bookings (semua, termasuk selesai)
        $recent_bookings = $all_recent->take(5);

        $available_buses = Bus::where('status', 'active')->whereNotNull('driver_id')
            ->orderBy('bus_number')
            ->get()
            ->sortBy(function($bus) {
                $order = ['standby' => 1, 'jalan' => 2, 'istirahat' => 3];
                return $order[$bus->trip_status] ?? 4;
            })->values();

        return view('user.dashboard', compact(
            'stats', 'recent_bookings', 'recent_perintis', 'recent_gowa', 'available_buses'
        ));
    }
}
