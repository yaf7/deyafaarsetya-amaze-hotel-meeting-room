<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'meeting_room_id',
        'food_package_id',
        'promotion_id',
        'admin_id',
        'customer_id',
        'customer_name',
        'phone',
        'date',
        'time',
        'participants',
        'layout',
        'residential_type',
        'total_price',
        'status',
        'whatsapp_sent',
        'whatsapp_sent_at',
        'reschedule_status',
        'requested_reschedule_date',
        'requested_reschedule_session',
        'reschedule_rejection_reason',
        'reschedule_count',
        'reschedule_notification_read'
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'integer',
        'participants' => 'integer',
        'whatsapp_sent' => 'boolean',
        'whatsapp_sent_at' => 'datetime',
        'requested_reschedule_date' => 'date',
        'reschedule_count' => 'integer',
        'reschedule_notification_read' => 'boolean'
    ];

    // Relasi
    public function chats()
    {
        return $this->hasMany(ReservationChat::class);
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function foodPackage()
    {
        return $this->belongsTo(FoodPackage::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function buffetSelections()
    {
        return $this->hasMany(ReservationBuffetSelection::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
