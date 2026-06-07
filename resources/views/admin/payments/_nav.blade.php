@php
    $pendingCount = \App\Models\Payment::where('verification_status', 'pending')->where('status', 'pending')->count();
    $verifiedCount = \App\Models\Payment::where('verification_status', 'verified')->count();
    $active = $activeTab ?? 'pending';
@endphp

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.payments.pending') }}"
           class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ $active === 'pending' ? 'bg-gold-400 text-white shadow-lg shadow-gold-400/20' : 'bg-white text-stone-500 border border-stone-200 hover:border-gold-300 hover:text-gold-600' }}">
            <i class="fas fa-hourglass-half mr-1.5"></i> Menunggu
            @if($pendingCount > 0)
                <span class="ml-1 px-1.5 py-0.5 rounded-full bg-white/20 text-[9px]">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.overdue') }}"
           class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ $active === 'overdue' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'bg-white text-stone-500 border border-stone-200 hover:border-rose-300 hover:text-rose-600' }}">
            <i class="fas fa-exclamation-triangle mr-1.5"></i> Overdue
        </a>
        <a href="{{ route('admin.payments.verified') }}"
           class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ $active === 'verified' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-stone-500 border border-stone-200 hover:border-emerald-300 hover:text-emerald-600' }}">
            <i class="fas fa-check mr-1.5"></i> Terverifikasi
            <span class="ml-1 opacity-80">({{ $verifiedCount }})</span>
        </a>
    </div>
</div>
