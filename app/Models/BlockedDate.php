<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BlockedDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id',
        'start_date',
        'end_date',
        'reason',
        'block_type',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentAndFuture($query)
    {
        return $query->where('end_date', '>=', now()->toDateString());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('block_type', $type);
    }

    /**
     * Check if a specific date is blocked
     */
    public static function isDateBlocked($packageId, $date)
    {
        return self::where('package_id', $packageId)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }

    /**
     * Get all blocked dates in a date range
     */
    public static function getBlockedDatesInRange($packageId, $startDate, $endDate)
    {
        return self::where('package_id', $packageId)
            ->where('is_active', true)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->get();
    }

    /**
     * Check if date range overlaps with any blocked dates
     */
    public static function hasOverlapInRange($packageId, $startDate, $endDate)
    {
        return self::where('package_id', $packageId)
            ->where('is_active', true)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }

    /**
     * Get display color for calendar based on block type
     */
    public function getColorClass()
    {
        return match($this->block_type) {
            'unavailable' => 'bg-red-200 border-red-500',
            'maintenance' => 'bg-orange-200 border-orange-500',
            'reserved' => 'bg-yellow-200 border-yellow-500',
            'personal' => 'bg-purple-200 border-purple-500',
            default => 'bg-gray-200 border-gray-500',
        };
    }

    /**
     * Get display label for block type
     */
    public function getTypeLabel()
    {
        return match($this->block_type) {
            'unavailable' => 'Tidak Tersedia',
            'maintenance' => 'Maintenance',
            'reserved' => 'Dipesan',
            'personal' => 'Personal',
            default => 'Lainnya',
        };
    }
}
