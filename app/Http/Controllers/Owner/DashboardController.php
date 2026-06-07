<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\DashboardChartService;
use App\Traits\DatabaseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use DatabaseHelper;

    public function index(Request $request, DashboardChartService $chartService)
    {
        $period = $chartService->resolvePeriod($request);
        $charts = $chartService->getDashboardCharts($period);

        // Total Statistics
        $total_orders = Order::count();
        $total_customers = User::where('role', 'customer')->count();
        
        // Revenue is based on success payments
        $total_revenue = \App\Models\Payment::where('status', 'success')->sum('amount');
        
        // Pending revenue is pending payments awaiting verification
        $pending_revenue = \App\Models\Payment::where('status', 'pending')->sum('amount');
        
        // Outstanding balance (piutang)
        $outstanding_revenue = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', '!=', 'fully_paid')
            ->sum('remaining_amount');

        // DP diterima (belum lunas penuh)
        $dp_received = \App\Models\Payment::where('status', 'success')
            ->where('payment_type', 'dp')
            ->sum('amount');

        // Piutang dari order partially paid / dp_paid
        $partial_orders_count = Order::whereIn('payment_status', ['dp_paid', 'partially_paid'])
            ->where('status', '!=', 'cancelled')
            ->count();

        // Overdue payments
        $overdue_amount = \App\Models\Payment::overdue()->sum('amount');
        $overdue_count = \App\Models\Payment::overdue()->count();

        // Forecast revenue bulan ini dari piutang dengan due_date bulan ini
        $forecast_revenue = \App\Models\Payment::where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->sum('amount');

        // Order Status Summary
        $orders_by_status = Order::groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->pluck('count', 'status');

        // Monthly Revenue Chart Data (Based on successful payments)
        $monthly_revenue = DB::table('payments')
            ->where('status', 'success')
            ->selectRaw($this->getYearRaw('paid_at').' as year, '.$this->getMonthRaw('paid_at').' as month, SUM(amount) as total')
            ->groupBy(DB::raw($this->getYearRaw('paid_at')), DB::raw($this->getMonthRaw('paid_at')))
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
        $recent_orders = Order::with(['user', 'package', 'payments'])
            ->latest()
            ->take(10)
            ->get();

        return view('owner.dashboard', [
            'total_orders' => $total_orders,
            'total_customers' => $total_customers,
            'total_revenue' => $total_revenue,
            'pending_revenue' => $pending_revenue,
            'outstanding_revenue' => $outstanding_revenue,
            'dp_received' => $dp_received,
            'partial_orders_count' => $partial_orders_count,
            'overdue_amount' => $overdue_amount,
            'overdue_count' => $overdue_count,
            'forecast_revenue' => $forecast_revenue,
            'orders_by_status' => $orders_by_status,
            'monthly_revenue' => $monthly_revenue,
            'top_packages' => $top_packages,
            'recent_orders' => $recent_orders,
            'charts' => $charts,
            'filter' => $period['filter'],
            'filterYear' => $period['year'],
            'filterMonth' => $period['month'],
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

        $payment_types = DB::table('payments')
            ->select('payment_type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_type')
            ->get();

        $recent_payments = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->select('payments.*', 'orders.order_number')
            ->latest('payments.created_at')
            ->take(20)
            ->get();

        $dp_total = DB::table('payments')
            ->where('payment_type', 'dp')
            ->where('status', 'success')
            ->sum('amount');

        $remaining_total = DB::table('payments')
            ->where('payment_type', 'remaining')
            ->where('status', 'success')
            ->sum('amount');

        $installment_total = DB::table('payments')
            ->where('payment_type', 'installment')
            ->where('status', 'success')
            ->sum('amount');

        $outstanding_total = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', '!=', 'fully_paid')
            ->sum('remaining_amount');

        $forecast_revenue = DB::table('payments')
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->sum('amount');

        return view('owner.payments', [
            'payment_methods' => $payment_methods,
            'payment_status' => $payment_status,
            'payment_types' => $payment_types,
            'recent_payments' => $recent_payments,
            'dp_total' => $dp_total,
            'remaining_total' => $remaining_total,
            'installment_total' => $installment_total,
            'outstanding_total' => $outstanding_total,
            'forecast_revenue' => $forecast_revenue,
        ]);
    }
}
