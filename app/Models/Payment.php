<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'payment_method',
        'payment_status',
        'transaction_id',
        'snap_token',
        'gross_amount'
    ];

    protected $casts = [
        'reservation_id' => 'integer'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
