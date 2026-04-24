<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use App\Models\Tip;

class NotificationController extends Controller
{
    /**
     * Return JSON notifications for the authenticated user.
     * Results are cached per-user for 30 seconds to reduce DB load.
     *
     * Admin  → recent bookings (pending + confirmed)
     * Sopir  → tips received for their bus
     * User   → their own booking status changes
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Cache key unique per user — clears when they mark-all-read
        $cacheKey = "notifications_user_{$user->id}";

        $result = Cache::remember($cacheKey, 30, function () use ($user) {
            return $this->buildNotifications($user);
        });

        return response()->json($result)
            ->header('Cache-Control', 'no-store')
            ->header('X-Cache-TTL', '30');
    }

    /**
     * Mark all as read — clears the user's notification cache.
     */
    public function markAllRead(Request $request)
    {
        $user = auth()->user();
        Cache::forget("notifications_user_{$user->id}");

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────

    private function buildNotifications($user): array
    {
        $notifications = collect();

        if ($user->isAdmin()) {
            $notifications = $this->adminNotifications();

        } elseif ($user->isSopir()) {
            $notifications = $this->sopirNotifications($user);

        } else {
            $notifications = $this->userNotifications($user);
        }

        $unreadCount = $notifications->where('unread', true)->count();

        return [
            'notifications' => $notifications->values()->all(),
            'unread_count'  => $unreadCount,
        ];
    }

    private function adminNotifications(): \Illuminate\Support\Collection
    {
        $pending = Booking::with('bus')
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

        $confirmed = Booking::with('bus')
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

        return $pending->concat($confirmed)->sortByDesc('unread')->values();
    }

    private function sopirNotifications($user): \Illuminate\Support\Collection
    {
        $bus = \App\Models\Bus::where('driver_id', $user->id)->first();
        if (!$bus) return collect();

        return Tip::where('bus_id', $bus->id)
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
    }

    private function userNotifications($user): \Illuminate\Support\Collection
    {
        $bookings = Booking::with('bus')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(12)
            ->get();

        $notifs = collect();

        foreach ($bookings as $b) {
            if ($b->is_completed) {
                $notifs->push([
                    'id'      => 'booking-completed-' . $b->id,
                    'icon'    => 'flag-checkered',
                    'color'   => 'indigo',
                    'title'   => 'Perjalanan Selesai 🏁',
                    'message' => 'Anda telah tiba di tujuan. Terima kasih telah menggunakan ' . ($b->bus->name ?? 'Bus Kampus') . '!',
                    'time'    => $b->updated_at->diffForHumans(),
                    'link'    => route('user.bookings.show', $b->id),
                    'unread'  => $b->updated_at->gt(now()->subMinutes(60)),
                ]);
            } else {
                $title   = match($b->status) {
                    'confirmed' => 'Booking Berhasil ✓',
                    'cancelled' => 'Booking Dibatalkan ❌',
                    default     => 'Menunggu Pembayaran',
                };
                $message = match($b->status) {
                    'confirmed' => 'E-Tiket Anda untuk ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') siap digunakan.',
                    'cancelled' => 'Pemesanan di ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') telah dibatalkan.',
                    default     => 'Booking Anda di ' . ($b->bus->name ?? 'Bus') . ' (Kursi #' . $b->seat_number . ') sedang diverifikasi.',
                };
                $color = match($b->status) { 'confirmed' => 'emerald', 'cancelled' => 'rose', default => 'amber' };
                $icon  = match($b->status) { 'confirmed' => 'ticket-alt', 'cancelled' => 'ban', default => 'clock' };

                $notifs->push([
                    'id'      => 'booking-' . $b->status . '-' . $b->id,
                    'icon'    => $icon,
                    'color'   => $color,
                    'title'   => $title,
                    'message' => $message,
                    'time'    => $b->updated_at->diffForHumans(),
                    'link'    => route('user.bookings.show', $b->id),
                    'unread'  => ($b->status === 'pending') || $b->updated_at->gt(now()->subMinutes(60)),
                ]);
            }
        }

        return $notifs;
    }
}
