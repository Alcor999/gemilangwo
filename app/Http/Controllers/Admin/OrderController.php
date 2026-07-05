<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of all orders
     */
    public function index()
    {
        $orders = Order::with(['user', 'package', 'payments'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', ['orders' => $orders]);
    }

    /**
     * Show the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'package', 'payments.bank', 'payments.verifiedBy', 'reviews', 'orderVendors']);

        return view('admin.orders.show', ['order' => $order]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Automatically create calendar event when order is confirmed
        if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed' && !$order->calendarEvent) {
            \App\Models\CalendarEvent::createFromOrder($order);
        }

        return redirect()->back()
            ->with('success', 'Order status updated successfully');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'pending' || $order->status === 'confirmed') {
            $order->update(['status' => 'cancelled']);

            // Cancel any pending payments
            $order->payments()->where('status', 'pending')->update([
                'status' => 'cancelled',
                'verification_status' => 'rejected',
                'verification_notes' => 'Dibatalkan oleh admin/sistem'
            ]);

            return redirect()->back()
                ->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()
            ->with('error', 'Tidak dapat membatalkan pesanan yang sudah selesai atau dibatalkan.');
    }

    /**
     * Approve payment
     */
    public function approvePayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $this->paymentService->verifyPayment(
            $payment,
            auth()->user(),
            $validated['notes']
        );

        return redirect()->back()
            ->with('success', 'Payment approved successfully');
    }

    /**
     * Reject payment
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->paymentService->rejectPayment(
            $payment,
            auth()->user(),
            $validated['reason']
        );

        return redirect()->back()
            ->with('success', 'Payment rejected successfully');
    }
}
