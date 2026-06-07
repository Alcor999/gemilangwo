<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentScheme;
use Illuminate\Http\Request;

class PaymentSchemeController extends Controller
{
    public function index()
    {
        $schemes = PaymentScheme::orderBy('id')->get();

        return view('admin.payment-schemes.index', compact('schemes'));
    }

    public function create()
    {
        return view('admin.payment-schemes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_schemes,code',
            'min_days_before_event' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'breakdown' => 'required|array|min:1',
            'breakdown.*.percentage' => 'required|numeric|min:1|max:100',
            'breakdown.*.label' => 'required|string|max:100',
            'breakdown.*.days_before_event' => 'nullable|integer|min:0',
        ]);

        $totalPercentage = collect($validated['breakdown'])->sum('percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return back()->withInput()->with('error', 'Total persentase breakdown harus 100%.');
        }

        PaymentScheme::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'breakdown' => $validated['breakdown'],
            'min_days_before_event' => $validated['min_days_before_event'],
            'is_active' => $request->boolean('is_active', true),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->to($this->indexRoute())
            ->with('success', 'Skema pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentScheme $paymentScheme)
    {
        return view('admin.payment-schemes.edit', ['scheme' => $paymentScheme]);
    }

    public function update(Request $request, PaymentScheme $paymentScheme)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_schemes,code,'.$paymentScheme->id,
            'min_days_before_event' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'breakdown' => 'required|array|min:1',
            'breakdown.*.percentage' => 'required|numeric|min:1|max:100',
            'breakdown.*.label' => 'required|string|max:100',
            'breakdown.*.days_before_event' => 'nullable|integer|min:0',
        ]);

        $totalPercentage = collect($validated['breakdown'])->sum('percentage');
        if (abs($totalPercentage - 100) > 0.01) {
            return back()->withInput()->with('error', 'Total persentase breakdown harus 100%.');
        }

        $paymentScheme->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'breakdown' => $validated['breakdown'],
            'min_days_before_event' => $validated['min_days_before_event'],
            'is_active' => $request->boolean('is_active', true),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->to($this->indexRoute())
            ->with('success', 'Skema pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentScheme $paymentScheme)
    {
        if (in_array($paymentScheme->code, ['full_payment', 'dp_30', 'dp_50', 'installment_3x'])) {
            return back()->with('error', 'Skema default sistem tidak dapat dihapus. Nonaktifkan saja.');
        }

        $paymentScheme->delete();

        return redirect()->to($this->indexRoute())
            ->with('success', 'Skema pembayaran berhasil dihapus.');
    }

    private function indexRoute(): string
    {
        return auth()->user()->isOwner()
            ? route('owner.payment-schemes.index')
            : route('admin.payment-schemes.index');
    }
}
