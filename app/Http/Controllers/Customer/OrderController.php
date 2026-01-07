<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display customer's orders
     */
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['package', 'payment'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', ['orders' => $orders]);
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load(['package', 'payment', 'reviews']);
        return view('customer.orders.show', ['order' => $order]);
    }

    /**
     * Show create order form
     */
    public function create()
    {
        $packages = Package::where('status', 'active')->get();
        return view('customer.orders.create', ['packages' => $packages]);
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'event_date' => 'required|date|after:today',
            'event_location' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        // Validate guest count doesn't exceed package maximum
        if ($package->max_guests && $validated['guest_count'] > $package->max_guests) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Guest count exceeds package maximum of ' . $package->max_guests);
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'package_id' => $validated['package_id'],
            'order_number' => 'WO-' . time() . '-' . rand(1000, 9999),
            'event_date' => $validated['event_date'],
            'event_location' => $validated['event_location'],
            'guest_count' => $validated['guest_count'],
            'special_request' => $validated['special_request'] ?? null,
            'total_price' => $package->price,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Order created successfully. Please proceed to payment.');
    }

    /**
     * Show payment page
     */
    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$order->isPending()) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'This order cannot be paid');
        }

        try {
            $snapToken = $this->midtransService->createSnapToken($order);
            return view('customer.orders.payment', [
                'order' => $order,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans notification
     */
    public function notification(Request $request)
    {
        try {
            $notification = $request->all();
            $this->midtransService->handleNotification((object)$notification);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Finish payment callback
     */
    public function paymentFinish(Request $request)
    {
        $order_id = $request->query('order_id');
        $order = Order::where('order_number', $order_id)->first();

        if (!$order) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order not found');
        }

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->payment && $order->payment->isSuccess()) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Payment successful! Your order has been confirmed.');
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('info', 'Payment status is being processed. Please check back soon.');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->isPending()) {
            $order->update(['status' => 'cancelled']);
            return redirect()->route('customer.orders.index')
                ->with('success', 'Order cancelled successfully');
        }

        return redirect()->back()
            ->with('error', 'Cannot cancel this order');
    }
}
