<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'start_date',
        'end_date',
        'is_active',
        'usage_limit',
        'usage_count',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'discount_package');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive()
    {
        $now = now();
        return $this->is_active && 
               $now->greaterThanOrEqualTo($this->start_date) &&
               ($this->end_date === null || $now->lessThanOrEqualTo($this->end_date));
    }

    public function calculateDiscount($originalPrice)
    {
        if ($this->type === 'percentage') {
            return ($originalPrice * $this->value) / 100;
        }
        return min($this->value, $originalPrice);
    }

    public function getDiscountedPrice($originalPrice)
    {
        return max(0, $originalPrice - $this->calculateDiscount($originalPrice));
    }
}
