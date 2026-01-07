<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Dashboard Analytics
     */
    public function dashboard()
    {
        $data = [
            'revenueToday' => $this->analyticsService->getRevenue('today'),
            'revenueThisMonth' => $this->analyticsService->getRevenue('month'),
            'revenueThisYear' => $this->analyticsService->getRevenue('year'),
            'totalCustomers' => $this->analyticsService->getTotalCustomers(),
            'newCustomersThisMonth' => $this->analyticsService->getNewCustomers('month'),
            'totalOrders' => $this->analyticsService->getTotalOrders(),
            'completedOrders' => $this->analyticsService->getCompletedOrders(),
            'conversionRate' => $this->analyticsService->getConversionRate(),
        ];

        return view('admin.analytics.dashboard', $data);
    }

    /**
     * Revenue Reports
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, monthly, yearly
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $revenueData = match($period) {
            'daily' => $this->analyticsService->getRevenueByDay($year, $month),
            'monthly' => $this->analyticsService->getRevenueByMonth($year),
            'yearly' => $this->analyticsService->getRevenueByYear(),
            default => $this->analyticsService->getRevenueByMonth($year),
        };

        return view('admin.analytics.revenue', [
            'revenueData' => $revenueData,
            'period' => $period,
            'year' => $year,
            'month' => $month,
            'chartData' => json_encode($this->analyticsService->formatChartData($revenueData, $period)),
        ]);
    }

    /**
     * Customer Acquisition Analysis
     */
    public function customers(Request $request)
    {
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        $customerData = match($period) {
            'monthly' => $this->analyticsService->getCustomersByMonth($year),
            'yearly' => $this->analyticsService->getCustomersByYear(),
            default => $this->analyticsService->getCustomersByMonth($year),
        };

        return view('admin.analytics.customers', [
            'customerData' => $customerData,
            'period' => $period,
            'year' => $year,
            'chartData' => json_encode($this->analyticsService->formatChartData($customerData, $period)),
            'topCountries' => $this->analyticsService->getTopCustomerLocations(),
        ]);
    }

    /**
     * Package Performance Analysis
     */
    public function packages(Request $request)
    {
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        $packages = $this->analyticsService->getPackagePerformance($period, $year);

        return view('admin.analytics.packages', [
            'packages' => $packages,
            'period' => $period,
            'year' => $year,
            'chartData' => json_encode($this->analyticsService->formatPackageChart($packages)),
        ]);
    }

    /**
     * Conversion Funnel Analysis
     */
    public function conversion(Request $request)
    {
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        $funnelData = $this->analyticsService->getConversionFunnel($period, $year);

        return view('admin.analytics.conversion', [
            'funnelData' => $funnelData,
            'period' => $period,
            'year' => $year,
            'chartData' => json_encode($funnelData),
        ]);
    }

    /**
     * Payment Method Breakdown
     */
    public function payments(Request $request)
    {
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        $paymentMethods = $this->analyticsService->getPaymentMethodBreakdown($period, $year);

        return view('admin.analytics.payments', [
            'paymentMethods' => $paymentMethods,
            'period' => $period,
            'year' => $year,
            'chartData' => json_encode($paymentMethods),
        ]);
    }

    /**
     * Export Reports
     */
    public function export(Request $request)
    {
        $type = $request->get('type'); // revenue, customers, packages, etc
        $format = $request->get('format', 'pdf'); // pdf or excel
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        return $this->analyticsService->exportReport($type, $format, $period, $year);
    }
}
