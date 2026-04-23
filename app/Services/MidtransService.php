<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$isProduction = config('midtrans.is_production');
    }

    /**
     * Create Snap token for payment
     */
    public function createSnapToken(Order $order)
    {
        $transaction_details = [
            'order_id' => $order->order_number . '-' . time(),
            'gross_amount' => (int) $order->total_price,
        ];

        $customer_details = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->phone ?? '08123456789',
        ];

        // Base Price (After Package Discount)
        $basePrice = (int) $order->package->getDiscountedPrice();
        
        $item_details = [
            [
                'id' => 'pkg-'.$order->package->id,
                'price' => $basePrice,
                'quantity' => 1,
                'name' => $order->package->name . ' (Base Package)',
            ],
        ];

        // Adjustment for Vendor Upgrades/Downgrades
        $adjustment = (int) $order->total_price - $basePrice;

        if ($adjustment !== 0) {
            $item_details[] = [
                'id' => 'adjustment',
                'price' => $adjustment,
                'quantity' => 1,
                'name' => $adjustment > 0 ? 'Vendor Upgrades' : 'Package Discount/Adjustments',
            ];
        }

        $payload = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);

            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Error creating Snap token: '.$e->getMessage());
        }
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification($notification)
    {
        $transaction_status = $notification->transaction_status ?? null;
        $payment_type = $notification->payment_type ?? null;
        $order_id = $notification->order_id ?? null;
        $transaction_id = $notification->transaction_id ?? null;

        // Extract order_number (WO-XXXXXXXX) from Midtrans order_id (WO-XXXXXXXX-TIMESTAMP)
        $order_number = $order_id;
        if (str_contains($order_id, '-')) {
            $parts = explode('-', $order_id);
            // Case: WO - XXXXXXXX - TIMESTAMP
            if (count($parts) >= 3) {
                $order_number = $parts[0] . '-' . $parts[1];
            }
        }

        $order = Order::where('order_number', $order_number)->first();

        if (! $order) {
            throw new \Exception('Order not found for order_id: '.$order_id . ' (Parsed: ' . $order_number . ')');
        }

        $payment = $order->payment;
        if (! $payment) {
            $payment = new Payment;
            $payment->order_id = $order->id;
            $payment->payment_id = $transaction_id;
        }

        $payment->payment_method = $payment_type;
        $payment->midtrans_response = (array) $notification;

        // Handle bank transfer with VA
        if ($payment_type === 'bank_transfer' || $payment_type === 'echannel') {
            // Store VA details if available
            if (isset($notification->va_numbers)) {
                foreach ($notification->va_numbers as $va) {
                    $payment->va_number = $va->va_number ?? null;
                    $payment->bank = $va->bank ?? null;
                    break;  // Store first VA
                }
            }
        }

        if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
            $payment->status = 'success';
            $payment->paid_at = now();
            $order->status = 'confirmed';
        } elseif ($transaction_status == 'pending') {
            $payment->status = 'pending';
        } elseif ($transaction_status == 'deny' || $transaction_status == 'cancel' || $transaction_status == 'expire') {
            $payment->status = 'failed';
            $order->status = 'cancelled';
        }

        $payment->save();
        $order->save();

        return $payment;
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($transaction_id)
    {
        try {
            $status = Transaction::status($transaction_id);

            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Error getting transaction status: '.$e->getMessage());
        }
    }
}
