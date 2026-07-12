<?php

namespace App\Services;

use App\Mail\PaymentInstructionMail;
use App\Mail\PaymentRejectedMail;
use App\Mail\PaymentVerifiedMail;
use App\Models\Bank;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Create manual payment record
     */
    public function createManualPayment(Order $order, Bank $bank, $amount = null, $paymentType = 'full', $installmentNumber = null, $dueDate = null)
    {
        $amount = $amount ?? $order->total_price;

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_id' => 'MANUAL-'.$order->order_number.'-'.Str::random(8),
            'bank_id' => $bank->id,
            'payment_method' => 'bank_transfer',
            'amount' => $amount,
            'status' => 'pending',
            'verification_status' => 'pending',
            'payment_type' => $paymentType,
            'installment_number' => $installmentNumber,
            'due_date' => $dueDate,
        ]);

        // Send payment instruction email
        Mail::to($order->user->email)->queue(
            new PaymentInstructionMail($order, $payment, $bank)
        );

        return $payment;
    }

    /**
     * Generate WhatsApp message link
     */
    public function generateWhatsAppLink($order, $bank = null, $adminPhone = null, $amount = null, $paymentType = 'full')
    {
        try {
            if (! $bank) {
                return null;
            }

            // Default admin phone
            $adminPhone = $adminPhone ?? env('ADMIN_WHATSAPP_NUMBER', '6281234567890');

            // Ensure order has package loaded
            if (! $order->package) {
                $order->load('package');
            }

            $amount = $amount ?? $order->total_price;
            
            $typeLabel = match($paymentType) {
                'dp' => 'DP (Uang Muka)',
                'installment' => 'Cicilan',
                'remaining' => 'Pelunasan Sisa',
                default => 'Bayar Lunas Penuh',
            };

            $message = "Halo, saya ingin konfirmasi pembayaran {$typeLabel} untuk order {$order->order_number}.\n";
            $message .= "Paket: {$order->package->name}\n";
            $message .= 'Jumlah: Rp '.number_format($amount, 0, ',', '.')."\n";
            $message .= "Bank: {$bank->name} ({$bank->account_number})";

            // Encode message for WhatsApp URL
            $encodedMessage = urlencode($message);

            return "https://wa.me/{$adminPhone}?text={$encodedMessage}";
        } catch (\Exception $e) {
            \Log::error('WhatsApp link generation error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Verify payment by admin
     */
    public function verifyPayment(Payment $payment, User $admin, $notes = null)
    {
        $order = $payment->order;

        $payment->update([
            'verification_status' => 'verified',
            'verified_by' => $admin->id,
            'verification_notes' => $notes,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $this->recalculateOrderPaymentStatus($order);

        $oldStatus = $order->status;
        if ($order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
        }

        $order->refresh();

        $notificationService = app(NotificationService::class);

        if ($payment->isDp()) {
            $notificationService->notifyDpReceived($payment);
        } elseif ($order->isFullyPaid()) {
            $notificationService->notifyPaymentComplete($order);
        }

        // Send confirmation email
        Mail::to($order->user->email)->queue(
            new PaymentVerifiedMail($order)
        );

        return $payment;
    }

    /**
     * Reject payment by admin
     */
    public function rejectPayment(Payment $payment, User $admin, $notes = null)
    {
        $payment->update([
            'verification_status' => 'rejected',
            'verified_by' => $admin->id,
            'verification_notes' => $notes,
            'status' => 'failed',
        ]);

        // Send rejection email
        Mail::to($payment->order->user->email)->queue(
            new PaymentRejectedMail($payment->order, $notes)
        );

        return $payment;
    }

    /**
     * Get pending payments for admin
     */
    public function getPendingPayments()
    {
        return Payment::where('verification_status', 'pending')
            ->with('order', 'order.user', 'order.package', 'bank')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get verified payments
     */
    public function getVerifiedPayments()
    {
        return Payment::where('verification_status', 'verified')
            ->with('order', 'order.user', 'verifiedBy', 'bank')
            ->orderBy('paid_at', 'desc')
            ->get();
    }

    /**
     * Buat jadwal pembayaran berdasarkan skema dan custom schedules
     */
    public function createPaymentSchedule(Order $order): array
    {
        $breakdown = app(PaymentSchemeService::class)->calculateBreakdown(
            $order->payment_scheme,
            (float) $order->total_price,
            $order->event_date
        );

        if ($order->custom_schedules && is_array($order->custom_schedules)) {
            foreach ($breakdown as $index => &$item) {
                if (!empty($order->custom_schedules[$index])) {
                    $item['due_date'] = \Carbon\Carbon::parse($order->custom_schedules[$index]);
                }
            }
        }

        return $breakdown;
    }

    /**
     * Get details of the next payment required for an order
     */
    public function getNextPaymentDetails(Order $order)
    {
        $totalPrice = (float) $order->total_price;
        $remaining = (float) $order->remaining_amount;

        if ($order->payment_status === 'fully_paid' || $remaining <= 0) {
            return null;
        }

        $schedule = $this->createPaymentSchedule($order);
        
        // Count how many successful payments have been made
        $successCount = $order->payments()
            ->where('status', 'success')
            ->count();

        // The next payment is the one at index $successCount
        if (isset($schedule[$successCount])) {
            $nextItem = $schedule[$successCount];
            
            // Adjust amount if it's the last payment (to avoid rounding issues)
            $amount = $nextItem['amount'];
            if ($successCount === count($schedule) - 1) {
                $amount = $remaining;
            }

            return [
                'amount' => $amount,
                'payment_type' => $nextItem['payment_type'],
                'installment_number' => $nextItem['installment_number'],
                'label' => $nextItem['label'],
                'due_date' => $nextItem['due_date'],
            ];
        }

        return null;
    }

    /**
     * Ringkasan pembayaran untuk tampilan order
     */
    public function getPaymentSummary(Order $order): array
    {
        $summary = app(PaymentSchemeService::class)->getPaymentSummary($order);
        
        // Ensure the summary uses the order's custom schedules
        if ($order->custom_schedules && is_array($order->custom_schedules)) {
            foreach ($summary['schedule'] as $index => &$item) {
                if (!empty($order->custom_schedules[$index])) {
                    $item['due_date'] = \Carbon\Carbon::parse($order->custom_schedules[$index]);
                }
            }
        }
        
        return $summary;
    }

    /**
     * Recalculate order payment totals from successful payments
     */
    public function recalculateOrderPaymentStatus(Order $order): Order
    {
        $totalPaid = (float) $order->payments()->where('status', 'success')->sum('amount');
        $remainingAmount = max(0, (float) $order->total_price - $totalPaid);

        $paymentStatus = 'unpaid';
        if ($remainingAmount <= 0 && $totalPaid > 0) {
            $paymentStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $hasDp = $order->payments()
                ->where('status', 'success')
                ->where('payment_type', 'dp')
                ->exists();
            $paymentStatus = $hasDp ? 'dp_paid' : 'partially_paid';
        }

        $order->update([
            'total_paid' => $totalPaid,
            'remaining_amount' => $remainingAmount,
            'payment_status' => $paymentStatus,
        ]);

        return $order->fresh();
    }

    /**
     * Pembayaran yang melewati deadline
     */
    public function getOverduePayments()
    {
        return Payment::overdue()
            ->with('order', 'order.user', 'order.package', 'bank')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Cek jatuh tempo & kirim reminder
     */
    public function checkDuePayments(int $daysBefore = 3): array
    {
        $remindersSent = 0;
        $notificationService = app(NotificationService::class);

        $upcoming = Payment::where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays($daysBefore)->toDateString()])
            ->with('order.user', 'order.package')
            ->get();

        foreach ($upcoming as $payment) {
            if ($notificationService->notifyInstallmentDue($payment->order, $payment)) {
                $remindersSent++;
            }
        }

        $overdue = $this->getOverduePayments();

        foreach ($overdue as $payment) {
            if ($notificationService->notifyInstallmentDue($payment->order, $payment, true)) {
                $remindersSent++;
            }
        }

        return [
            'reminders_sent' => $remindersSent,
            'overdue_count' => $overdue->count(),
        ];
    }

    /**
     * Kirim reminder manual untuk satu payment
     */
    public function sendPaymentReminder(Payment $payment): bool
    {
        $isOverdue = $payment->due_date && $payment->due_date->isPast();

        return app(NotificationService::class)->notifyInstallmentDue(
            $payment->order,
            $payment,
            $isOverdue
        );
    }
}
