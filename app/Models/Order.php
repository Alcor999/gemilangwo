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
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
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
                    \Log::warning('SMS notification failed: ' . $e->getMessage());
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
                            \Log::warning('SMS notification failed: ' . $e->getMessage());
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

    public function payment()
    {
        return $this->hasOne(Payment::class);
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
}
