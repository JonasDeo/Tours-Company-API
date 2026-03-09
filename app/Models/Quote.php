<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'country',
        'trip_types', 'destinations', 'experiences', 'occasions',
        'accommodation', 'adults', 'children', 'arrival_date',
        'message', 'status', 'admin_notes',
    ];

    protected $casts = [
        'trip_types'   => 'array',
        'destinations' => 'array',
        'experiences'  => 'array',
        'occasions'    => 'array',
        'arrival_date' => 'date',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}