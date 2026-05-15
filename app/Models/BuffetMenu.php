<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetMenu extends Model
{
    protected $fillable = ['name', 'category'];

    public function buffetSelections()
    {
        return $this->hasMany(ReservationBuffetSelection::class);
    }
}
