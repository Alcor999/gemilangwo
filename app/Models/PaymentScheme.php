<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentScheme extends Model
{
    protected $fillable = [
        'name',
        'code',
        'breakdown',
        'min_days_before_event',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'breakdown' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
