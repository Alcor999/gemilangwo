<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
        $order->load(['user', 'package', 'payment', 'reviews']);
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
}
