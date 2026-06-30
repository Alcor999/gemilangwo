@props(['priority'])

@php
    $map = [
        'low' => 'bg-emerald-50 text-emerald-700',
        'medium' => 'bg-amber-50 text-amber-700',
        'high' => 'bg-rose-50 text-rose-700',
        'urgent' => 'bg-choco-900 text-gold-400',
    ];
    $labels = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
        'urgent' => 'Mendesak',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ' . ($map[$priority] ?? 'bg-stone-100 text-stone-600')]) }}>
    {{ $labels[$priority] ?? ucfirst($priority) }}
</span>
