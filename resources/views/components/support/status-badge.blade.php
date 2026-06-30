@props(['status'])

@php
    $map = [
        'open' => 'bg-blue-50 text-blue-700',
        'in_progress' => 'bg-sky-50 text-sky-700',
        'waiting_customer' => 'bg-amber-50 text-amber-700',
        'resolved' => 'bg-emerald-50 text-emerald-700',
        'closed' => 'bg-stone-100 text-stone-600',
    ];
    $labels = [
        'open' => 'Terbuka',
        'in_progress' => 'Diproses',
        'waiting_customer' => 'Menunggu Pelanggan',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ' . ($map[$status] ?? 'bg-stone-100 text-stone-600')]) }}>
    {{ $labels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
</span>
