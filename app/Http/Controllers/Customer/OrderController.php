<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\Bank;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $midtransService;
    protected $paymentService;

    public function __construct(MidtransService $midtransService, PaymentService $paymentService)
    {
        $this->midtransService = $midtransService;
        $this->paymentService = $paymentService;
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

        // Create order with compatible order number for Midtrans VA
        // Format: WO-<timestamp>-<random> but keep it shorter for VA compatibility
        $orderNumber = 'WO-' . substr(time(), -8) . rand(10, 99);
        
        $order = Order::create([
            'user_id' => auth()->id(),
            'package_id' => $validated['package_id'],
            'order_number' => $orderNumber,
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
     * Show payment page with bank selection
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

        // Get active banks
        $banks = Bank::where('active', true)->get();

        return view('customer.orders.payment-manual', [
            'order' => $order,
            'banks' => $banks,
        ]);
    }

    /**
     * Process manual payment selection
     */
    public function selectBank(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
        ]);

        $bank = Bank::findOrFail($validated['bank_id']);

        // Create payment record
        $payment = $this->paymentService->createManualPayment($order, $bank);

        return redirect()->route('customer.orders.paymentConfirm', ['order' => $order->id])
            ->with('success', 'Bank dipilih. Silakan transfer sesuai instruksi.');
    }

    /**
     * Show payment confirmation page
     */
    public function paymentConfirm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Reload order with payment, bank, and package relationship
        $order = $order->load('payment.bank', 'package');
        $payment = $order->payment;
        
        if (!$payment || $payment->payment_method !== 'bank_transfer') {
            return redirect()->route('customer.orders.payment', $order->id)
                ->with('error', 'Invalid payment record');
        }

        $bank = $payment->bank;
        $whatsappLink = $this->paymentService->generateWhatsAppLink($order, $bank);

        return view('customer.orders.payment-confirm', [
            'order' => $order,
            'payment' => $payment,
            'bank' => $bank,
            'whatsappLink' => $whatsappLink,
        ]);
    }

    /**
     * Handle Midtrans notification (keep for backwards compatibility)
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
