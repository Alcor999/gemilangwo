<?php

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Payment::whereNull('payment_type')->update(['payment_type' => 'full']);

        Order::with('payments')->chunkById(100, function ($orders) {
            foreach ($orders as $order) {
                $totalPaid = (float) $order->payments()
                    ->where('status', 'success')
                    ->sum('amount');

                $totalPrice = (float) $order->total_price;
                $remaining = max(0, $totalPrice - $totalPaid);

                $paymentStatus = 'unpaid';
                if ($remaining <= 0 && $totalPaid > 0) {
                    $paymentStatus = 'fully_paid';
                } elseif ($totalPaid > 0) {
                    $hasDp = $order->payments()
                        ->where('status', 'success')
                        ->where('payment_type', 'dp')
                        ->exists();
                    $paymentStatus = $hasDp ? 'dp_paid' : 'partially_paid';
                }

                $order->update([
                    'payment_scheme' => $order->payment_scheme ?? 'full_payment',
                    'total_paid' => $totalPaid,
                    'remaining_amount' => $remaining > 0 ? $remaining : 0,
                    'payment_status' => $paymentStatus,
                ]);
            }
        });
    }

    public function down(): void
    {
        // Data migration — tidak di-rollback
    }
};
