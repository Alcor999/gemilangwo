@props([
    'label' => null,
    'error' => null,
    'helper' => null,
])

<div class="space-y-1.5">
    @if($label)
        <label {{ $attributes->has('id') ? "for={$attributes->get('id')}" : '' }} class="block text-xs font-semibold text-choco-800 uppercase tracking-wider mb-1">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input {{ $attributes->merge([
            'class' => "block w-full px-4 py-3 bg-white border border-stone-200 rounded-lg text-choco-900 placeholder:text-stone-400 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all duration-200 " . 
                       ($error ? "border-red-500 ring-red-100" : "")
        ]) }}>
    </div>

    @if($error)
        <p class="text-xs text-red-600 italic mt-1">{{ $error }}</p>
    @elseif($helper)
        <p class="text-xs text-stone-500 mt-1">{{ $helper }}</p>
    @endif
</div>
