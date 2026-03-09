<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tour extends Model
{
    protected $fillable = [
        'title', 'slug', 'destination', 'type',
        'duration_days', 'price', 'currency',
        'description', 'excerpt', 'images',
        'included', 'excluded', 'tags', 'published',
    ];

    protected $casts = [
        'images'    => 'array',
        'included'  => 'array',
        'excluded'  => 'array',
        'tags'      => 'array',
        'published' => 'boolean',
    ];

    // Auto-generate slug from title
    protected static function booted(): void
    {
        static::creating(function (Tour $tour) {
            if (empty($tour->slug)) {
                $tour->slug = Str::slug($tour->title);
            }
        });
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Only return published tours for public queries
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}