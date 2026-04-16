<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'lat',
        'lng',
        'order',
        'type',
        'description',
    ];

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'origin'      => 'Terminal Awal',
            'destination' => 'Terminal Akhir',
            default       => 'Halte Pemberhentian',
        };
    }
}
