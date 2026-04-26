<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bus_number',
        'driver_id',
        'plate_number',
        'capacity',
        'route',
        'departure_time',
        'arrival_time',
        'description',
        'image',
        'status',
        'trip_status',
        'current_lat',
        'current_lng',
        'current_terminal',
        'departed_at',
    ];

    protected $casts = [
        'departed_at'   => 'datetime',
        'current_lat'   => 'float',
        'current_lng'   => 'float',
        'bus_number'    => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function tips()
    {
        return $this->hasMany(Tip::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reports()
    {
        return $this->hasMany(BusReport::class);
    }

    public function getAvailableSeatsAttribute(): int
    {
        $taken = $this->bookings()
            ->whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('is_completed', false)
            ->count();
        return max(0, $this->capacity - $taken);
    }

    /**
     * Apakah bus bisa menerima booking?
     * Hanya saat standby (di terminal awal)
     */
    public function isBookable(): bool
    {
        if ($this->status !== 'active') return false;
        if (is_null($this->driver_id)) return false;
        if ($this->trip_status === 'standby') return true;
        return false;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active'      => 'Aktif',
            'maintenance' => 'Perawatan',
            'inactive'    => 'Tidak Aktif',
            default       => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active'      => 'green',
            'maintenance' => 'yellow',
            'inactive'    => 'red',
            default       => 'gray',
        };
    }

    public function getTripStatusLabelAttribute(): string
    {
        return match($this->trip_status) {
            'jalan'     => 'Sedang Jalan',
            'istirahat' => 'Istirahat',
            'standby'   => 'Standby (Siap)',
            default     => 'Standby',
        };
    }

    public function getTripStatusColorAttribute(): string
    {
        return match($this->trip_status) {
            'jalan'     => 'green',
            'istirahat' => 'red',
            'standby'   => 'yellow',
            default     => 'yellow',
        };
    }

    public function getBusCodeAttribute(): string
    {
        return 'BUS-' . str_pad($this->bus_number ?? $this->id, 2, '0', STR_PAD_LEFT);
    }
}
