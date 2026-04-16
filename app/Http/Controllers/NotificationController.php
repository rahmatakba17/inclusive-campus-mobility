<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Tip;

class NotificationController extends Controller
{
    /**
     * Return JSON notifications for the authenticated user.
     * Admin  → recent bookings that are pending + recent tips
     * Sopir  → tips received for their bus
     * User   → their own booking status changes
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $notifications = collect();

        if ($user->isAdmin()) {
            // Pending bookings (unconfirmed)
            $pendingBookings = Booking::with('bus')
                ->where('status', 'pending')
                ->latest()
                ->take(8)
                ->get()
                ->map(fn($b) => [
                    'id'      => 'booking-' . $b->id,
                    'icon'    => 'ticket',
                    'color'   => 'amber',
                    'title'   => 'Pemesanan Baru',
                    'message' => ($b->passenger_name) . ' memesan Kursi #' . $b->seat_number . ' di ' . ($b->bus->name ?? '-'),
                    'time'    => $b->created_at->diffForHumans(),
                    'link'    => route('admin.bookings.show', $b->id),
                    'unread'  => true,
                ]);

            // Recent confirmed bookings (last 5)
            $confirmedBookings = Booking::with('bus')
                ->where('status', 'confirmed')
                ->latest()
                ->take(4)
                ->get()
                ->map(fn($b) => [
                    'id'      => 'confirmed-' . $b->id,
                    'icon'    => 'check-circle',
                    'color'   => 'emerald',
                    'title'   => 'Booking Dikonfirmasi',
                    'message' => ($b->passenger_name) . ' — ' . ($b->bus->name ?? '-'),
                    'time'    => $b->updated_at->diffForHumans(),
                    'link'    => route('admin.bookings.show', $b->id),
                    'unread'  => false,
                ]);

            $notifications = $pendingBookings->concat($confirmedBookings)->sortByDesc('unread')->values();

        } elseif ($user->isSopir()) {
            // Tips for the driver's bus
            $bus = \App\Models\Bus::where('driver_id', $user->id)->first();
            if ($bus) {
                $tips = Tip::where('bus_id', $bus->id)
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn($t) => [
                        'id'      => 'tip-' . $t->id,
                        'icon'    => 'gift',
                        'color'   => 'rose',
                        'title'   => 'Tip Masuk 🎁',
                        'message' => 'Penumpang memberikan tip Rp ' . number_format($t->amount, 0, ',', '.'),
                        'time'    => $t->created_at->diffForHumans(),
                        'link'    => '#',
                        'unread'  => $t->created_at->gt(now()->subHours(1)),
                    ]);
                $notifications = $tips;
            }
        } else {
            // Regular user – their bookings (umum/khusus/civitas)
            $bookings = Booking::with('bus')
                ->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->take(12)
                ->get();
                
            $userNotifs = collect();

            foreach ($bookings as $b) {
                // Notifikasi 1: Jika perjalanan selesai (bus tiba di tujuan)
                if ($b->is_completed) {
                    $userNotifs->push([
                        'id'      => 'booking-completed-' . $b->id,
                        'icon'    => 'flag-checkered',
                        'color'   => 'indigo',
                        'title'   => 'Perjalanan Selesai 🏁',
                        'message' => 'Anda telah tiba di tujuan. Terima kasih telah menggunakan ' . ($b->bus->name ?? 'Bus Kampus') . '!',
                        'time'    => $b->updated_at->diffForHumans(),
                        'link'    => route('user.bookings.show', $b->id),
                        'unread'  => $b->updated_at->gt(now()->subMinutes(60)), // Red dot selama 1 jam
                    ]);
                } 
                // Jika belum selesai, periksa statusnya (pending/confirmed/cancelled)
                else {
                    $title = match($b->status) {
                        'confirmed' => 'Booking Berhasil ✓',
                        'cancelled' => 'Booking Dibatalkan ❌',
                        default     => 'Menunggu Pembayaran',
                    };
                    
                    $message = match($b->status) {
                        'confirmed' => 'E-Tiket Anda untuk ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') siap digunakan.',
                        'cancelled' => 'Pemesanan di ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') telah dibatalkan.',
                        default     => 'Booking Anda di ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') sedang diverifikasi.',
                    };

                    $color = match($b->status) {
                        'confirmed' => 'emerald',
                        'cancelled' => 'rose',
                        default     => 'amber',
                    };

                    $icon = match($b->status) {
                        'confirmed' => 'ticket-alt',
                        'cancelled' => 'ban',
                        default     => 'clock',
                    };
                    
                    $isRecent = $b->updated_at->gt(now()->subMinutes(60));
                    $unread = ($b->status === 'pending') || $isRecent;

                    $userNotifs->push([
                        'id'      => 'booking-' . $b->status . '-' . $b->id,
                        'icon'    => $icon,
                        'color'   => $color,
                        'title'   => $title,
                        'message' => $message,
                        'time'    => $b->updated_at->diffForHumans(),
                        'link'    => route('user.bookings.show', $b->id),
                        'unread'  => $unread,
                    ]);
                }
            }

            // Urutkan berdasarkan yang paling baru
            $notifications = $userNotifs;
        }

        $unreadCount = $notifications->where('unread', true)->count();

        return response()->json([
            'notifications' => $notifications->values(),
            'unread_count'  => $unreadCount,
        ]);
    }
}
