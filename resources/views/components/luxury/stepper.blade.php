@props([
    'steps' => [], // Array of objects: ['label' => '', 'status' => 'completed|current|upcoming']
])

<div {{ $attributes->merge(['class' => 'relative']) }}>
    <div class="flex items-center justify-between">
        @foreach($steps as $index => $step)
            <div class="flex flex-col items-center flex-1 relative">
                <!-- Line -->
                @if($index < count($steps) - 1)
                    <div class="absolute left-1/2 right-[-50%] top-5 h-0.5 {{ $step['status'] === 'completed' ? 'bg-gold-400' : 'bg-stone-200' }} transition-colors duration-500"></div>
                @endif

                <!-- Circle -->
                <div class="relative z-10 flex items-center justify-center w-10 h-10 transition-all duration-500 border-2 rounded-full 
                    {{ $step['status'] === 'completed' ? 'bg-gold-400 border-gold-400 text-white' : '' }}
                    {{ $step['status'] === 'current' ? 'bg-white border-gold-400 text-gold-500 ring-4 ring-gold-50 shadow-lg scale-110' : '' }}
                    {{ $step['status'] === 'upcoming' ? 'bg-white border-stone-200 text-stone-400' : '' }}">
                    
                    @if($step['status'] === 'completed')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <span class="text-sm font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>

                <!-- Label -->
                <div class="mt-3 text-center">
                    <span class="block text-[10px] font-bold uppercase tracking-widest {{ $step['status'] !== 'upcoming' ? 'text-choco-800' : 'text-stone-400' }}">
                        {{ $step['label'] }}
                    </span>
                    @if(isset($step['date']))
                        <span class="block text-[9px] text-stone-500 mt-0.5 italic">
                            {{ $step['date'] }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
