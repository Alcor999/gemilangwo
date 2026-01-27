<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentInstructionMail;
use App\Mail\PaymentVerifiedMail;
use App\Mail\PaymentRejectedMail;

class PaymentService
{
    /**
     * Create manual payment record
     */
    public function createManualPayment(Order $order, Bank $bank)
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_id' => 'MANUAL-' . $order->order_number . '-' . Str::random(8),
            'bank_id' => $bank->id,
            'payment_method' => 'bank_transfer',
            'amount' => $order->total_price,
            'status' => 'pending',
            'verification_status' => 'pending',
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
    public function generateWhatsAppLink($order, $bank, $adminPhone = null)
    {
        // Default admin phone - ganti sesuai nomor WhatsApp Anda
        $adminPhone = $adminPhone ?? env('ADMIN_WHATSAPP_NUMBER', '6281234567890');
        
        $message = "Halo, saya ingin konfirmasi pembayaran untuk order {$order->order_number}.\n";
        $message .= "Paket: {$order->package->name}\n";
        $message .= "Jumlah: Rp " . number_format($order->total_price, 0, ',', '.') . "\n";
        $message .= "Bank: {$bank->name} ({$bank->account_number})";

        // Encode message untuk WhatsApp URL
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$adminPhone}?text={$encodedMessage}";
    }

    /**
     * Verify payment by admin
     */
    public function verifyPayment(Payment $payment, User $admin, $notes = null)
    {
        $payment->update([
            'verification_status' => 'verified',
            'verified_by' => $admin->id,
            'verification_notes' => $notes,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        // Update order status
        $payment->order->update(['status' => 'confirmed']);

        // Send confirmation email
        Mail::to($payment->order->user->email)->queue(
            new PaymentVerifiedMail($payment->order)
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
}
