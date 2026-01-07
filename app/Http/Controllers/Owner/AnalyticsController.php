<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Owner Analytics Dashboard
     */
    public function dashboard()
    {
        $ownerId = Auth::id();

        $data = [
            'revenueToday' => $this->analyticsService->getOwnerRevenue($ownerId, 'today'),
            'revenueThisMonth' => $this->analyticsService->getOwnerRevenue($ownerId, 'month'),
            'revenueThisYear' => $this->analyticsService->getOwnerRevenue($ownerId, 'year'),
            'totalBookings' => $this->analyticsService->getOwnerTotalOrders($ownerId),
            'completedBookings' => $this->analyticsService->getOwnerCompletedOrders($ownerId),
            'upcomingEvents' => $this->analyticsService->getOwnerUpcomingEvents($ownerId, 30),
            'topPackages' => $this->analyticsService->getOwnerTopPackages($ownerId),
        ];

        return view('owner.analytics.dashboard', $data);
    }

    /**
     * Revenue Reports
     */
    public function revenue(Request $request)
    {
        $ownerId = Auth::id();
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $revenueData = match($period) {
            'daily' => $this->analyticsService->getOwnerRevenueByDay($ownerId, $year, $month),
            'monthly' => $this->analyticsService->getOwnerRevenueByMonth($ownerId, $year),
            'yearly' => $this->analyticsService->getOwnerRevenueByYear($ownerId),
            default => $this->analyticsService->getOwnerRevenueByMonth($ownerId, $year),
        };

        return view('owner.analytics.revenue', [
            'revenueData' => $revenueData,
            'period' => $period,
            'year' => $year,
            'month' => $month,
            'chartData' => json_encode($this->analyticsService->formatChartData($revenueData, $period)),
        ]);
    }

    /**
     * Booking Performance
     */
    public function bookings(Request $request)
    {
        $ownerId = Auth::id();
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        $bookingData = $this->analyticsService->getOwnerBookingStats($ownerId, $period, $year);
        $packages = $this->analyticsService->getOwnerTopPackages($ownerId, 10);

        return view('owner.analytics.bookings', [
            'bookingData' => $bookingData,
            'packages' => $packages,
            'period' => $period,
            'year' => $year,
            'chartData' => json_encode($this->analyticsService->formatChartData($bookingData, $period)),
        ]);
    }

    /**
     * Customer Lifetime Value
     */
    public function customerValue(Request $request)
    {
        $ownerId = Auth::id();
        $period = $request->get('period', 'month');

        $clvData = $this->analyticsService->getOwnerCustomerLifetimeValue($ownerId, $period);
        $repeatCustomers = $this->analyticsService->getOwnerRepeatCustomers($ownerId);

        return view('owner.analytics.customer-value', [
            'clvData' => $clvData,
            'repeatCustomers' => $repeatCustomers,
            'period' => $period,
        ]);
    }

    /**
     * Churn Analysis
     */
    public function churn(Request $request)
    {
        $ownerId = Auth::id();
        $months = $request->get('months', 12);

        $churnData = $this->analyticsService->getOwnerChurnAnalysis($ownerId, $months);

        return view('owner.analytics.churn', [
            'churnData' => $churnData,
            'months' => $months,
            'chartData' => json_encode($churnData),
        ]);
    }

    /**
     * Export Reports
     */
    public function export(Request $request)
    {
        $ownerId = Auth::id();
        $type = $request->get('type');
        $format = $request->get('format', 'pdf');
        $period = $request->get('period', 'month');
        $year = $request->get('year', now()->year);

        return $this->analyticsService->exportOwnerReport($ownerId, $type, $format, $period, $year);
    }
}
