<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'capacity',
        'facilities',
        'layout',
        'price',
        'photo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'layout' => 'array',    // Otomatis konversi JSON ↔ array
        'capacity' => 'integer',
        'price' => 'integer',
    ];

    /**
     * Relasi ke reservasi.
     */
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class);
    }
}
