<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationBuffetSelection extends Model
{
    protected $fillable = ['reservation_id', 'buffet_menu_id'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function buffetMenu()
    {
        return $this->belongsTo(BuffetMenu::class);
    }
}
