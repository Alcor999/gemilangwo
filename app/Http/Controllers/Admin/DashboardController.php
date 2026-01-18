<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_orders = Order::count();
        $total_customers = User::where('role', 'customer')->count();
        $total_packages = Package::count();
        $total_revenue = Order::whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        
        $recent_orders = Order::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_packages' => $total_packages,
            'total_revenue' => $total_revenue,
            'recent_orders' => $recent_orders,
        ]);
    }
}
