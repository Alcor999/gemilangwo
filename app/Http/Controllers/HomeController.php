<?php

namespace App\Http\Controllers;

use App\Models\Package;

class HomeController extends Controller
{
    /**
     * Show the homepage
     */
    public function index()
    {
        $packages = Package::where('status', 'active')->get();
        return view('home', ['packages' => $packages]);
    }
}
