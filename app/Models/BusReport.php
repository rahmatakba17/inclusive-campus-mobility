<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'user_id',
        'type',
        'condition',
        'notes',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
