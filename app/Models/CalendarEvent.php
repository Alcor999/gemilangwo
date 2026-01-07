<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'package_id',
        'event_date',
        'event_start',
        'event_end',
        'status',
        'notes',
        'is_confirmed',
        'confirmed_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_start' => 'date',
        'event_end' => 'date',
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scopes
     */
    public function scopeConfirmed($query)
    {
        return $query->where('is_confirmed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_confirmed', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInMonth($query, $month, $year)
    {
        return $query->whereYear('event_date', $year)
            ->whereMonth('event_date', $month);
    }

    public function scopeInRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_date', [$startDate, $endDate]);
    }

    /**
     * Calculate event days including pre and post event
     */
    public function calculateEventDays()
    {
        $startDate = $this->event_start ?? $this->event_date;
        $endDate = $this->event_end ?? $this->event_date;
        
        return $endDate->diffInDays($startDate) + 1;
    }

    /**
     * Get all dates occupied by this event
     */
    public function getOccupiedDates()
    {
        $startDate = $this->event_start ?? $this->event_date;
        $endDate = $this->event_end ?? $this->event_date;
        
        $dates = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }
        
        return $dates;
    }

    /**
     * Confirm the event on calendar
     */
    public function confirm()
    {
        $this->update([
            'is_confirmed' => true,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Tertunda',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming()
    {
        return $this->event_date->isFuture();
    }

    /**
     * Check if event is past
     */
    public function isPast()
    {
        return $this->event_date->isPast();
    }

    /**
     * Get display color based on status
     */
    public function getColorClass()
    {
        return match($this->status) {
            'pending' => 'bg-blue-200 border-blue-500',
            'confirmed' => 'bg-green-200 border-green-500',
            'in_progress' => 'bg-purple-200 border-purple-500',
            'completed' => 'bg-gray-200 border-gray-500',
            default => 'bg-gray-100 border-gray-400',
        };
    }

    /**
     * Create event from order
     */
    public static function createFromOrder(Order $order)
    {
        $eventStart = $order->event_date->copy()->subDays($order->pre_event_days ?? 0);
        $eventEnd = $order->event_date->copy()->addDays($order->post_event_days ?? 0);

        return self::create([
            'order_id' => $order->id,
            'package_id' => $order->package_id,
            'event_date' => $order->event_date,
            'event_start' => $eventStart,
            'event_end' => $eventEnd,
            'status' => 'pending',
        ]);
    }
}
