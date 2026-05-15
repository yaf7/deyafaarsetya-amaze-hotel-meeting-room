<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationChat extends Model
{
    protected $fillable = [
        'reservation_id',
        'sender',
        'message',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
