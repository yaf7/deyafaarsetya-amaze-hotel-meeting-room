<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'discount', 'status'
    ];

    protected $casts = [
        'discount' => 'float',  // Persentase (0-100)
        'status' => 'boolean'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
