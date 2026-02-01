<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\VendorCategory;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', ['packages' => $packages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendorCategories = VendorCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.packages.create', compact('vendorCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert features array to JSON
        if (isset($validated['features']) && is_array($validated['features'])) {
            $validated['features'] = json_encode(array_filter($validated['features']));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('packages', $imageName, 'public');
            $validated['image'] = 'packages/' . $imageName;
        }

        $package = Package::create($validated);
        $package->vendorCategories()->sync($request->vendor_category_ids ?? []);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $package->load('vendorCategories');
        $vendorCategories = VendorCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.packages.edit', ['package' => $package, 'vendorCategories' => $vendorCategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'max_guests' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert features array to JSON
        if (isset($validated['features']) && is_array($validated['features'])) {
            $validated['features'] = json_encode(array_filter($validated['features']));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('packages', $imageName, 'public');
            $validated['image'] = 'packages/' . $imageName;
        }

        $package->update($validated);
        $package->vendorCategories()->sync($request->vendor_category_ids ?? []);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully');
    }
}
