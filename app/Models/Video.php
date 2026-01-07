<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'type',
        'video_path',
        'youtube_url',
        'thumbnail_path',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: belongs to Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
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
     * Scope: active videos only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
