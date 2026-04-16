<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'bus'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('booking_code', 'like', "%{$q}%")
                    ->orWhere('guest_name', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                    );
            });
        }

        $bookings = $query->paginate(15);

        // Stats: gunakan filtered query (tanpa filter status) untuk total & per-status
        // Clone query tanpa filter status agar bisa hitung per-status dari scope saat ini
        $baseQuery = Booking::query();
        if ($request->filled('bus_id'))  $baseQuery->where('bus_id', $request->bus_id);
        if ($request->filled('date'))    $baseQuery->whereDate('booking_date', $request->date);
        if ($request->filled('q')) {
            $q = $request->q;
            $baseQuery->where(function ($sub) use ($q) {
                $sub->where('booking_code', 'like', "%{$q}%")
                    ->orWhere('guest_name',  'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u
                        ->where('name',  'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                    );
            });
        }

        $stats = [
            'total'     => (clone $baseQuery)->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];


        $buses = \App\Models\Bus::all();

        return view('admin.bookings.index', compact('bookings', 'stats', 'buses'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'bus']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Status pemesanan berhasil diperbarui!'
            ]);
        }

        return back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pemesanan berhasil dihapus!');
    }
}
