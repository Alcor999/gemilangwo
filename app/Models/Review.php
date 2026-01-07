<?php

namespace App\Models;

use App\Mail\AdminNotificationMail;
use App\Mail\ReviewSubmissionMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'order_id',
        'rating',
        'title',
        'content',
        'helpful_count',
        'unhelpful_count',
        'is_verified',
        'is_approved',
        'is_featured',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function (Review $review) {
            // Send review submission email to customer
            Mail::to($review->user->email)
                ->queue(new ReviewSubmissionMail($review));

            // Send admin notification
            Mail::to(config('app.admin_email', 'admin@gemilangwo.test'))
                ->queue(new AdminNotificationMail('new_review', [
                    'review_id' => $review->id,
                    'customer_name' => $review->user->name,
                    'package_name' => $review->package->name,
                    'rating' => $review->rating,
                    'title' => $review->title,
                ]));
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Methods
    public function getAverageHelpfulness()
    {
        $total = $this->helpful_count + $this->unhelpful_count;
        if ($total === 0) return 0;
        return round(($this->helpful_count / $total) * 100);
    }

    public function getStarDisplay()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
