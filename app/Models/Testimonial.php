<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'location', 'avatar', 'text', 'rating', 'featured', 'sort_order',
    ];

    protected $casts = [
        'rating'   => 'integer',
        'featured' => 'boolean',
        'sort_order' => 'integer',
    ];
}