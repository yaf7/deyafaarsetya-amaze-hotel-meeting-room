<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackage extends Model
{
    protected $fillable = [
        'name', 'price', 'description'
    ];

    protected $casts = [
        'price' => 'integer'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
