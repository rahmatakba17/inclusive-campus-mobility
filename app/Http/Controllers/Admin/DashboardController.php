<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buses' => Bus::count(),
            'active_buses' => Bus::where('status', 'active')->whereNotNull('driver_id')->count(),
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_users' => User::whereIn('role', ['civitas', 'umum'])->count(),
            'today_bookings' => Booking::whereDate('booking_date', today())->count(),
            'tip_today_count' => \App\Models\Tip::whereDate('created_at', today())->count(),
            'tip_today_amount' => \App\Models\Tip::whereDate('created_at', today())->sum('amount'),
        ];

        // Pengguna yang sudah melakukan transaksi (booking)
        $active_users = User::whereIn('role', ['civitas', 'umum'])
            ->has('bookings')
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // Booking terbaru
        $recent_bookings = Booking::with(['user', 'bus'])
            ->latest()
            ->take(8)
            ->get();

        // Bus dengan booking terbanyak
        $popular_buses = Bus::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'active_users', 'recent_bookings', 'popular_buses'));
    }
}
