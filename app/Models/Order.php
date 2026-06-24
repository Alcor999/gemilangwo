<?php

namespace App\Models;

use App\Mail\AdminNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusMail;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'order_number',
        'event_date',
        'event_location',
        'guest_count',
        'special_request',
        'total_price',
        'extra_guest_charge',
        'payment_scheme',
        'custom_schedules',
        'dp_percentage',
        'total_paid',
        'remaining_amount',
        'payment_status',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_scheme' => 'full_payment',
        'payment_status' => 'unpaid',
    ];

    protected static function booted()
    {
        static::created(function (Order $order) {
            // Send order confirmation email to customer
            Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));

            // Send admin notification
            Mail::to(config('app.admin_email', 'admin@gemilangwo.test'))
                ->queue(new AdminNotificationMail('new_order', [
                    'order_id' => $order->id,
                    'customer_name' => $order->user->name,
                    'customer_email' => $order->user->email,
                    'customer_phone' => $order->user->phone,
                    'package_name' => $order->package->name,
                    'total_price' => $order->total_price,
                    'event_date' => $order->event_date->format('d F Y'),
                ]));

            // Send SMS/WhatsApp notification to customer
            if ($order->user->phone && ($order->user->prefer_whatsapp || $order->user->prefer_sms)) {
                try {
                    app(NotificationService::class)->notifyOrderConfirmation($order);
                } catch (\Exception $e) {
                    \Log::warning('SMS notification failed: '.$e->getMessage());
                }
            }
        });

        static::updating(function (Order $order) {
            if ($order->isDirty('status')) {
                $previousStatus = $order->getOriginal('status');
                if ($previousStatus !== $order->status) {
                    // Send status update email to customer
                    Mail::to($order->user->email)
                        ->queue(new OrderStatusMail($order, $previousStatus));

                    // Send SMS/WhatsApp status update
                    if ($order->user->phone && ($order->user->prefer_whatsapp || $order->user->prefer_sms)) {
                        try {
                            if ($order->status === 'confirmed') {
                                // Send payment reminder when order is confirmed
                                app(NotificationService::class)->notifyPaymentReminder($order);
                            }
                        } catch (\Exception $e) {
                            \Log::warning('SMS notification failed: '.$e->getMessage());
                        }
                    }
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'dp_percentage' => 'float',
            'total_paid' => 'float',
            'remaining_amount' => 'float',
            'extra_guest_charge' => 'float',
            'custom_schedules' => 'array',
        ];
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function calendarEvent()
    {
        return $this->hasOne(CalendarEvent::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function videoTestimonials()
    {
        return $this->hasMany(VideoTestimonial::class);
    }

    public function orderVendors()
    {
        return $this->hasMany(OrderVendor::class);
    }

    public function selectedVendors()
    {
        return $this->hasMany(OrderVendor::class);
    }

    // Accessors
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // Payment Scheme Helpers
    public function getPaymentProgress(): float
    {
        if ($this->total_price <= 0) return 0;
        return min(100, round(($this->total_paid / $this->total_price) * 100, 2));
    }

    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'fully_paid' || $this->total_paid >= $this->total_price;
    }

    public function getDpAmount(): float
    {
        if ($this->payment_scheme === 'full_payment') {
            return 0;
        }
        
        $percentage = $this->dp_percentage ?? 30; // default 30% if null
        return round(($percentage / 100) * $this->total_price, 2);
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->total_price - $this->total_paid);
    }

    public function getSchemeLabelAttribute(): string
    {
        return match($this->payment_scheme) {
            'full_payment' => 'Bayar Lunas',
            'dp_20' => 'DP 20% + Pelunasan',
            'dp_30' => 'DP 30% + Pelunasan',
            'dp_40' => 'DP 40% + Pelunasan',
            'dp_50' => 'DP 50% + Pelunasan',
            'installment_3x' => 'Cicilan 3x',
            'installment_5x' => 'Cicilan 5x',
            default => 'Bayar Lunas',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'Belum Dibayar',
            'dp_paid' => 'DP Terbayar',
            'partially_paid' => 'Dibayar Sebagian',
            'fully_paid' => 'Lunas',
            default => 'Belum Dibayar',
        };
    }
}
