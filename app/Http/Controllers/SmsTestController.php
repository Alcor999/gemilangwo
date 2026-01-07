<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Services\NotificationService;
use App\Services\SmsService;

class SmsTestController extends Controller
{
    /**
     * Send test order confirmation SMS
     */
    public function testOrderConfirmation($orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyOrderConfirmation($order);

        return response()->json([
            'message' => $sent ? 'Order confirmation SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $order->user->phone,
            'customer' => $order->user->name,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test payment reminder SMS
     */
    public function testPaymentReminder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyPaymentReminder($order);

        return response()->json([
            'message' => $sent ? 'Payment reminder SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $order->user->phone,
            'order_id' => $order->id,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test payment confirmation SMS
     */
    public function testPaymentConfirmation($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyPaymentConfirmation($payment);

        return response()->json([
            'message' => $sent ? 'Payment confirmation SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $payment->order->user->phone,
            'amount' => $payment->amount,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test event reminder (3 days)
     */
    public function testEventReminder3Days($orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyEventReminder3Days($order);

        return response()->json([
            'message' => $sent ? 'Event reminder (3 days) SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $order->user->phone,
            'event_date' => $order->event_date,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test event reminder (1 day)
     */
    public function testEventReminder1Day($orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyEventReminder1Day($order);

        return response()->json([
            'message' => $sent ? 'Event reminder (1 day) SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $order->user->phone,
            'event_date' => $order->event_date,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test event completed notification
     */
    public function testEventCompleted($orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyEventCompleted($order);

        return response()->json([
            'message' => $sent ? 'Event completed SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $order->user->phone,
            'order_id' => $order->id,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Send test review thank you SMS
     */
    public function testReviewThankYou($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $service = app(NotificationService::class);
        
        $sent = $service->notifyReviewThankYou($review);

        return response()->json([
            'message' => $sent ? 'Review thank you SMS/WhatsApp sent' : 'Failed to send SMS/WhatsApp',
            'phone' => $review->user->phone,
            'review_id' => $review->id,
            'status' => $sent ? 'success' : 'failed',
        ]);
    }

    /**
     * Format phone number test
     */
    public function testFormatPhone($phone)
    {
        $formatted = SmsService::formatPhoneNumber($phone);

        return response()->json([
            'original' => $phone,
            'formatted' => $formatted,
        ]);
    }

    /**
     * Get SMS logs
     */
    public function getSmsLogs()
    {
        $logs = \App\Models\SmsLog::latest()->limit(20)->get();

        return response()->json([
            'total' => \App\Models\SmsLog::count(),
            'logs' => $logs,
        ]);
    }
}
