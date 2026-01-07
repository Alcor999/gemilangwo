<?php

namespace App\Models;

use App\Mail\AdminNotificationMail;
use App\Mail\PaymentReceivedMail;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_id',
        'payment_method',
        'amount',
        'status',
        'midtrans_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::updating(function (Payment $payment) {
            if ($payment->isDirty('status') && $payment->status === 'success' && $payment->getOriginal('status') !== 'success') {
                // Send payment received email to customer
                Mail::to($payment->order->user->email)
                    ->queue(new PaymentReceivedMail($payment));

                // Send admin notification
                Mail::to(config('app.admin_email', 'admin@gemilangwo.test'))
                    ->queue(new AdminNotificationMail('payment_received', [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'customer_name' => $payment->order->user->name,
                    ]));

                // Send SMS/WhatsApp payment confirmation
                $customer = $payment->order->user;
                if ($customer->phone && ($customer->prefer_whatsapp || $customer->prefer_sms)) {
                    try {
                        app(NotificationService::class)->notifyPaymentConfirmation($payment);
                    } catch (\Exception $e) {
                        \Log::warning('SMS notification failed: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Methods
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
