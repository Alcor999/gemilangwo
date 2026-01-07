<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'available_from',
        'available_to',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'available_from' => 'date',
        'available_to' => 'date',
        'is_available' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Scopes
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('available_from', '<=', now())
                     ->where('available_to', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('available_from', '>', now())
                     ->where('is_available', true);
    }

    /**
     * Methods
     */
    public function isAvailableOn($date)
    {
        $checkDate = Carbon::parse($date)->toDateString();
        return $this->is_available &&
               $this->available_from <= $checkDate &&
               $this->available_to >= $checkDate;
    }

    public function getDaysUnavailable()
    {
        return $this->available_to->diffInDays($this->available_from) + 1;
    }
}
