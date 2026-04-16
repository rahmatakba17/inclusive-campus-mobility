<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['civitas', 'umum'])
            ->withCount('bookings')
            ->with(['bookings' => function ($q) {
                $q->latest()->take(1);
            }]);

        if ($request->has('search') && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->orderByDesc('bookings_count')
            ->paginate(15);

        $stats = [
            'total_users' => User::whereIn('role', ['civitas', 'umum'])->count(),
            'active_users' => User::whereIn('role', ['civitas', 'umum'])->has('bookings')->count(),
            'today_users' => User::whereIn('role', ['civitas', 'umum'])
                ->whereHas('bookings', fn($q) => $q->whereDate('booking_date', today()))
                ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $bookings = $user->bookings()->with('bus')->latest()->paginate(10);
        $stats = [
            'total' => $user->bookings()->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'pending' => $user->bookings()->where('status', 'pending')->count(),
            'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
        ];
        return view('admin.users.show', compact('user', 'bookings', 'stats'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusName = $user->is_active ? 'Diaktifkan' : 'Dinonaktifkan';
        return back()->with('success', "Status akun pengguna {$user->name} berhasil {$statusName}.");
    }
}
