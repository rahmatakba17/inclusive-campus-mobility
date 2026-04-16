<?php

namespace App\Http\Controllers\Sopir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $driver = auth()->user();
        $bus = $driver->bus; // Relasi driver memiliki 1 bus

        if (!$bus) {
            return view('sopir.dashboard', [
                'hasBus' => false,
                'message' => 'Anda belum ditugaskan untuk mengendarai armada bus manapun. Silakan hubungi Administrator.'
            ]);
        }

        // Hitung total penumpang hari ini (jadwal keberangkatan hari ini)
        $today = now()->format('Y-m-d');
        $bookingsToday = \App\Models\Booking::where('bus_id', $bus->id)
                            ->where('booking_date', $today)
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->where('is_completed', false)
                            ->orderBy('seat_number', 'asc')
                            ->get();

        $totalPassengers = $bookingsToday->count();

        // Siapkan manifest (data penumpang per kursi)
        $manifest = [];
        for ($i = 1; $i <= $bus->capacity; $i++) {
            $booking = $bookingsToday->firstWhere('seat_number', $i);
            $manifest[$i] = $booking;
        }

        // Tip hari ini untuk bus ini
        $tipToday = \App\Models\Tip::where('bus_id', $bus->id)
            ->whereDate('created_at', $today)
            ->sum('amount');
        $tipCount = \App\Models\Tip::where('bus_id', $bus->id)
            ->whereDate('created_at', $today)
            ->count();

        return view('sopir.dashboard', [
            'hasBus' => true,
            'bus' => $bus,
            'totalPassengers' => $totalPassengers,
            'manifest' => $manifest,
            'today' => $today,
            'tipToday' => $tipToday,
            'tipCount' => $tipCount,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $driver = auth()->user();
        $bus = $driver->bus;

        if (!$bus) {
            return back()->with('error', 'Bus tidak ditemukan.');
        }

        $validated = $request->validate([
            'trip_status' => 'required|in:standby,jalan,istirahat'
        ]);

        $canceledCount = 0;
        // [SIMULATION FIX] Sopir Tidak Menunggu Logic - DINONAKTIFKAN
        // Karena simulasi berjalan cepat (20x), pengguna sering kali belum sempat "Check-in"
        // di halte, sehingga otomatis tercancel saat status trip simulasi pindah ke "jalan".
        /*
        if ($validated['trip_status'] === 'jalan' && $bus->trip_status === 'standby') {
            $canceledCount = \App\Models\Booking::where('bus_id', $bus->id)
                ->where('booking_date', now()->format('Y-m-d'))
                ->where('is_completed', false)
                ->where('is_boarded', false)
                ->whereIn('status', ['pending', 'confirmed'])
                ->update([
                    'status' => 'cancelled',
                    'notes' => 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan'
                ]);
        }
        */

        $bus->update([
            'trip_status' => $validated['trip_status']
        ]);

        $message = 'Status perjalanan bus berhasil diperbarui menjadi ' . strtoupper($validated['trip_status']);
        if ($canceledCount > 0) {
            $message .= '. (Auto-Cancel: ' . $canceledCount . ' manifest kedaluwarsa dibersihkan).';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status'  => $bus->trip_status
            ]);
        }

        return back()->with('success', $message);
    }

    public function finishTrip(Request $request)
    {
        $driver = auth()->user();
        $bus = $driver->bus;

        if (!$bus) {
            return response()->json(['success' => false, 'message' => 'Bus tidak ditemukan.'], 404);
        }

        // Set bus to standby
        $bus->update(['trip_status' => 'standby']);

        // [PERBAIKAN BUG] Selesaikan seluruh data penumpang (termasuk auto-cancel)
        // agar tidak mengambang sebagai uncompleted cancelled ticket
        \App\Models\Booking::completeAllForBus($bus->id);

        return response()->json([
            'success' => true,
            'message' => 'Perjalanan selesai. Manifest telah di-reset.'
        ]);
    }

    public function checkTips()
    {
        $driver = auth()->user();
        $bus = $driver->bus;

        if (!$bus) return response()->json(['tips' => []]);

        $newTips = \App\Models\Tip::where('bus_id', $bus->id)
                    ->where('is_read', false)
                    ->get();
                    
        if ($newTips->count() > 0) {
            // Mark as read
            \App\Models\Tip::whereIn('id', $newTips->pluck('id'))->update(['is_read' => true]);
        }

        return response()->json([
            'tips' => $newTips,
            'total_amount' => $newTips->sum('amount')
        ]);
    }

    public function storeReport(Request $request)
    {
        $driver = auth()->user();
        $bus = $driver->bus;

        if (!$bus) {
            return back()->with('error', 'Bus tidak ditemukan.');
        }

        $validated = $request->validate([
            'condition' => 'required|in:good,needs_maintenance,damaged',
            'notes' => 'required|string|max:1500'
        ]);

        \App\Models\BusReport::create([
            'bus_id' => $bus->id,
            'user_id' => $driver->id,
            'type' => 'daily_inspection',
            'condition' => $validated['condition'],
            'notes' => $validated['notes']
        ]);

        return back()->with('success', 'Laporan harian inspeksi armada berhasil dikirim!');
    }

    public function boardPassenger(\App\Models\Booking $booking)
    {
        $driver = auth()->user();
        if (!$driver->bus || $booking->bus_id !== $driver->bus->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        // Jika tiket dibatalkan otomatis oleh sistem (bot 15 detik), biarkan sopir "menyelamatkannya" (Revive)
        $isAutoCancelled = $booking->status === 'cancelled' && str_contains($booking->notes ?? '', 'Batal Otomatis: Waktu konfirmasi 15 detik');

        if (!$isAutoCancelled) {
            if ($booking->is_boarded || $booking->is_completed || !in_array($booking->status, ['pending', 'confirmed'])) {
                return response()->json(['success' => false, 'message' => 'Tiket sudah tidak valid atau sudah tervalidasi.'], 400);
            }
        }

        // ── [FITUR BARU] Konfirmasi Kolektif (Grup) ─────────────────────────
        // Jika user memesan lebih dari 1 kursi, otomatis konfirmasi semua tiket user tersebut di bus ini.
        $query = \App\Models\Booking::where('bus_id', $booking->bus_id)
            ->where('booking_date', $booking->booking_date)
            ->where('is_boarded', false)
            ->where('is_completed', false);

        if ($booking->user_id) {
            $query->where('user_id', $booking->user_id);
        } else {
            $query->whereNull('user_id')
                  ->where('guest_name', $booking->guest_name)
                  ->where('guest_phone', $booking->guest_phone);
        }

        $relatedBookings = $query->get();
        $confirmedCount = $relatedBookings->count();

        foreach ($relatedBookings as $relatedBooking) {
            $relatedBooking->update([
                'is_boarded' => true,
                'status'     => 'confirmed', // Revive status if it was cancelled
                'notes'      => str_replace(' [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', ' [Diselamatkan oleh Sopir]', $relatedBooking->notes ?? '')
            ]);
        }

        $passengerName = $booking->user ? $booking->user->name : $booking->guest_name;
        $message = "Penumpang {$passengerName} berhasil dikonfirmasi.";
        if ($confirmedCount > 1) {
            $message = "MANTAP! {$confirmedCount} tiket milik {$passengerName} berhasil terkonfirmasi serentak sekali klik!";
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
