<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of available packages
     */
    public function index()
    {
        $packages = Package::where('status', 'active')->get();
        return view('customer.packages.index', ['packages' => $packages]);
    }

    /**
     * Show package details
     */
    public function show(Package $package)
    {
        return view('customer.packages.show', ['package' => $package]);
    }
}
