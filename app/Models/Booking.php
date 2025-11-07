<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'service_id',
        'client_name',
        'contact_number',
        'booking_datetime',
        'price',
        'status',
        'notes',
        'messenger_psid'
    ];

    protected $casts = [
        'booking_datetime' => 'datetime'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
