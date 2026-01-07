<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load(['orders', 'reviews']);

        $total_orders = $user->orders()->count();
        $completed_orders = $user->orders()->where('status', 'completed')->count();
        $pending_orders = $user->orders()->where('status', 'pending')->count();
        $recent_orders = $user->orders()
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', [
            'total_orders' => $total_orders,
            'completed_orders' => $completed_orders,
            'pending_orders' => $pending_orders,
            'recent_orders' => $recent_orders,
        ]);
    }
}
