<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Snap;

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
            'order_id' => $order->order_number,
            'gross_amount' => (int)$order->total_price,
        ];

        $customer_details = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->phone ?? '628123456789',  // Default if null
        ];

        $item_details = [
            [
                'id' => 'pkg-' . $order->package->id,
                'price' => (int)$order->package->price,
                'quantity' => 1,
                'name' => $order->package->name,
            ],
        ];

        if ($order->total_price > $order->package->price) {
            $additional_cost = $order->total_price - $order->package->price;
            $item_details[] = [
                'id' => 'additional_fee',
                'price' => (int)$additional_cost,
                'quantity' => 1,
                'name' => 'Additional Charges',
            ];
        }

        // Bank transfer configuration with VA
        $bank_transfer = [
            'bank' => 'bni',
            'free_text' => [
                'inquiry' => [
                    'en' => 'Thank you for your wedding order',
                ],
            ],
        ];

        $payload = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            // Enable bank transfer with VA first for better compatibility
            'bank_transfer' => $bank_transfer,
            'enabled_payments' => [
                'bank_transfer',
                'bank_bni',
                'bank_bca',
                'bank_mandiri',
                'bank_cimb',
                'bank_bri',
                'credit_card',
                'echannel',
                'permata',
                'akulaku',
                'gopay',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Error creating Snap token: ' . $e->getMessage());
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

        // Find payment by order number (order_id in Midtrans = order_number)
        $order = Order::where('order_number', $order_id)->first();

        if (!$order) {
            throw new \Exception('Order not found for order_id: ' . $order_id);
        }

        $payment = $order->payment;
        if (!$payment) {
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->payment_id = $transaction_id;
        }

        $payment->payment_method = $payment_type;
        $payment->midtrans_response = (array)$notification;

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
            throw new \Exception('Error getting transaction status: ' . $e->getMessage());
        }
    }
}
