<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTestimonial extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'title',
        'description',
        'type',
        'video_path',
        'youtube_url',
        'thumbnail_path',
        'rating',
        'is_featured',
        'is_active',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
    ];

    protected $table = 'video_testimonials';

    /**
     * Relationship: belongs to User (Customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: belongs to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeId()
    {
        if (!$this->youtube_url) return null;
        
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->youtube_url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get video embed URL for iframe
     */
    public function getEmbedUrl()
    {
        if ($this->type === 'youtube') {
            $id = $this->getYoutubeId();
            return $id ? "https://www.youtube.com/embed/{$id}" : null;
        }
        return null;
    }

    /**
     * Scope: active testimonials only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: featured testimonials only
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
