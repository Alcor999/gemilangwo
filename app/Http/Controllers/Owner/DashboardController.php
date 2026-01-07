<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Traits\DatabaseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use DatabaseHelper;
    public function index()
    {
        // Total Statistics
        $total_orders = Order::count();
        $total_customers = User::where('role', 'customer')->count();
        $total_revenue = Order::where('status', 'completed')->sum('total_price');
        $pending_revenue = Order::where('status', 'pending')->sum('total_price');

        // Order Status Summary
        $orders_by_status = Order::groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->pluck('count', 'status');

        // Monthly Revenue Chart Data
        $monthly_revenue = Order::where('status', 'completed')
            ->selectRaw($this->getYearRaw('created_at') . ' as year, ' . $this->getMonthRaw('created_at') . ' as month, SUM(total_price) as total')
            ->groupBy(DB::raw($this->getYearRaw('created_at')), DB::raw($this->getMonthRaw('created_at')))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Top Packages
        $top_packages = Order::select('package_id', DB::raw('count(*) as order_count'))
            ->groupBy('package_id')
            ->with('package')
            ->orderBy('order_count', 'desc')
            ->take(5)
            ->get();

        // Recent Orders
        $recent_orders = Order::with(['user', 'package', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        return view('owner.dashboard', [
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_revenue' => $total_revenue,
            'pending_revenue' => $pending_revenue,
            'orders_by_status' => $orders_by_status,
            'monthly_revenue' => $monthly_revenue,
            'top_packages' => $top_packages,
            'recent_orders' => $recent_orders,
        ]);
    }

    /**
     * Show detailed statistics and reports
     */
    public function statistics()
    {
        // Total orders by package
        $package_orders = DB::table('orders')
            ->join('packages', 'orders.package_id', '=', 'packages.id')
            ->select('packages.name', DB::raw('count(orders.id) as total'), DB::raw('sum(orders.total_price) as revenue'))
            ->groupBy('packages.id', 'packages.name')
            ->orderBy('total', 'desc')
            ->get();

        // Orders by status
        $status_summary = Order::groupBy('status')
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total_price) as total_price'))
            ->get();

        // Customer retention
        $repeat_customers = DB::table('users')
            ->where('role', 'customer')
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(orders.id) as order_count'))
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('order_count', 'desc')
            ->get();

        return view('owner.statistics', [
            'package_orders' => $package_orders,
            'status_summary' => $status_summary,
            'repeat_customers' => $repeat_customers,
        ]);
    }

    /**
     * Show payment statistics
     */
    public function payments()
    {
        $payment_methods = DB::table('payments')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $payment_status = DB::table('payments')
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('status')
            ->get();

        $recent_payments = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->select('payments.*', 'orders.order_number')
            ->latest('payments.created_at')
            ->take(20)
            ->get();

        return view('owner.payments', [
            'payment_methods' => $payment_methods,
            'payment_status' => $payment_status,
            'recent_payments' => $recent_payments,
        ]);
    }
}
