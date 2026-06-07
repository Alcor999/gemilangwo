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
        'bank_id',
        'payment_method',
        'amount',
        'status',
        'payment_proof_path',
        'verification_status',
        'verified_by',
        'verification_notes',
        'midtrans_response',
        'payment_type',
        'installment_number',
        'due_date',
        'payment_note',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'midtrans_response' => 'array',
            'paid_at' => 'datetime',
            'due_date' => 'date',
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
                        \Log::warning('SMS notification failed: '.$e->getMessage());
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

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
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

    // Payment Type Helpers
    public function isDp(): bool
    {
        return $this->payment_type === 'dp';
    }

    public function isInstallment(): bool
    {
        return $this->payment_type === 'installment';
    }

    public function isRemaining(): bool
    {
        return $this->payment_type === 'remaining';
    }

    public function isFull(): bool
    {
        return $this->payment_type === 'full';
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('payment_type', $type);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString());
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->payment_type) {
            'full' => 'Lunas Penuh',
            'dp' => 'Uang Muka (DP)',
            'installment' => 'Cicilan',
            'remaining' => 'Pelunasan Sisa',
            default => 'Lunas Penuh',
        };
    }
}
