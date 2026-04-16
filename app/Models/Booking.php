<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bus_id',
        'booking_date',
        'seat_number',
        'status',
        'notes',
        'booking_code',
        'guest_name',
        'guest_phone',
        'payment_method',
        'payment_status',
        'etoll_number',
        'price',
        'is_completed',
        'is_boarded',
        'is_priority',
        'priority_need',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'is_completed' => 'boolean',
        'is_boarded'   => 'boolean',
        'is_priority'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_completed) return 'Selesai';
        return match($this->status) {
            'pending'   => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            default     => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        if ($this->is_completed) return 'violet';
        return match($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'qris'  => 'QRIS',
            'etoll' => 'Kartu E-Tol',
            default => '-',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->booking_code = 'BUS-' . strtoupper(uniqid());
        });
    }

    public function getPassengerNameAttribute(): string
    {
        if ($this->user_id) {
            return $this->user->name ?? 'User Terhapus';
        }
        return $this->guest_name ?? 'Tamu (Umum)';
    }

    public function getPassengerContactAttribute(): string
    {
        if ($this->user_id) {
            return $this->user->email ?? '-';
        }
        return $this->guest_email ?? strval($this->guest_phone) ?? '-';
    }

    public function getPassengerAvatarAttribute(): string
    {
        $name = $this->passenger_name;
        return strtoupper(substr($name, 0, 1));
    }

    /**
     * Auto-cancel unboarded bookings for non-disability passengers after 15 seconds.
     *
     * PERBAIKAN BUG: Hanya cancel booking untuk bus yang sudah BERANGKAT (trip_status='jalan').
     * Sebelumnya, booking dibatalkan berdasarkan created_at saja — menyebabkan booking
     * dibatalkan saat bus masih standby dan sebelum penumpang sempat boarding.
     */
    public static function cleanupExpiredUnconfirmed()
    {
        // Ambil bus ID yang sedang benar-benar berjalan
        $departedBusIds = \Illuminate\Support\Facades\DB::table('buses')
            ->where('trip_status', 'jalan')
            ->where('status', 'active')
            ->pluck('id');

        if ($departedBusIds->isEmpty()) {
            return 0; // Tidak ada bus yang berangkat — tidak perlu cancel apapun
        }

        return static::whereIn('bus_id', $departedBusIds)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->where('is_boarded', false)
            ->where('created_at', '<=', now()->subSeconds(15)) // ganti base ke created_at
            ->where(function ($query) {
                $query->whereNull('priority_need')
                      ->orWhere('priority_need', '!=', 'high');
            })
            ->update([
                'status' => 'cancelled',
                'notes'  => \Illuminate\Support\Facades\DB::raw("CONCAT(COALESCE(notes, ''), ' [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]')")
            ]);
    }

    /**
     * Selesaikan SEMUA booking (termasuk yang auto-cancelled) saat trip selesai.
     *
     * PERBAIKAN BUG: finishTrip sebelumnya hanya update is_completed untuk
     * status 'pending'/'confirmed' — booking yang ter-cancel tetap is_completed=false
     * sehingga card tiket masih menampilkan 'CANCELLED' bukan 'SELESAI'.
     *
     * Logika bisnis benar: jika bus selesai perjalanan, semua booking hari ini
     * (apapun statusnya — termasuk auto-cancelled) harus ditandai is_completed=true
     * agar tampil sebagai 'Selesai' di riwayat perjalanan user.
     */
    public static function completeAllForBus(int $busId): int
    {
        return static::where('bus_id', $busId)
            ->where('booking_date', now()->format('Y-m-d'))
            ->where('is_completed', false)
            ->update([
                'is_completed' => true,
                // Jika ter-cancel otomatis (geofence 15 detik) namun bus sudah selesai:
                // kembalikan status ke 'confirmed' agar ticket tampil 'Selesai' bukan 'Dibatalkan'
                'status' => \Illuminate\Support\Facades\DB::raw(
                    "CASE WHEN status = 'cancelled' AND notes LIKE '%Batal Otomatis%'
                          THEN 'confirmed'
                          ELSE status
                     END"
                ),
            ]);
    }
}
