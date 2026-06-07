<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show pending payments list
     */
    public function pendingPayments(Request $request)
    {
        $query = \App\Models\Payment::where('verification_status', 'pending')
            ->where('status', 'pending')
            ->with('order', 'order.user', 'order.package', 'bank');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('payment_type', $request->type);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $outstandingByOrder = $payments->groupBy('order_id')->map(function ($group) {
            $order = $group->first()->order;

            return $order?->remaining_amount ?? 0;
        });

        return view('admin.payments.pending', [
            'payments' => $payments,
            'filterType' => $request->get('type', 'all'),
            'outstandingByOrder' => $outstandingByOrder,
        ]);
    }

    /**
     * Show overdue payments
     */
    public function overduePayments()
    {
        $payments = $this->paymentService->getOverduePayments();

        return view('admin.payments.overdue', [
            'payments' => $payments,
        ]);
    }

    /**
     * Send payment reminder to customer
     */
    public function sendReminder(Payment $payment)
    {
        $sent = $this->paymentService->sendPaymentReminder($payment);

        return redirect()->back()->with(
            $sent ? 'success' : 'error',
            $sent ? 'Reminder berhasil dikirim ke pelanggan.' : 'Gagal mengirim reminder. Pastikan nomor telepon pelanggan tersedia.'
        );
    }

    /**
     * Show payment detail for verification
     */
    public function verify(Payment $payment)
    {
        $payment->load('order', 'order.user', 'order.package', 'bank');

        return view('admin.payments.verify', [
            'payment' => $payment,
        ]);
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $this->paymentService->verifyPayment(
            $payment,
            auth()->user(),
            $request->notes
        );

        return redirect()->back()
            ->with('success', 'Pembayaran dikonfirmasi');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->paymentService->rejectPayment(
            $payment,
            auth()->user(),
            $request->reason
        );

        return redirect()->back()
            ->with('success', 'Pembayaran ditolak');
    }

    /**
     * Show verified payments list
     */
    public function verifiedPayments()
    {
        $payments = $this->paymentService->getVerifiedPayments();

        return view('admin.payments.verified', [
            'payments' => $payments,
        ]);
    }

    /**
     * Export pending payments
     */
    public function export()
    {
        $payments = $this->paymentService->getPendingPayments();

        return response()->json([
            'count' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'payments' => $payments->map(function ($p) {
                return [
                    'order_number' => $p->order->order_number,
                    'amount' => $p->amount,
                    'bank' => $p->bank->name,
                    'customer' => $p->order->user->name,
                    'status' => $p->verification_status,
                    'created_at' => $p->created_at,
                ];
            }),
        ]);
    }
}
