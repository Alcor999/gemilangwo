<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::with('vendorCategory');
        if ($request->filled('category')) {
            $query->where('vendor_category_id', $request->category);
        }
        $vendors = $query->orderBy('vendor_category_id')->orderBy('sort_order')->paginate(15);
        $categories = VendorCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.vendors.index', compact('vendors', 'categories'));
    }

    public function create()
    {
        $categories = VendorCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vendors', 'public');
        }

        Vendor::create($validated);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function edit(Vendor $vendor)
    {
        $categories = VendorCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.vendors.edit', compact('vendor', 'categories'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vendors', 'public');
        }

        $vendor->update($validated);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil diubah.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
