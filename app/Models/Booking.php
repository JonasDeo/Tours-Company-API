<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'quote_id', 'tour_id',
        'client_name', 'client_email', 'client_phone',
        'arrival_date', 'adults', 'children',
        'total_amount', 'currency', 'paid', 'paid_at',
        'status', 'notes',
    ];

    protected $casts = [
        'paid'         => 'boolean',
        'paid_at'      => 'datetime',
        'arrival_date' => 'date',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}