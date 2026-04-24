<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        \App\Models\Booking::cleanupExpiredUnconfirmed();

        // [SIMULATION FIX] Bersihkan ghost tickets dari siklus simulasi sebelumnya
        Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->where('created_at', '<', now()->subMinutes(30)) // Toleransi 30 menit
            ->update(['is_completed' => true]);

        $query = Auth::user()->bookings()->with('bus')->latest();

        if ($request->filled('query')) {
            $q = $request->get('query');
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('booking_code', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhereHas('bus', function ($b) use ($q) {
                        $b->where('name', 'like', "%{$q}%")
                            ->orWhere('plate_number', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'aktif') {
                $query->where('is_completed', false)->whereIn('status', ['confirmed', 'pending']);
            } elseif ($status === 'selesai') {
                $query->where('is_completed', true);
            } elseif ($status === 'batal') {
                $query->where('status', 'cancelled');
            }
        }

        $bookings = $query->paginate(10)->withQueryString();

        // Caching global stats could be better, but auth()->user()->bookings() is fast enough
        return view('user.bookings.index', compact('bookings'));
    }

    public function create(Bus $bus, Request $request)
    {
        \App\Models\Booking::cleanupExpiredUnconfirmed();

        if ($bus->status !== 'active') {
            return back()->with('error', 'Bus ini sedang tidak beroperasi.');
        }

        // Guard realtime: tolak jika bus sudah bukan standby di DB
        if ($bus->trip_status !== 'standby') {
            return back()->with(
                'error',
                'Pemesanan tidak dapat dilakukan. Bus ini saat ini dalam status "' . $bus->trip_status_label . '". Pemesanan hanya tersedia saat bus berstatus Standby di terminal.'
            );
        }

        // [SIMULATION FIX] Bersihkan ghost tickets dari siklus simulasi sebelumnya
        Booking::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->where('created_at', '<', now()->subMinutes(30))
            ->update(['is_completed' => true]);

        // Blokir jika user sudah punya tiket aktif di bus manapun
        $activeBooking = Booking::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->first();

        if ($activeBooking) {
            return redirect()->route('user.bookings.index')
                ->with('error', 'Akses ditolak. Anda masih memiliki tiket yang sedang aktif. Silakan selesaikan perjalanan atau batalkan pesanan sebelumnya untuk memesan lagi.');
        }

        $date = today()->toDateString();

        $bookedSeats = Booking::where('bus_id', $bus->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->pluck('seat_number')
            ->toArray();

        return view('user.bookings.create', compact('bus', 'date', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'booking_date' => 'required|date',
            'selected_seats' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'rute' => 'required|string',
            'payment_method' => 'required|in:qris,etoll',
            'etoll_number' => 'nullable|string|size:16',
            'is_priority' => 'nullable|boolean',
            // 'medium' = Lansia/Hamil, 'high' = Kursi Roda (gratis), 'other' = kondisi medis lain
            'priority_need' => 'nullable|string|in:medium,high,other',
        ]);

        $isPriority = $request->boolean('is_priority');
        $priorityNeed = $isPriority ? ($validated['priority_need'] ?? null) : null;

        // ── Validasi: priority mewajibkan pilihan kategori ────────────────
        if ($isPriority && empty($priorityNeed)) {
            return back()->with(
                'error',
                'Anda mengaktifkan fasilitas prioritas. Silakan pilih tingkat kebutuhan prioritas (Ringan/Sedang atau Tinggi) sebelum melanjutkan.'
            );
        }

        // [DEMO MODE] Pembatasan jam operasional (05:00–21:00 WITA) dinonaktifkan.
        // Aktifkan kembali baris di bawah untuk production:
        // $currentHour = (int) now()->setTimezone('Asia/Makassar')->format('H');
        // if ($currentHour < 5 || $currentHour >= 21) {
        //     return back()->with('error', 'Pemesanan hanya dapat dilakukan pada jam operasional bus (05:00 - 21:00 WITA).');
        // }

        $bus = Bus::findOrFail($validated['bus_id']);

        if ($bus->status !== 'active') {
            return back()->with('error', 'Bus ini sedang tidak beroperasi (status armada: ' . $bus->status . '. Hubungi admin jika ada pertanyaan.');
        }

        // Guard realtime: cek ulang trip_status dari DB sebelum simpan booking
        if ($bus->trip_status !== 'standby') {
            return back()->with(
                'error',
                'Pemesanan gagal. Bus "' . $bus->name . '" saat ini berstatus "' . $bus->trip_status_label . '" dan tidak menerima pemesanan baru. Pilih bus lain yang masih Standby.'
            );
        }

        // ── Parse kursi yang dipilih ──────────────────────────────────────
        $seatsArray = array_values(
            array_filter(array_map('intval', explode(',', $validated['selected_seats'])))
        );

        if (empty($seatsArray)) {
            return back()->with('error', 'Silakan pilih setidaknya 1 kursi.');
        }

        // ── Aturan Keadilan: Batas Kursi per Tipe Pengguna ───────────────
        // Pengguna prioritas tinggi (kursi roda) → maks 1 kursi (aksesibilitas fisik)
        // Pengguna Umum / Tamu                   → maks 1 kursi
        // Pengguna Sivitas Akademika              → maks 4 kursi
        if ($priorityNeed === 'high' && count($seatsArray) > 1) {
            return back()->with(
                'error',
                'Pengguna dengan Prioritas Tinggi (Kursi Roda) hanya dapat memesan 1 kursi prioritas sekaligus demi keadilan bagi penumpang lain.'
            );
        }

        if (Auth::user()->isUmum() && count($seatsArray) > 1) {
            return back()->with(
                'error',
                'Pengguna Umum hanya diperbolehkan memesan 1 kursi per transaksi.'
            );
        }

        if (Auth::user()->isCivitas() && !$isPriority && count($seatsArray) > 4) {
            return back()->with(
                'error',
                'Sivitas Akademika dapat memesan maksimal 4 kursi reguler per transaksi.'
            );
        }

        // ── Smart Blocking: Kursi Prioritas (nomor 1–4) ──────────────────
        $prioritySeats = [1, 2, 3, 4];
        $intersect = array_intersect($seatsArray, $prioritySeats);

        // Jika usia status saat ini masih di bawah 5 detik, maka aturan priority strict berlaku.
        // Setelah 5 detik kosong, kursi 1-4 dapat diklaim oleh pengguna umum.
        $isPriorityStrict = $bus->updated_at->addSeconds(5)->isFuture();

        if (!empty($intersect) && !$isPriority) {
            if ($isPriorityStrict) {
                $waitSeconds = $bus->updated_at->addSeconds(8)->diffInSeconds(now());
                if ($waitSeconds <= 0)
                    $waitSeconds = 1; // display fallback
                return back()->with(
                    'error',
                    'Kursi nomor ' . implode(', ', $intersect) . ' dialokasikan khusus untuk penumpang berkebutuhan prioritas (lansia, ibu hamil, penyandang disabilitas). '
                    . 'Kursi ini baru akan dibuka untuk umum jika masih kosong dalam ' . $waitSeconds . ' detik mendatang.'
                );
            }
        }

        // Pastikan pengguna non-prioritas tidak memenuhi seluruh kursi prioritas
        // (fairness: kursi prioritas hanya boleh diklaim oleh pengguna prioritas)
        if ($isPriority && !empty(array_diff($seatsArray, $prioritySeats))) {
            // Boleh ambil kursi reguler jika prioritas aktif
            // (tidak diblokir — fleksibilitas jika zona prioritas penuh)
        }

        // ── Validasi Metode Pembayaran ────────────────────────────────────
        if ($validated['payment_method'] === 'etoll' && empty($validated['etoll_number'])) {
            return back()->with('error', 'Nomor kartu E-Tol wajib diisi untuk metode pembayaran E-Tol.');
        }

        // Guest/Umum hanya boleh QRIS (tidak memiliki kartu E-Tol kemahasiswaan)
        if (Auth::user()->isUmum() && $validated['payment_method'] === 'etoll') {
            return back()->with(
                'error',
                'Pengguna Umum tidak dapat menggunakan metode E-Tol Kampus Non-Merdeka. Silakan gunakan QRIS.'
            );
        }

        // ── Double-booking guard ──────────────────────────────────────────
        $existingBooking = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->first();

        if ($existingBooking) {
            return back()->with(
                'error',
                'Anda masih memiliki tiket aktif (Kode: ' . $existingBooking->booking_code . '). '
                . 'Selesaikan perjalanan atau batalkan tiket tersebut sebelum memesan tiket baru.'
            );
        }

        // ── Cek konflik kursi (race condition) ───────────────────────────
        $conflictedSeats = Booking::where('bus_id', $bus->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('seat_number', $seatsArray)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->pluck('seat_number')
            ->toArray();

        if (!empty($conflictedSeats)) {
            return back()->with(
                'error',
                'Kursi nomor ' . implode(', ', $conflictedSeats) . ' baru saja dipesan penumpang lain. Silakan pilih kursi lain yang masih tersedia.'
            );
        }

        // ── PRICING ENGINE ────────────────────────────────────────────────
        // Prioritas Tinggi (Kursi Roda) → GRATIS, disubsidi penuh oleh Kampus Non-Merdeka
        // Sivitas Akademika              → Rp 3.000 / kursi (E-Tol)
        // Pengguna Umum                  → Rp 5.000 / kursi (QRIS — subsidi silang)
        if ($priorityNeed === 'high') {
            $pricePerSeat = 0; // Fully subsidized
        } elseif (Auth::user()->isCivitas()) {
            $pricePerSeat = 3000;
        } else {
            $pricePerSeat = 5000;
        }

        // ── Build catatan perjalanan ──────────────────────────────────────
        $finalNotes = 'Arah: ' . $validated['rute'];
        if (!empty($validated['notes'])) {
            $finalNotes .= ' | Catatan: ' . $validated['notes'];
        }
        if ($isPriority && $priorityNeed) {
            $priorityLabels = [
                'medium' => 'Prioritas Ringan/Sedang (Lansia/Hamil)',
                'high' => 'Prioritas Tinggi — Pengguna Kursi Roda (Gratis)',
                'other' => 'Kondisi Medis/Khusus Lainnya',
            ];
            $finalNotes .= ' | Fasilitas: ' . ($priorityLabels[$priorityNeed] ?? strtoupper($priorityNeed));
        }

        // ── Buat booking untuk setiap kursi yang dipilih ─────────────────
        $createdCodes = [];
        foreach ($seatsArray as $seatNumber) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'bus_id' => $validated['bus_id'],
                'booking_date' => $validated['booking_date'],
                'seat_number' => $seatNumber,
                'notes' => $finalNotes,
                'status' => 'confirmed',
                'payment_method' => $validated['payment_method'],
                'payment_status' => $pricePerSeat === 0 ? 'subsidized' : 'paid',
                'etoll_number' => $validated['payment_method'] === 'etoll' ? $validated['etoll_number'] : null,
                'price' => $pricePerSeat,
                'priority_need' => $priorityNeed,
                'is_priority' => $isPriority,
                'is_completed' => false,
            ]);
            $createdCodes[] = $booking->booking_code ?? $seatNumber;
        }

        $seatStr = implode(', ', $seatsArray);
        $priceStr = $pricePerSeat === 0 ? 'GRATIS (Disubsidi)' : 'Rp ' . number_format($pricePerSeat * count($seatsArray), 0, ',', '.');
        $successMsg = 'Tiket berhasil dipesan! Kursi ' . $seatStr . ' dikonfirmasi. Total: ' . $priceStr . '.';
        if ($priorityNeed === 'high') {
            $successMsg .= ' Terima kasih telah menggunakan fasilitas inklusif Kampus Non-Merdeka.';
        }

        return redirect()->route('user.bookings.index')->with('success', $successMsg);
    }

    public function show(Booking $booking)
    {
        if ((int) $booking->user_id !== (int) Auth::id())
            abort(403);
        $booking->load('bus');
        return view('user.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ((int) $booking->user_id !== (int) Auth::id())
            abort(403);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Pemesanan tidak dapat dibatalkan karena statusnya sudah tidak aktif.');
        }

        // Guard: blokir pembatalan jika bus sudah berjalan atau istirahat
        // (perlindungan dua lapis: frontend + backend)
        $bus = $booking->bus;
        if ($bus && in_array($bus->trip_status, ['jalan', 'istirahat'])) {
            return back()->with(
                'error',
                'Pembatalan tidak dapat diproses. Bus "' . $bus->name . '" saat ini berstatus "' .
                ($bus->trip_status === 'jalan' ? 'Sedang Berjalan' : 'Sedang Istirahat') .
                '". Pembatalan hanya diizinkan ketika bus masih Standby di terminal.'
            );
        }

        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Pemesanan tiket ' . $booking->booking_code . ' berhasil dibatalkan.');
    }

    public function validateCheckIn(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id())
            abort(403);

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // Cari halte asumsikan bus->current_terminal atau dari relasi.
        // Kita gunakan $booking->bus->currentTerminal
        $terminal = \App\Models\Terminal::find($booking->bus->current_terminal_id);

        if (!$terminal) {
            return response()->json(['status' => 'error', 'message' => 'Terminal tidak ditemukan untuk bus ini.'], 404);
        }

        $userLat = $request->lat;
        $userLng = $request->lng;

        $earthRadius = 6371000; // in meters
        $latDiff = deg2rad($terminal->lat - $userLat);
        $lngDiff = deg2rad($terminal->lng - $userLng);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($userLat)) * cos(deg2rad($terminal->lat)) *
            sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        $maxRadius = $terminal->geofence_radius ?? 20;

        if ($distance > $maxRadius) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda berada ' . round($distance) . ' meter dari Halte. Jarak maksimum validasi adalah ' . $maxRadius . ' meter.'
            ], 403);
        }

        $booking->update(['is_boarded' => true]);

        return response()->json(['success' => true, 'message' => 'Check-in Geofencing Berhasil. Selamat bergabung!']);
    }

    public function buses()
    {
        $buses = Bus::where('status', 'active')
            ->orderBy('bus_number')
            ->get()
            ->sortBy(function ($bus) {
                $order = ['standby' => 1, 'jalan' => 2, 'istirahat' => 3];
                return $order[$bus->trip_status] ?? 4;
            })->values();
        return view('user.buses', compact('buses'));
    }
}
