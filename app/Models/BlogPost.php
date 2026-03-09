<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'tags',
        'excerpt', 'content', 'cover_image',
        'published', 'read_count', 'author',
    ];

    protected $casts = [
        'tags'      => 'array',
        'published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function incrementReads(): void
    {
        $this->increment('read_count');
    }
}