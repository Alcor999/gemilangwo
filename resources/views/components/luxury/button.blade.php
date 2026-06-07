@props([
    'variant' => 'primary', // primary, secondary, outline, ghost
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
])

@php
    $baseStyles = "inline-flex items-center justify-center font-sans font-medium transition-all duration-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none active:scale-95";
    
    $variants = [
        'primary' => 'bg-gold-400 text-white hover:bg-gold-500 shadow-xl shadow-gold-400/20 focus:ring-gold-300',
        'secondary' => 'bg-choco-900 text-gold-400 hover:bg-choco-800 shadow-xl shadow-choco-900/20 focus:ring-choco-700',
        'outline' => 'border border-gold-400/30 text-gold-500 hover:bg-gold-50 hover:border-gold-400 focus:ring-gold-300',
        'ghost' => 'text-stone-400 hover:bg-stone-50 hover:text-choco-900 focus:ring-stone-100',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-[10px] uppercase tracking-widest',
        'md' => 'px-6 py-3.5 text-[11px] uppercase tracking-[0.2em] font-bold',
        'lg' => 'px-10 py-5 text-xs uppercase tracking-[0.3em] font-bold',
    ];

    $classes = "{$baseStyles} {$variants[$variant]} {$sizes[$size]}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
