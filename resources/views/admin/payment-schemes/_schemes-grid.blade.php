@php
    $routePrefix = auth()->user()->isOwner() ? 'owner.payment-schemes' : 'admin.payment-schemes';
@endphp

<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2 px-1">Payment Configuration</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Skema <span class="italic text-stone-400">Pembayaran</span></h1>
            <p class="text-stone-400 text-sm mt-2 max-w-lg">Kelola opsi pembayaran yang ditawarkan kepada pelanggan — lunas, DP, atau cicilan.</p>
        </div>
        <x-luxury.button href="{{ route($routePrefix.'.create') }}" variant="primary" size="sm">
            <i class="fas fa-plus mr-2"></i> Tambah Skema
        </x-luxury.button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($schemes as $scheme)
            <x-luxury.card class="p-0 overflow-hidden border-stone-100 {{ !$scheme->is_active ? 'opacity-70' : '' }}" animate>
                <div class="p-6 pb-4">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-500 border border-gold-100">
                                @if($scheme->code === 'full_payment')
                                    <i class="fas fa-coins"></i>
                                @elseif(str_starts_with($scheme->code, 'dp_'))
                                    <i class="fas fa-hourglass-start"></i>
                                @else
                                    <i class="fas fa-calendar-alt"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-serif text-xl text-choco-900">{{ $scheme->name }}</h3>
                                <code class="text-[10px] text-stone-400 font-mono">{{ $scheme->code }}</code>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $scheme->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-stone-100 text-stone-400' }}">
                            {{ $scheme->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    @if($scheme->description)
                        <p class="text-sm text-stone-500 mb-4 leading-relaxed">{{ $scheme->description }}</p>
                    @endif

                    <div class="flex items-center gap-2 text-[10px] text-stone-400 font-bold uppercase tracking-widest mb-4">
                        <i class="far fa-calendar"></i>
                        Min. {{ $scheme->min_days_before_event }} hari sebelum acara
                    </div>
                </div>

                <div class="px-6 pb-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gold-600/70 mb-3">Rincian Termin</p>
                    <div class="space-y-2">
                        @foreach($scheme->breakdown as $index => $item)
                            <div class="flex items-center justify-between p-3 rounded-xl {{ $index === 0 ? 'bg-gold-50/50 border border-gold-100' : 'bg-stone-50 border border-stone-100' }}">
                                <div class="flex items-center gap-3">
                                    <span class="h-6 w-6 rounded-lg bg-white flex items-center justify-center text-[10px] font-bold text-choco-900 border border-stone-100">{{ $index + 1 }}</span>
                                    <span class="text-sm font-medium text-choco-900">{{ $item['label'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-choco-900">{{ $item['percentage'] }}%</span>
                                    <p class="text-[10px] text-stone-400">
                                        @if(!empty($item['days_before_event']))
                                            H-{{ $item['days_before_event'] }} acara
                                        @else
                                            Saat checkout
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
                    <x-luxury.button href="{{ route($routePrefix.'.edit', $scheme) }}" variant="outline" size="sm" class="w-full">
                        <i class="fas fa-pen mr-2"></i> Ubah Skema
                    </x-luxury.button>
                </div>
            </x-luxury.card>
        @endforeach
    </div>

    @if($schemes->isEmpty())
        <x-luxury.card class="p-16 text-center border-stone-100">
            <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200 mb-4">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <p class="font-serif text-lg text-stone-400 italic mb-4">Belum ada skema pembayaran.</p>
            <x-luxury.button href="{{ route($routePrefix.'.create') }}" variant="primary" size="sm">
                Buat Skema Pertama
            </x-luxury.button>
        </x-luxury.card>
    @endif
</div>
