@props([
    'charts',
    'filterUrl',
    'filter' => 'this_month',
    'year' => null,
    'month' => null,
])

@php
    $chartService = app(\App\Services\DashboardChartService::class);
    $presets = $chartService->getFilterPresets();
    $summary = $charts['summary'];
    $periodLabel = $charts['period']['label'] ?? 'Periode';
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;
    $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => \Carbon\Carbon::create(null, $m)->translatedFormat('F')]);
@endphp

<div class="space-y-6" x-data="{ filter: '{{ $filter }}' }">
    {{-- Filter --}}
    <x-luxury.card :padding="'p-6'" class="border-stone-100">
        <form method="GET" action="{{ $filterUrl }}" class="flex flex-col lg:flex-row lg:items-end gap-4">
            <div class="flex-1">
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3">Filter Periode</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($presets as $key => $label)
                        <button type="submit" name="filter" value="{{ $key }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ $filter === $key ? 'bg-gold-400 text-white shadow-lg shadow-gold-400/20' : 'bg-stone-50 text-stone-500 border border-stone-200 hover:border-gold-300 hover:text-gold-600' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap items-end gap-3 p-4 rounded-2xl bg-stone-50 border border-stone-100" x-show="true">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Bulan</label>
                    <select name="month" class="px-3 py-2 rounded-xl border border-stone-200 text-sm text-choco-900 bg-white focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none">
                        @foreach($months as $m => $name)
                            <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tahun</label>
                    <select name="year" class="px-3 py-2 rounded-xl border border-stone-200 text-sm text-choco-900 bg-white focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" name="filter" value="custom"
                    class="px-4 py-2 rounded-xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-choco-800 transition-colors">
                    Terapkan
                </button>
            </div>
        </form>
    </x-luxury.card>

    {{-- Summary KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-luxury.card class="p-5 border-stone-100">
            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Acara Wedding</p>
            <p class="text-3xl font-serif text-choco-900">{{ $summary['events_total'] }}</p>
            <p class="text-[10px] text-stone-400 mt-1">{{ $periodLabel }}</p>
        </x-luxury.card>
        <x-luxury.card class="p-5 border-stone-100">
            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Pemesanan Baru</p>
            <p class="text-3xl font-serif text-blue-600">{{ $summary['orders_total'] }}</p>
            <p class="text-[10px] text-stone-400 mt-1">{{ $periodLabel }}</p>
        </x-luxury.card>
        <x-luxury.card class="p-5 border-gold-100 bg-gradient-to-br from-white to-gold-50/30">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gold-600 mb-1">Pemasukan</p>
            <p class="text-2xl font-serif text-choco-900">Rp {{ number_format($summary['revenue_total'], 0, ',', '.') }}</p>
            <p class="text-[10px] text-stone-400 mt-1">{{ $periodLabel }}</p>
        </x-luxury.card>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <x-luxury.card :padding="'p-6'" class="border-stone-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gold-500 mb-1">Event Schedule</p>
                    <h3 class="font-serif text-xl text-choco-900 italic">Grafik Acara</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="h-72 relative">
                <canvas id="eventsChart"></canvas>
            </div>
        </x-luxury.card>

        <x-luxury.card :padding="'p-6'" class="border-stone-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gold-500 mb-1">Sales Performance</p>
                    <h3 class="font-serif text-xl text-choco-900 italic">Pemesanan & Pemasukan</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="h-72 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </x-luxury.card>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = @json($charts['labels']);
    const eventsData = @json($charts['events']);
    const ordersData = @json($charts['orders']);
    const revenueData = @json($charts['revenue']);

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { font: { size: 10, family: 'Inter' }, boxWidth: 12 } },
            tooltip: {
                backgroundColor: '#2c1810',
                titleFont: { family: 'Playfair Display' },
                padding: 12,
                cornerRadius: 12,
            },
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 0, maxTicksLimit: 12 },
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { font: { size: 10 } },
            },
        },
    };

    new Chart(document.getElementById('eventsChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Acara Wedding',
                data: eventsData,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: '#10b981',
                borderWidth: 1,
                borderRadius: 8,
                borderSkipped: false,
            }],
        },
        options: chartDefaults,
    });

    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pemesanan',
                    data: ordersData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 8,
                    yAxisID: 'y',
                },
                {
                    label: 'Pemasukan (Rp jt)',
                    data: revenueData.map(v => v / 1000000),
                    type: 'line',
                    borderColor: '#b8860b',
                    backgroundColor: 'rgba(184, 134, 11, 0.1)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#b8860b',
                    pointRadius: 4,
                    yAxisID: 'y1',
                },
            ],
        },
        options: {
            ...chartDefaults,
            scales: {
                x: chartDefaults.scales.x,
                y: {
                    ...chartDefaults.scales.y,
                    position: 'left',
                    title: { display: true, text: 'Pemesanan', font: { size: 10 } },
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { size: 10 },
                        callback: v => 'Rp ' + v + ' jt',
                    },
                    title: { display: true, text: 'Pemasukan', font: { size: 10 } },
                },
            },
            plugins: {
                ...chartDefaults.plugins,
                tooltip: {
                    ...chartDefaults.plugins.tooltip,
                    callbacks: {
                        label(ctx) {
                            if (ctx.datasetIndex === 1) {
                                const val = revenueData[ctx.dataIndex];
                                return 'Pemasukan: Rp ' + val.toLocaleString('id-ID');
                            }
                            return 'Pemesanan: ' + ctx.parsed.y;
                        },
                    },
                },
            },
        },
    });
});
</script>
@endpush
