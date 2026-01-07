<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Package;
use App\Models\Payment;
use App\Traits\DatabaseHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    use DatabaseHelper;
    /**
     * Admin Analytics - Revenue
     */
    public function getRevenue($period = 'month')
    {
        $query = Payment::where('status', 'success');

        return match($period) {
            'today' => $query->whereDate('created_at', now()->toDateString())->sum('amount'),
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
            'year' => $query->whereYear('created_at', now()->year)->sum('amount'),
            default => 0,
        };
    }

    public function getRevenueByDay($year, $month)
    {
        return Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue, COUNT(*) as transactions')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => Carbon::parse($item->date)->format('d M'),
                'revenue' => $item->revenue,
                'transactions' => $item->transactions,
            ]);
    }

    public function getRevenueByMonth($year)
    {
        return Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw($this->getMonthRaw('created_at')))
            ->selectRaw($this->getMonthRaw('created_at') . ' as month, SUM(amount) as revenue, COUNT(*) as transactions')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => [
                'month' => Carbon::createFromDate($year, $item->month)->format('M'),
                'revenue' => $item->revenue,
                'transactions' => $item->transactions,
            ]);
    }

    public function getRevenueByYear()
    {
        return Payment::where('status', 'success')
            ->groupBy(DB::raw($this->getYearRaw('created_at')))
            ->selectRaw($this->getYearRaw('created_at') . ' as year, SUM(amount) as revenue, COUNT(*) as transactions')
            ->orderBy('year')
            ->get()
            ->map(fn($item) => [
                'year' => $item->year,
                'revenue' => $item->revenue,
                'transactions' => $item->transactions,
            ]);
    }

    /**
     * Customer Acquisition
     */
    public function getTotalCustomers()
    {
        return User::where('role', 'customer')->count();
    }

    public function getNewCustomers($period = 'month')
    {
        $query = User::where('role', 'customer');

        return match($period) {
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'year' => $query->whereYear('created_at', now()->year)->count(),
            default => 0,
        };
    }

    public function getCustomersByMonth($year)
    {
        return User::where('role', 'customer')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw($this->getMonthRaw('created_at')))
            ->selectRaw($this->getMonthRaw('created_at') . ' as month, COUNT(*) as customers')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => [
                'month' => Carbon::createFromDate($year, $item->month)->format('M'),
                'customers' => $item->customers,
            ]);
    }

    public function getCustomersByYear()
    {
        return User::where('role', 'customer')
            ->groupBy(DB::raw($this->getYearRaw('created_at')))
            ->selectRaw($this->getYearRaw('created_at') . ' as year, COUNT(*) as customers')
            ->orderBy('year')
            ->get()
            ->map(fn($item) => [
                'year' => $item->year,
                'customers' => $item->customers,
            ]);
    }

    public function getTopCustomerLocations($limit = 5)
    {
        return User::where('role', 'customer')
            ->whereNotNull('city')
            ->groupBy('city')
            ->selectRaw('city, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }

    /**
     * Package Performance
     */
    public function getPackagePerformance($period = 'month', $year = null)
    {
        $year = $year ?? now()->year;
        
        $query = Order::select(
            'packages.id',
            'packages.name',
            'packages.price',
            DB::raw('COUNT(orders.id) as total_bookings'),
            DB::raw('SUM(orders.total_price) as total_revenue')
        )
        ->join('packages', 'orders.package_id', '=', 'packages.id')
        ->where('orders.status', '!=', 'cancelled');

        if ($period === 'month') {
            $query->whereYear('orders.created_at', $year);
        }

        return $query->groupBy('packages.id', 'packages.name', 'packages.price')
            ->orderByDesc('total_bookings')
            ->get();
    }

    /**
     * Conversion Funnel
     */
    public function getConversionFunnel($period = 'month', $year = null)
    {
        $year = $year ?? now()->year;

        $baseQuery = fn($model) => $model->whereYear('created_at', $year);

        $totalVisitors = $baseQuery(Order::query())->distinct('user_id')->count('user_id') ?? 1;
        $totalOrders = $baseQuery(Order::query())->count();
        $completedOrders = $baseQuery(Order::query())->where('status', 'completed')->count();
        $paidOrders = Payment::whereYear('created_at', $year)->where('status', 'completed')->count();

        return [
            ['stage' => 'Visitors/Browsers', 'count' => $totalVisitors, 'percentage' => 100],
            ['stage' => 'Added to Cart', 'count' => $totalOrders, 'percentage' => round(($totalOrders / $totalVisitors) * 100, 2)],
            ['stage' => 'Completed Orders', 'count' => $completedOrders, 'percentage' => round(($completedOrders / $totalVisitors) * 100, 2)],
            ['stage' => 'Paid', 'count' => $paidOrders, 'percentage' => round(($paidOrders / $totalVisitors) * 100, 2)],
        ];
    }

    /**
     * Orders
     */
    public function getTotalOrders()
    {
        return Order::count();
    }

    public function getCompletedOrders()
    {
        return Order::where('status', 'completed')->count();
    }

    /**
     * Conversion Rate
     */
    public function getConversionRate()
    {
        $totalCustomers = User::where('role', 'customer')->count() ?? 1;
        $customersWithOrders = Order::distinct('user_id')->count('user_id');

        return round(($customersWithOrders / $totalCustomers) * 100, 2);
    }

    /**
     * Payment Method Breakdown
     */
    public function getPaymentMethodBreakdown($period = 'month', $year = null)
    {
        $year = $year ?? now()->year;

        return Payment::where('status', 'success')
            ->whereYear('created_at', $year)
            ->when($period === 'month', fn($q) => $q->whereMonth('created_at', now()->month))
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
            ->get()
            ->map(fn($item) => [
                'method' => ucfirst($item->payment_method ?? 'unknown'),
                'count' => $item->count,
                'amount' => $item->total_amount,
                'percentage' => 0, // will be calculated in view
            ]);
    }

    /**
     * Format Chart Data
     */
    public function formatChartData($data, $period = 'month')
    {
        $labels = $data->pluck(match($period) {
            'daily' => 'date',
            'monthly' => 'month',
            'yearly' => 'year',
            default => 'month',
        })->toArray();

        $revenues = $data->pluck('revenue')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'tension' => 0.1,
                ]
            ]
        ];
    }

    public function formatPackageChart($packages)
    {
        return [
            'labels' => $packages->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Bookings',
                    'data' => $packages->pluck('total_bookings')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                    ]
                ]
            ]
        ];
    }

    /**
     * Export Reports
     */
    public function exportReport($type, $format = 'pdf', $period = 'month', $year = null)
    {
        $year = $year ?? now()->year;

        $data = match($type) {
            'revenue' => $this->getRevenueByMonth($year),
            'customers' => $this->getCustomersByMonth($year),
            'packages' => $this->getPackagePerformance($period, $year),
            default => [],
        };

        return match($format) {
            'pdf' => $this->exportPdf($type, $data),
            'excel' => $this->exportExcel($type, $data),
            default => response()->json(['error' => 'Invalid format'], 400),
        };
    }

    protected function exportPdf($type, $data)
    {
        // Implement using dompdf
        $html = $this->generatePdfHtml($type, $data);
        
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        
        $filename = $type . '-report-' . now()->format('Y-m-d') . '.pdf';
        
        // Return response with PDF content
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function exportExcel($type, $data)
    {
        $export = match($type) {
            'revenue' => new \App\Exports\RevenueExport($data, 'Revenue Report'),
            'packages' => new \App\Exports\PackagePerformanceExport($data),
            default => null,
        };

        if (!$export) {
            return response()->json(['error' => 'Invalid export type'], 400);
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            $type . '-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    protected function generatePdfHtml($type, $data)
    {
        $html = '<html><head><style>';
        $html .= 'body { font-family: Arial, sans-serif; margin: 20px; }';
        $html .= 'h1 { color: #333; text-align: center; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th { background-color: #007BFF; color: white; padding: 10px; text-align: left; }';
        $html .= 'td { padding: 10px; border-bottom: 1px solid #ddd; }';
        $html .= 'tr:nth-child(even) { background-color: #f2f2f2; }';
        $html .= '</style></head><body>';
        $html .= '<h1>' . ucfirst($type) . ' Report</h1>';
        $html .= '<p>Generated on: ' . now()->format('d M Y H:i:s') . '</p>';
        $html .= '<table><thead><tr>';

        if ($type === 'revenue') {
            $html .= '<th>Period</th><th>Revenue</th><th>Transactions</th>';
            $html .= '</tr></thead><tbody>';
            foreach ($data as $item) {
                $html .= '<tr><td>' . ($item['date'] ?? $item['month'] ?? $item['year']) . '</td>';
                $html .= '<td>Rp ' . number_format($item['revenue'], 0, ',', '.') . '</td>';
                $html .= '<td>' . ($item['transactions'] ?? 0) . '</td></tr>';
            }
        } elseif ($type === 'packages') {
            $html .= '<th>Package</th><th>Price</th><th>Bookings</th><th>Revenue</th>';
            $html .= '</tr></thead><tbody>';
            foreach ($data as $item) {
                $html .= '<tr><td>' . $item->name . '</td>';
                $html .= '<td>Rp ' . number_format($item->price, 0, ',', '.') . '</td>';
                $html .= '<td>' . $item->total_bookings . '</td>';
                $html .= '<td>Rp ' . number_format($item->total_revenue, 0, ',', '.') . '</td></tr>';
            }
        }

        $html .= '</tbody></table></body></html>';
        return $html;
    }

    /**
     * Owner Analytics Methods
     */
    public function getOwnerRevenue($ownerId, $period = 'month')
    {
        $query = Payment::where('status', 'success')
            ->whereHas('order', fn($q) => 
                $q->whereHas('package', fn($p) => 
                    $p->where('owner_id', $ownerId)
                )
            );

        return match($period) {
            'today' => $query->whereDate('created_at', now()->toDateString())->sum('amount'),
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
            'year' => $query->whereYear('created_at', now()->year)->sum('amount'),
            default => 0,
        };
    }

    public function getOwnerTotalOrders($ownerId)
    {
        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))->count();
    }

    public function getOwnerCompletedOrders($ownerId)
    {
        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
            ->where('status', 'completed')
            ->count();
    }

    public function getOwnerUpcomingEvents($ownerId, $days = 30)
    {
        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
            ->where('status', '!=', 'cancelled')
            ->whereBetween('event_date', [now(), now()->addDays($days)])
            ->orderBy('event_date')
            ->limit(10)
            ->get();
    }

    public function getOwnerTopPackages($ownerId, $limit = 5)
    {
        return Package::where('owner_id', $ownerId)
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get();
    }

    public function getOwnerRevenueByDay($ownerId, $year, $month)
    {
        return Payment::where('status', 'success')
            ->whereHas('order', fn($q) => 
                $q->whereHas('package', fn($p) => 
                    $p->where('owner_id', $ownerId)
                )
            )
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue, COUNT(*) as orders')
            ->orderBy('date')
            ->get();
    }

    public function getOwnerRevenueByMonth($ownerId, $year)
    {
        return Payment::where('status', 'success')
            ->whereHas('order', fn($q) => 
                $q->whereHas('package', fn($p) => 
                    $p->where('owner_id', $ownerId)
                )
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw($this->getMonthRaw('created_at')))
            ->selectRaw($this->getMonthRaw('created_at') . ' as month, SUM(amount) as revenue, COUNT(*) as orders')
            ->orderBy('month')
            ->get();
    }

    public function getOwnerRevenueByYear($ownerId)
    {
        return Payment::where('status', 'success')
            ->whereHas('order', fn($q) => 
                $q->whereHas('package', fn($p) => 
                    $p->where('owner_id', $ownerId)
                )
            )
            ->groupBy(DB::raw($this->getYearRaw('created_at')))
            ->selectRaw($this->getYearRaw('created_at') . ' as year, SUM(amount) as revenue, COUNT(*) as orders')
            ->orderBy('year')
            ->get();
    }

    public function getOwnerBookingStats($ownerId, $period = 'month', $year = null)
    {
        $year = $year ?? now()->year;

        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw($this->getMonthRaw('created_at')))
            ->selectRaw($this->getMonthRaw('created_at') . ' as month, COUNT(*) as bookings, SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            ->orderBy('month')
            ->get();
    }

    public function getOwnerCustomerLifetimeValue($ownerId, $period = 'month')
    {
        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
            ->with('user:id,name,email')
            ->select('user_id', DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_price) as ltv'))
            ->groupBy('user_id')
            ->orderByDesc('ltv')
            ->limit(20)
            ->get();
    }

    public function getOwnerRepeatCustomers($ownerId)
    {
        return Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();
    }

    public function getOwnerChurnAnalysis($ownerId, $months = 12)
    {
        $data = [];
        
        for ($i = $months; $i > 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $activeCustomers = Order::whereHas('package', fn($q) => $q->where('owner_id', $ownerId))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id');

            $data[] = [
                'month' => $startDate->format('M Y'),
                'active_customers' => $activeCustomers,
            ];
        }

        return $data;
    }

    public function exportOwnerReport($ownerId, $type, $format = 'pdf', $period = 'month', $year = null)
    {
        $year = $year ?? now()->year;

        $data = match($type) {
            'revenue' => $this->getOwnerRevenueByMonth($ownerId, $year),
            'bookings' => $this->getOwnerBookingStats($ownerId, $period, $year),
            'packages' => $this->getOwnerTopPackages($ownerId, 50),
            default => [],
        };

        return match($format) {
            'pdf' => $this->exportPdf($type, $data),
            'excel' => $this->exportExcel($type, $data),
            default => response()->json(['error' => 'Invalid format'], 400),
        };
    }
}
