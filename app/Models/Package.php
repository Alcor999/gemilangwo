<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'max_guests',
        'features',
        'image',
        'status',
        'owner_id',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
        ];
    }

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_package');
    }

    public function activeDiscounts()
    {
        return $this->discounts()->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            });
    }

    public function getDiscountedPrice()
    {
        $discount = $this->activeDiscounts()->first();
        if (!$discount) {
            return $this->price;
        }
        return $discount->getDiscountedPrice($this->price);
    }

    public function getActiveDiscount()
    {
        return $this->activeDiscounts()->first();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true)->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
    }

    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getRatingDistribution()
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $this->approvedReviews()->where('rating', $i)->count();
        }
        return $distribution;
    }

    public function getTotalReviews()
    {
        return $this->approvedReviews()->count();
    }

    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class)->ordered();
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }

    public function videoTestimonials()
    {
        return $this->hasManyThrough(
            VideoTestimonial::class,
            Order::class,
            'package_id',
            'order_id'
        );
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'package_id', 'user_id')->withTimestamps();
    }

    public function getTotalWishlists()
    {
        return $this->wishlists()->count();
    }

    public function availability()
    {
        return $this->hasManyThrough(
            Availability::class,
            User::class,
            'id',
            'owner_id',
            'owner_id'
        );
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function activeBlockedDates()
    {
        return $this->blockedDates()->where('is_active', true)->currentAndFuture();
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function confirmedCalendarEvents()
    {
        return $this->calendarEvents()->where('is_confirmed', true);
    }
}

