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
        $orders = Order::with(['user', 'package', 'payment'])
            ->latest()
            ->paginate(15);
        
        return view('admin.orders.index', ['orders' => $orders]);
    }

    /**
     * Show the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'package', 'payment', 'reviews', 'orderVendors']);
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

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Order status updated successfully');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return redirect()->back()
                ->with('success', 'Order cancelled successfully');
        }

        return redirect()->back()
            ->with('error', 'Cannot cancel an order that is not pending');
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
