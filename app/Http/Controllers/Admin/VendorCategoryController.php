<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorCategory;
use Illuminate\Http\Request;

class VendorCategoryController extends Controller
{
    public function index()
    {
        $categories = VendorCategory::withCount('allVendors')->orderBy('sort_order')->paginate(15);
        return view('admin.vendor-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.vendor-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:vendor_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        VendorCategory::create($validated);
        return redirect()->route('admin.vendor-categories.index')
            ->with('success', 'Kategori vendor berhasil ditambahkan.');
    }

    public function edit(VendorCategory $vendorCategory)
    {
        return view('admin.vendor-categories.edit', compact('vendorCategory'));
    }

    public function update(Request $request, VendorCategory $vendorCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:vendor_categories,slug,' . $vendorCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);

        $vendorCategory->update($validated);
        return redirect()->route('admin.vendor-categories.index')
            ->with('success', 'Kategori vendor berhasil diubah.');
    }

    public function destroy(VendorCategory $vendorCategory)
    {
        if ($vendorCategory->allVendors()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori yang memiliki vendor. Hapus vendor terlebih dahulu.');
        }
        $vendorCategory->delete();
        return redirect()->route('admin.vendor-categories.index')
            ->with('success', 'Kategori vendor berhasil dihapus.');
    }
}
