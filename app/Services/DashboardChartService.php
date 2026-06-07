<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Traits\DatabaseHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardChartService
{
    use DatabaseHelper;

    public function resolvePeriod(Request $request): array
    {
        $filter = $request->get('filter', 'this_month');
        $year = (int) $request->get('year', now()->year);
        $month = max(1, min(12, (int) $request->get('month', now()->month)));

        if (! array_key_exists($filter, $this->getFilterPresets())) {
            $filter = 'this_month';
        }

        $period = match ($filter) {
            'last_month' => [
                'start' => ($lastMonth = now()->copy()->subMonthNoOverflow())->copy()->startOfMonth(),
                'end' => $lastMonth->copy()->endOfMonth(),
                'granularity' => 'daily',
                'label' => 'Bulan Lalu ('.$lastMonth->translatedFormat('F Y').')',
            ],
            'last_3_months' => [
                'start' => now()->subMonths(2)->startOfMonth(),
                'end' => now()->endOfMonth(),
                'granularity' => 'monthly',
                'label' => '3 Bulan Terakhir',
            ],
            'last_6_months' => [
                'start' => now()->subMonths(5)->startOfMonth(),
                'end' => now()->endOfMonth(),
                'granularity' => 'monthly',
                'label' => '6 Bulan Terakhir',
            ],
            'this_year' => [
                'start' => now()->copy()->startOfYear(),
                'end' => now()->copy()->endOfYear(),
                'granularity' => 'monthly',
                'label' => 'Tahun '.now()->year,
            ],
            'custom' => [
                'start' => Carbon::create($year, $month, 1)->startOfMonth(),
                'end' => Carbon::create($year, $month, 1)->endOfMonth(),
                'granularity' => 'daily',
                'label' => Carbon::create($year, $month, 1)->translatedFormat('F Y'),
            ],
            default => [
                'start' => now()->copy()->startOfMonth(),
                'end' => now()->copy()->endOfMonth(),
                'granularity' => 'daily',
                'label' => 'Bulan Ini ('.now()->translatedFormat('F Y').')',
            ],
        };

        $period['filter'] = $filter;
        $period['year'] = $filter === 'custom' ? $year : $period['start']->year;
        $period['month'] = $filter === 'custom' ? $month : $period['start']->month;

        return $period;
    }

    public function getDashboardCharts(array $period): array
    {
        $start = $period['start']->copy()->startOfDay();
        $end = $period['end']->copy()->endOfDay();
        $granularity = $period['granularity'];

        $buckets = $this->buildBuckets($start, $end, $granularity);

        $eventsRaw = $this->fetchGroupedCounts(
            Order::query()->where('status', '!=', 'cancelled'),
            'event_date',
            $start,
            $end,
            $granularity
        );

        $ordersRaw = $this->fetchGroupedCounts(
            Order::query()->where('status', '!=', 'cancelled'),
            'created_at',
            $start,
            $end,
            $granularity
        );

        $revenueRaw = $this->fetchGroupedRevenue($start, $end, $granularity);

        $labels = [];
        $events = [];
        $orders = [];
        $revenue = [];

        foreach ($buckets as $key => $meta) {
            $labels[] = $meta['label'];
            $events[] = (int) ($eventsRaw[$key] ?? 0);
            $orders[] = (int) ($ordersRaw[$key] ?? 0);
            $revenue[] = (int) ($revenueRaw[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'events' => $events,
            'orders' => $orders,
            'revenue' => $revenue,
            'summary' => [
                'events_total' => array_sum($events),
                'orders_total' => array_sum($orders),
                'revenue_total' => array_sum($revenue),
            ],
            'period' => $period,
        ];
    }

    protected function buildBuckets(Carbon $start, Carbon $end, string $granularity): array
    {
        $buckets = [];

        if ($granularity === 'monthly') {
            $cursor = $start->copy()->startOfMonth();
            while ($cursor <= $end) {
                $key = $cursor->format('Y-m');
                $buckets[$key] = [
                    'label' => $cursor->translatedFormat('M Y'),
                ];
                $cursor->addMonth();
            }

            return $buckets;
        }

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $day) {
            $key = $day->format('Y-m-d');
            $buckets[$key] = [
                'label' => $day->format('j M'),
            ];
        }

        return $buckets;
    }

    protected function fetchGroupedCounts($query, string $column, Carbon $start, Carbon $end, string $granularity): array
    {
        $dateExpr = $this->getBucketExpression($column, $granularity);

        $rows = (clone $query)
            ->whereBetween($column, [$start, $end])
            ->groupBy(DB::raw($dateExpr))
            ->selectRaw("{$dateExpr} as bucket, COUNT(*) as total")
            ->pluck('total', 'bucket');

        return $rows->map(fn ($v) => (int) $v)->all();
    }

    protected function fetchGroupedRevenue(Carbon $start, Carbon $end, string $granularity): array
    {
        $dateCol = 'COALESCE(paid_at, created_at)';
        $dateExpr = $this->getBucketExpression($dateCol, $granularity);

        $rows = Payment::query()
            ->where('status', 'success')
            ->whereBetween(DB::raw($dateCol), [$start, $end])
            ->groupBy(DB::raw($dateExpr))
            ->selectRaw("{$dateExpr} as bucket, SUM(amount) as total")
            ->pluck('total', 'bucket');

        return $rows->map(fn ($v) => (int) $v)->all();
    }

    protected function getBucketExpression(string $column, string $granularity): string
    {
        if ($granularity === 'monthly') {
            return $this->getYearMonthRaw($column);
        }

        return $this->getDateRaw($column);
    }

    public function getFilterPresets(): array
    {
        return [
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            'last_3_months' => '3 Bulan Terakhir',
            'last_6_months' => '6 Bulan Terakhir',
            'this_year' => 'Tahun Ini',
            'custom' => 'Pilih Bulan',
        ];
    }
}
