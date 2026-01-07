<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusMail;
use App\Mail\PaymentReceivedMail;
use App\Mail\ReviewSubmissionMail;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    /**
     * Send test order confirmation email
     */
    public function testOrderConfirmation($orderId)
    {
        $order = Order::findOrFail($orderId);
        Mail::to($order->user->email)->send(new OrderConfirmationMail($order));

        return response()->json([
            'message' => 'Order confirmation email sent to ' . $order->user->email,
            'order_id' => $orderId,
        ]);
    }

    /**
     * Send test payment received email
     */
    public function testPaymentReceived($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        Mail::to($payment->order->user->email)->send(new PaymentReceivedMail($payment));

        return response()->json([
            'message' => 'Payment received email sent to ' . $payment->order->user->email,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Send test order status email
     */
    public function testOrderStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        $previousStatus = 'pending';
        Mail::to($order->user->email)->send(new OrderStatusMail($order, $previousStatus));

        return response()->json([
            'message' => 'Order status email sent to ' . $order->user->email,
            'order_id' => $orderId,
        ]);
    }

    /**
     * Send test review submission email
     */
    public function testReviewSubmission($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        Mail::to($review->user->email)->send(new ReviewSubmissionMail($review));

        return response()->json([
            'message' => 'Review submission email sent to ' . $review->user->email,
            'review_id' => $reviewId,
        ]);
    }

    /**
     * Send test admin notification email
     */
    public function testAdminNotification($type)
    {
        $adminEmail = config('app.admin_email', 'admin@gemilangwo.test');

        $data = match($type) {
            'new_order' => [
                'order_id' => 1,
                'customer_name' => 'John Doe',
                'customer_email' => 'customer@example.com',
                'customer_phone' => '08123456789',
                'package_name' => 'Premium Wedding Package',
                'total_price' => 15000000,
                'event_date' => '2026-02-14',
            ],
            'new_review' => [
                'review_id' => 1,
                'customer_name' => 'Jane Smith',
                'package_name' => 'Premium Wedding Package',
                'rating' => 5,
                'title' => 'Amazing Service!',
            ],
            'payment_received' => [
                'payment_id' => 1,
                'order_id' => 1,
                'amount' => 15000000,
                'payment_method' => 'bank_transfer',
                'customer_name' => 'John Doe',
            ],
            default => [],
        };

        Mail::to($adminEmail)->send(new AdminNotificationMail($type, $data));

        return response()->json([
            'message' => "Admin notification ($type) email sent to $adminEmail",
            'type' => $type,
        ]);
    }
}
