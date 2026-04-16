<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCivitas()
    {
        return $this->role === 'civitas';
    }

    public function isUmum()
    {
        return $this->role === 'umum';
    }

    public function isSopir()
    {
        return $this->role === 'sopir';
    }

    public function roleBadgeClass()
    {
        return match($this->role) {
            'admin'   => 'bg-blue-100 text-blue-700',
            'civitas' => 'bg-green-100 text-green-700',
            'sopir'   => 'bg-yellow-100 text-yellow-700',
            default   => 'bg-gray-100 text-gray-700',
        };
    }

    public function roleNameDisplay()
    {
        return match($this->role) {
            'admin'   => 'Administrator',
            'civitas' => 'Sivitas Akademika',
            'sopir'   => 'Sopir Bus',
            default   => 'Pengguna Umum',
        };
    }

    public function bus()
    {
        return $this->hasOne(Bus::class, 'driver_id');
    }

    // Laporan yang di-submit oleh user ini (sebagai sopir atau admin)
    public function busReports()
    {
        return $this->hasMany(BusReport::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
