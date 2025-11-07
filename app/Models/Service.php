<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image',
        'is_active'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
