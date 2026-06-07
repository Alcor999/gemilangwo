@props([
    'padding' => 'p-6',
    'animate' => false,
])

<div {{ $attributes->merge([
    'class' => "bg-white border border-stone-100 rounded-xl shadow-sm overflow-hidden " . 
               ($animate ? "hover:shadow-md hover:-translate-y-1 transition-all duration-300" : "")
]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-stone-100 bg-stone-50/50">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
            {{ $footer }}
        </div>
    @endif
</div>
