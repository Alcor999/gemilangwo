<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Order;
use App\Models\Package;
use App\Services\MidtransService;
use App\Services\PaymentSchemeService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected $midtransService;

    protected $paymentService;

    protected $paymentSchemeService;

    public function __construct(
        MidtransService $midtransService,
        PaymentService $paymentService,
        PaymentSchemeService $paymentSchemeService
    ) {
        $this->midtransService = $midtransService;
        $this->paymentService = $paymentService;
        $this->paymentSchemeService = $paymentSchemeService;
    }

    /**
     * Display customer's orders
     */
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['package', 'payments'])
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

        $order->load(['package', 'payments.bank', 'reviews', 'orderVendors']);

        $paymentSummary = $this->paymentService->getPaymentSummary($order);
        $nextPayment = $this->paymentService->getNextPaymentDetails($order);

        return view('customer.orders.show', [
            'order' => $order,
            'paymentSummary' => $paymentSummary,
            'nextPayment' => $nextPayment,
        ]);
    }

    /**
     * Show create order form
     */
    public function create()
    {
        $packages = Package::where('status', 'active')
            ->with(['requiredVendorCategories.vendors'])
            ->get();

        return view('customer.orders.create', ['packages' => $packages]);
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $minDate = now()->addDays(4)->format('Y-m-d');
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'event_date' => 'required|date|after_or_equal:' . $minDate,
            'event_location' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'vendors' => 'nullable|array',
            'vendors.*' => 'exists:vendors,id',
            'payment_scheme' => 'required|in:full_payment,dp_20,dp_30,dp_40,dp_50,installment_3x,installment_5x',
        ], [
            'event_date.after_or_equal' => 'Tanggal acara minimal 4 hari dari hari ini karena pembayaran harus lunas sebelum H-4.'
        ]);

        $package = Package::with('requiredVendorCategories.vendors')->findOrFail($validated['package_id']);

        $eventDate = Carbon::parse($validated['event_date']);
        if (! $this->paymentSchemeService->isEligible($validated['payment_scheme'], $eventDate)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Skema pembayaran yang dipilih tidak tersedia untuk tanggal acara ini. Pilih tanggal lebih jauh atau gunakan Bayar Lunas.');
        }

        // Calculate extra guests charge
        $extraGuestCharge = 0;
        $threshold = config('gemilang.guests.threshold', 1000);
        $chargePerUnit = config('gemilang.guests.charge_per_unit', 1000000);
        $unitSize = config('gemilang.guests.unit_size', 100);

        if ($validated['guest_count'] > $threshold) {
            $extraGuests = $validated['guest_count'] - $threshold;
            $units = ceil($extraGuests / $unitSize);
            $extraGuestCharge = $units * $chargePerUnit;
        }

        // Calculate total: package base + vendor prices + extra guest charge
        $totalPrice = $package->getDiscountedPrice() + $extraGuestCharge;
        $selectedVendors = [];

        foreach ($package->requiredVendorCategories as $category) {
            $vendorId = $validated['vendors'][$category->id] ?? null;
            if (! $vendorId) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Silakan pilih vendor untuk kategori: '.$category->name);
            }
            $vendor = $category->vendors->firstWhere('id', $vendorId);
            if (! $vendor) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Vendor tidak valid untuk kategori: '.$category->name);
            }
            
            // Logika baru untuk kompensasi Vendor Default
            $defaultVendorId = $category->pivot->default_vendor_id;
            if ($defaultVendorId) {
                $defaultVendor = \App\Models\Vendor::find($defaultVendorId);
                if ($defaultVendor && $vendor->id != $defaultVendor->id) {
                    $priceDiff = $vendor->price - $defaultVendor->price;
                    $totalPrice += (float) $priceDiff;
                }
            } else {
                // Jika kategori tersebut tidak punya vendor default, vendor apa pun yang dipilih akan di-charge sepenuhnya
                $totalPrice += (float) $vendor->price;
            }

            $selectedVendors[] = [
                'vendor' => $vendor,
                'category' => $category,
            ];
        }

        $orderNumber = 'WO-'.substr(time(), -8).rand(10, 99);

        $paymentScheme = $validated['payment_scheme'];
        $dpPercentage = null;
        if ($paymentScheme === 'dp_20') {
            $dpPercentage = 20.00;
        } elseif ($paymentScheme === 'dp_30') {
            $dpPercentage = 30.00;
        } elseif ($paymentScheme === 'dp_40') {
            $dpPercentage = 40.00;
        } elseif ($paymentScheme === 'dp_50') {
            $dpPercentage = 50.00;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'package_id' => $validated['package_id'],
            'order_number' => $orderNumber,
            'event_date' => $validated['event_date'],
            'event_location' => $validated['event_location'],
            'guest_count' => $validated['guest_count'],
            'special_request' => $validated['special_request'] ?? null,
            'total_price' => $totalPrice,
            'payment_scheme' => $paymentScheme,
            'dp_percentage' => $dpPercentage,
            'total_paid' => 0.00,
            'remaining_amount' => $totalPrice,
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        foreach ($selectedVendors as $sv) {
            $order->orderVendors()->create([
                'vendor_id' => $sv['vendor']->id,
                'vendor_category_id' => $sv['category']->id,
                'vendor_name' => $sv['vendor']->name,
                'vendor_category_name' => $sv['category']->name,
                'price' => $sv['vendor']->price,
            ]);
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    /**
     * Show payment page with bank selection
     */
    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->isCancelled() || $order->isCompleted()) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Order ini tidak dapat dibayar karena sudah dibatalkan atau selesai.');
        }

        if ($order->payment_status === 'fully_paid') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order ini sudah lunas.');
        }

        try {
            $nextPayment = $this->paymentService->getNextPaymentDetails($order);
            if (! $nextPayment) {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('error', 'Tidak ada jadwal pembayaran aktif untuk pesanan ini.');
            }

            $banks = Bank::where('active', true)->get();

            return view('customer.orders.payment', [
                'order' => $order,
                'next_payment' => $nextPayment,
                'banks' => $banks,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
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

        // Get the next payment details
        $nextPayment = $this->paymentService->getNextPaymentDetails($order);
        if (! $nextPayment) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Tidak ada pembayaran yang harus dilakukan saat ini.');
        }

        // Check if there is already a pending payment of the same type and installment number
        $existingPayment = $order->payments()
            ->where('status', 'pending')
            ->where('payment_type', $nextPayment['payment_type'])
            ->where(function($q) use ($nextPayment) {
                if ($nextPayment['installment_number']) {
                    $q->where('installment_number', $nextPayment['installment_number']);
                } else {
                    $q->whereNull('installment_number');
                }
            })
            ->first();

        if ($existingPayment) {
            $existingPayment->update([
                'bank_id' => $bank->id,
                'amount' => $nextPayment['amount'],
            ]);
            $payment = $existingPayment;
        } else {
            $payment = $this->paymentService->createManualPayment(
                $order,
                $bank,
                $nextPayment['amount'],
                $nextPayment['payment_type'],
                $nextPayment['installment_number'],
                $nextPayment['due_date']
            );
        }

        return redirect()->route('customer.orders.paymentConfirm', ['order' => $order->id])
            ->with('success', 'Bank berhasil dipilih. Silakan transfer sesuai instruksi.');
    }

    /**
     * Show payment confirmation page
     */
    public function paymentConfirm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $payment = $order->payments()->where('status', 'pending')->latest()->first();

        if (! $payment || $payment->payment_method !== 'bank_transfer') {
            return redirect()->route('customer.orders.payment', $order->id)
                ->with('error', 'Tidak ada transaksi pembayaran tertunda.');
        }

        $bank = $payment->bank;
        $whatsappLink = $this->paymentService->generateWhatsAppLink(
            $order,
            $bank,
            null,
            $payment->amount,
            $payment->payment_type
        );

        return view('customer.orders.payment-confirm', [
            'order' => $order,
            'payment' => $payment,
            'bank' => $bank,
            'whatsappLink' => $whatsappLink,
        ]);
    }

    /**
     * Upload bukti transfer
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'payment_proof' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $payment = $order->payments()->findOrFail($validated['payment_id']);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Bukti transfer hanya dapat diunggah untuk pembayaran yang masih menunggu verifikasi.');
        }

        if ($payment->payment_proof_path) {
            Storage::disk('public')->delete($payment->payment_proof_path);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $payment->update([
            'payment_proof_path' => $path,
            'payment_note' => 'Bukti transfer diunggah pelanggan pada '.now()->format('d M Y H:i'),
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah. Tim kami akan segera memverifikasi.');
    }

    /**
     * Bayar sisa / cicilan berikutnya
     */
    public function payRemaining(Order $order)
    {
        return $this->payment($order);
    }

    /**
     * Riwayat pembayaran order
     */
    public function paymentHistory(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load(['package', 'payments.bank', 'payments.verifiedBy']);

        return view('customer.orders.payment-history', [
            'order' => $order,
            'paymentSummary' => $this->paymentService->getPaymentSummary($order),
        ]);
    }

    /**
     * Handle Midtrans notification (keep for backwards compatibility)
     */
    public function notification(Request $request)
    {
        try {
            $notification = $request->all();
            $this->midtransService->handleNotification((object) $notification);

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

        if (! $order) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order not found');
        }

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->payment_status === 'fully_paid' || $order->isFullyPaid()) {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.');
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

        if ($order->isPending() || $order->isConfirmed()) {
            $order->update(['status' => 'cancelled']);

            // Cancel any pending payments
            $order->payments()->where('status', 'pending')->update([
                'status' => 'cancelled',
                'verification_status' => 'rejected',
                'verification_notes' => 'Dibatalkan oleh pelanggan'
            ]);

            $msg = 'Pesanan Anda berhasil dibatalkan.';
            if ($order->total_paid > 0) {
                $msg .= ' Sesuai kebijakan kami, pembayaran/DP sebesar Rp ' . number_format($order->total_paid, 0, ',', '.') . ' yang telah disetor tidak dapat dikembalikan (hangus).';
            }

            return redirect()->route('customer.orders.index')
                ->with('success', $msg);
        }

        return redirect()->back()
            ->with('error', 'Cannot cancel this order');
    }
}
