<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Package;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::with('creator', 'packages')->latest()->paginate(15);
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::all();
        return view('admin.discounts.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:packages,id',
        ]);

        $validated['created_by'] = auth()->id();

        $discount = Discount::create($validated);
        
        if ($request->has('packages') && !empty($request->packages)) {
            $discount->packages()->sync($request->packages);
        }

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Discount berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        $discount->load('creator', 'packages');
        return view('admin.discounts.show', compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        $packages = Package::all();
        $discount->load('packages');
        return view('admin.discounts.edit', compact('discount', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:packages,id',
        ]);

        $discount->update($validated);
        
        if ($request->has('packages')) {
            $discount->packages()->sync($request->packages ?? []);
        }

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Discount berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->packages()->detach();
        $discount->delete();

        return redirect()
            ->route('admin.discounts.index')
            ->with('success', 'Discount berhasil dihapus!');
    }
}

