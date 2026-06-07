@extends('layouts.app')

@section('title', 'Detail Operasional Pesanan - Administrator')

@section('content')
<div class="space-y-12 pb-24" x-data="{ statusOpen: false }">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 pb-8 border-b border-stone-100">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest bg-gold-50 text-gold-600 border border-gold-100">Operational Docket</span>
                <span class="text-stone-300 text-[10px] font-bold uppercase tracking-widest">#{{ $order->order_number }}</span>
            </div>
            <h1 class="font-serif text-4xl text-choco-900 italic">Detail <span class="not-italic text-stone-300">Logistik</span></h1>
        </div>

        <div class="flex items-center gap-4">
            @php
                $statusClasses = [
                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                    'default' => 'bg-stone-50 text-stone-600 border-stone-100'
                ];
                $currentStatus = strtolower($order->status);
                $class = $statusClasses[$currentStatus] ?? $statusClasses['default'];
            @endphp
            <div class="px-6 py-3 rounded-2xl border flex items-center gap-3 {{ $class }}">
                <div class="h-2 w-2 rounded-full bg-current animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-12 items-start">
        <!-- Main Docket (Left) -->
        <div class="lg:col-span-8 space-y-12">
            <!-- Customer & Event Overview -->
            <div class="grid md:grid-cols-2 gap-8">
                <x-luxury.card class="p-8 space-y-6 border-stone-100 shadow-sm">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 border-b border-stone-50 pb-4">Profil Pelanggan</h5>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-stone-50 flex items-center justify-center text-choco-900 border border-stone-100 font-bold uppercase text-xs">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-choco-900">{{ $order->user->name }}</p>
                                <p class="text-[10px] text-stone-400 italic">Primary Client</p>
                            </div>
                        </div>
                        <div class="grid gap-3 pt-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 font-light italic">Elektronik Surat</span>
                                <span class="text-choco-900 font-medium">{{ $order->user->email }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 font-light italic">Kontak Jalur</span>
                                <span class="text-choco-900 font-medium">{{ $order->user->phone ?? 'Unlisted' }}</span>
                            </div>
                        </div>
                    </div>
                </x-luxury.card>

                <x-luxury.card class="p-8 space-y-6 border-stone-100 shadow-sm">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 border-b border-stone-50 pb-4">Konfigurasi Acara</h5>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 border border-gold-100">
                                <i class="fas fa-calendar-day text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-choco-900">{{ $order->event_date->format('l, d F Y') }}</p>
                                <p class="text-[10px] text-stone-400 italic">Timeline Alotted</p>
                            </div>
                        </div>
                        <div class="grid gap-3 pt-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 font-light italic">Destinasi Lokasi</span>
                                <span class="text-choco-900 font-medium text-right max-w-[150px] truncate" title="{{ $order->event_location }}">{{ $order->event_location }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 font-light italic">Delegasi Tamu</span>
                                <span class="text-choco-900 font-medium">{{ $order->guest_count }} Personas</span>
                            </div>
                        </div>
                    </div>
                </x-luxury.card>
            </div>

            <!-- Logistic Details (Vendors) -->
            <x-luxury.card class="p-10 space-y-8 border-stone-100 shadow-sm">
                <h5 class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400">Manajemen Vendor & Logistik</h5>
                <div class="overflow-hidden rounded-3xl border border-stone-50">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-stone-50 text-[9px] font-bold uppercase tracking-[0.3em] text-gold-600/60">
                                <th class="px-6 py-4">Kategori Layanan</th>
                                <th class="px-6 py-4">Pihak Vendor</th>
                                <th class="px-6 py-4 text-right">Valuasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($order->orderVendors as $ov)
                                <tr class="group hover:bg-stone-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-bold text-choco-900 uppercase tracking-widest">{{ $ov->vendor_category_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500 italic">{{ $ov->vendor_name }}</td>
                                    <td class="px-6 py-4 text-right text-xs font-bold text-choco-900">
                                        Rp {{ number_format($ov->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-stone-50/30">
                                <td colspan="2" class="px-6 py-6 text-right">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Nilai Akumulatif</span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="text-lg font-serif font-bold text-choco-900 tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-luxury.card>
            
            @if($order->special_request)
                <div class="p-10 rounded-[3rem] bg-stone-900 text-white space-y-4">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold-400">Permintaan Khusus (Priority)</h5>
                    <p class="text-sm font-light italic leading-relaxed text-white/70">"{{ $order->special_request }}"</p>
                </div>
            @endif
        </div>

        <!-- Sidebar / Actions (Right) -->
        <div class="lg:col-span-4 space-y-12 h-full">
            <!-- Financial Control Center -->
            <x-luxury.card class="p-10 space-y-8 border-gold-100 shadow-2xl relative overflow-hidden bg-gradient-to-br from-white to-gold-50/30">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-400/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
                
                <h5 class="text-[10px] font-bold uppercase tracking-[0.2em] text-choco-900 border-b border-gold-100 pb-4">Finance Terminal</h5>
                
                <div class="space-y-6 relative z-10">
                    {{-- Skema & Progress --}}
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-stone-400 italic">Skema Pembayaran</span>
                            <span class="font-bold text-choco-900">{{ $order->scheme_label }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-stone-400 italic">Status Pembayaran</span>
                            <span class="font-bold text-gold-600">{{ $order->payment_status_label }}</span>
                        </div>
                        @php $progress = $order->getPaymentProgress(); @endphp
                        <div class="w-full bg-stone-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-stone-400">
                            <span>Dibayar: Rp {{ number_format($order->total_paid, 0, ',', '.') }}</span>
                            <span>Sisa: Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Payment Timeline --}}
                    <div class="space-y-4">
                        <label class="text-[9px] font-bold uppercase tracking-widest text-gold-600/60 block">Payment Timeline</label>
                        @forelse($order->payments->sortBy('created_at') as $payment)
                            <div class="bg-white p-4 rounded-2xl border border-stone-50 shadow-sm space-y-3" x-data="{ rejectOpen: false }">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-choco-900">
                                            {{ $payment->type_label }}
                                            @if($payment->installment_number)
                                                #{{ $payment->installment_number }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-stone-400 mt-1">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-choco-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </div>

                                @if($payment->bank)
                                    <p class="text-[10px] text-stone-400">{{ $payment->bank->name }} — {{ $payment->bank->account_number }}</p>
                                @endif

                                @if($payment->payment_proof_path)
                                    <a href="{{ asset('storage/'.$payment->payment_proof_path) }}" target="_blank" class="text-[10px] text-blue-500 underline">Lihat bukti transfer</a>
                                @endif

                                @if($payment->due_date)
                                    <p class="text-[10px] {{ $payment->due_date->isPast() && $payment->status === 'pending' ? 'text-rose-500' : 'text-stone-400' }}">
                                        Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                                        @if($payment->due_date->isPast() && $payment->status === 'pending') (Overdue) @endif
                                    </p>
                                @endif

                                @if($payment->verification_status === 'pending' && $payment->status === 'pending')
                                    <div class="grid gap-2 pt-2">
                                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-3 rounded-xl bg-choco-900 text-gold-400 text-[9px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all active:scale-95">
                                                Approve #{{ $payment->id }}
                                            </button>
                                        </form>
                                        <button @click="rejectOpen = !rejectOpen" type="button" class="w-full py-2 rounded-xl border border-rose-100 text-rose-500 text-[9px] font-bold uppercase tracking-widest hover:bg-rose-50 transition-all">
                                            Deny
                                        </button>
                                        <div x-show="rejectOpen" x-collapse x-cloak>
                                            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="space-y-2 pt-2">
                                                @csrf
                                                <textarea name="reason" required placeholder="Alasan penolakan..." class="w-full bg-white border border-rose-100 rounded-xl p-3 text-[10px] text-rose-700 outline-none min-h-[80px]"></textarea>
                                                <button type="submit" class="w-full py-2 rounded-lg bg-rose-500 text-white text-[9px] font-bold uppercase">Confirm Denial</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="px-3 py-2 rounded-xl text-[9px] font-bold uppercase tracking-widest {{ $payment->verification_status === 'verified' ? 'bg-emerald-50 text-emerald-600' : ($payment->verification_status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-stone-50 text-stone-500') }}">
                                        {{ $payment->verification_status }}
                                        @if($payment->verifiedBy) — {{ $payment->verifiedBy->name }} @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center space-y-3">
                                <div class="h-12 w-12 bg-stone-50 rounded-2xl mx-auto flex items-center justify-center text-stone-200">
                                    <i class="fas fa-receipt text-xl"></i>
                                </div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-300">Belum Ada Pembayaran</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </x-luxury.card>

            <!-- Status Control Center -->
            <x-luxury.card class="p-10 space-y-8 border-stone-100 shadow-sm h-full">
                <h5 class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 border-b border-stone-50 pb-4">Execution Control</h5>
                
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-3">
                        <label class="text-[9px] font-bold uppercase tracking-widest text-stone-400">Master Operational Status</label>
                        <select name="status" required class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl px-6 py-4 text-[11px] text-choco-900 font-bold focus:ring-2 focus:ring-gold-400/20 outline-none transition-all shadow-sm">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Under Review</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed & Locked</option>
                            <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Production</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Successfully Executed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 rounded-2xl bg-white border border-gold-100 text-gold-600 text-[10px] font-bold uppercase tracking-widest hover:bg-gold-50 transition-all shadow-sm active:scale-95">Update Lifecycle</button>
                </form>

                @if($order->isPending())
                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="pt-4 border-t border-stone-50">
                        @csrf
                        <button type="button" 
                                class="w-full py-4 rounded-2xl border border-stone-100 text-stone-300 text-[10px] font-bold uppercase tracking-widest hover:text-rose-400 hover:border-rose-100 transition-all"
                                data-confirm="Akhiri transaksi ini secara permanen?"
                                data-confirm-title="Terminasi Pesanan"
                                data-confirm-btn="Terminasi"
                                data-confirm-danger="1">Terminasi Sesi</button>
                    </form>
                @endif
            </x-luxury.card>
        </div>
    </div>
</div>
@endsection
