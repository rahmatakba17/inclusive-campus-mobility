<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestBookingController extends Controller
{
    public function buses()
    {
        $buses = Bus::where('status', 'active')
            ->orderByRaw("FIELD(trip_status, 'standby', 'jalan', 'istirahat')")
            ->get();
        return view('guest.buses', compact('buses'));
    }

    public function create(Bus $bus, Request $request)
    {
        if ($bus->status !== 'active') {
            return redirect()->route('guest.buses')->with('error', 'Bus ini sedang tidak beroperasi.');
        }

        // Guard realtime: tolak jika bus sudah bukan standby di DB
        if ($bus->trip_status !== 'standby') {
            return redirect()->route('guest.buses')->with('error',
                'Pemesanan tidak dapat dilakukan. Bus ' . $bus->name . ' sudah beranjak dari terminal. Silakan pilih armada lain yang bersiap.');
        }

        $date = $request->get('date', now()->format('Y-m-d'));
        
        $bookedSeats = Booking::where('bus_id', $bus->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_number')
            ->toArray();

        return view('guest.create', compact('bus', 'date', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'selected_seats' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'rute' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $finalNotes = "Arah: " . $validated['rute'];
        if (!empty($validated['notes'])) {
            $finalNotes .= " | Catatan: " . $validated['notes'];
        }

        $seats = explode(',', $validated['selected_seats']);
        
        // Guest only allowed 1 seat per transaction (based on user rule)
        if (count($seats) > 1) {
            return back()->with('error', 'Tamu hanya diperbolehkan memesan 1 kursi per transaksi.');
        }

        $bus = Bus::findOrFail($validated['bus_id']);

        if ($bus->status !== 'active') {
            return redirect()->route('guest.buses')->with('error', 'Bus ini sedang tidak beroperasi.');
        }

        // Guard realtime server-side: cek ulang trip_status dari DB sebelum simpan booking
        if ($bus->trip_status !== 'standby') {
            return redirect()->route('guest.buses')->with('error',
                'Pemesanan gagal diproses. Bus ' . $bus->name . ' telah berangkat. Otomatis dialihkan kembali ke daftar bus prioritas.');
        }

        // Validasi jam operasional
        $currentHour = (int) now()->format('H');
        if ($currentHour < 5 || $currentHour >= 21) {
            return back()->with('error', 'Pemesanan hanya dapat dilakukan pada jam operasional (05:00 - 21:00 WITA).');
        }

        // Validasi jadwal bus
        $currentTime = now()->format('H:i');
        if ($currentTime > $bus->arrival_time) {
            return back()->with('error', "Pemesanan untuk bus ini sudah ditutup. Jadwal bus: {$bus->departure_time} – {$bus->arrival_time} WITA.");
        }
        
        // Check if seats are already taken
        $existing = Booking::where('bus_id', $bus->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('seat_number', $seats)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Kursi yang Anda pilih sudah terisi.');
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => null, // Guest
                'bus_id' => $bus->id,
                'booking_date' => $validated['booking_date'],
                'seat_number' => $seats[0],
                'status' => 'pending',
                'notes' => $finalNotes,
                'guest_name' => $validated['guest_name'],
                'guest_phone' => $validated['guest_phone'],
                'price' => 5000,
                'is_boarded' => true,
            ]);

            DB::commit();

            return redirect()->route('guest.booking.success', $booking->booking_code)
                ->with('success', 'Pemesanan berhasil! Silakan simpan kode booking Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage());
        }
    }

    public function success($code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        return view('guest.success', compact('booking'));
    }
}
