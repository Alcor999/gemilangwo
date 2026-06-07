@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - Gemilang WO')

@section('content')
<div class="space-y-8 pb-16 max-w-3xl mx-auto">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Payment Confirmation</p>
        <h1 class="font-serif text-4xl text-choco-900 leading-tight">Konfirmasi <span class="italic text-stone-400">Pembayaran</span></h1>
    </div>

    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-100 flex gap-4 text-sm text-amber-900">
        <i class="fas fa-info-circle text-2xl text-amber-500 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-bold mb-1">Menunggu Verifikasi</p>
            <p class="text-amber-800/80">Transfer sesuai nominal ke rekening di bawah, lalu unggah bukti transfer atau hubungi via WhatsApp.</p>
        </div>
    </div>

    <x-luxury.card :padding="'p-8'" class="border-stone-100">
        <h2 class="font-serif text-lg text-choco-900 mb-5 pb-4 border-b border-stone-100">Detail Pembayaran</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8 text-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Pesanan</p>
                <p class="font-bold text-choco-900">#{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Paket</p>
                <p class="font-bold text-choco-900">{{ $order->package->name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tahap</p>
                <span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold bg-gold-50 text-gold-700">{{ $payment->type_label }}</span>
            </div>
        </div>

        @if($bank)
            <div class="p-6 rounded-2xl bg-stone-50 border-l-4 border-gold-400 space-y-4 mb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Bank</p>
                    <p class="text-lg font-bold text-choco-900">{{ $bank->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Nomor Rekening</p>
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xl font-mono font-bold text-choco-900" id="accountNumber">{{ $bank->account_number }}</p>
                        <button type="button" onclick="copyToClipboard()" class="px-3 py-1.5 rounded-lg border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-stone-600 hover:bg-white transition-colors">
                            <i class="fas fa-copy mr-1"></i> Salin
                        </button>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Atas Nama</p>
                    <p class="font-bold text-choco-900">{{ $bank->account_holder }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Jumlah Transfer (Tepat)</p>
                    <p class="text-2xl font-serif font-bold text-gold-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                @if($bank->instruction)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Catatan Transfer</p>
                        <p class="text-sm text-stone-600">{{ $bank->instruction }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="p-4 rounded-2xl bg-amber-50 text-amber-800 text-sm mb-6 border border-amber-100">
                <i class="fas fa-exclamation-triangle mr-1"></i> Informasi bank tidak ditemukan. Hubungi dukungan.
            </div>
        @endif

        @if($order->remaining_amount > 0 && $order->payment_scheme !== 'full_payment')
            <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-100 text-center text-sm mb-6">
                <div class="border-r border-stone-200">
                    <p class="text-[10px] text-stone-400 mb-1">Sisa Setelah Ini</p>
                    <p class="font-bold text-rose-600">Rp {{ number_format(max(0, $order->remaining_amount - $payment->amount), 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-stone-400 mb-1">Total Pesanan</p>
                    <p class="font-bold text-choco-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                @if($payment->due_date)
                    <div class="col-span-2 pt-3 border-t border-stone-200 text-xs text-stone-500">
                        <i class="fas fa-clock mr-1"></i> Batas waktu: <strong>{{ $payment->due_date->format('d M Y') }}</strong>
                    </div>
                @endif
            </div>
        @endif

        <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 text-sm text-stone-600 mb-6">
            <p class="font-bold text-choco-900 mb-2"><i class="fas fa-lightbulb text-gold-500 mr-1"></i> Penting</p>
            <ul class="list-disc list-inside space-y-1 text-xs">
                <li>Transfer tepat sesuai jumlah yang ditampilkan</li>
                <li>Sertakan referensi <strong>{{ $order->order_number }}</strong> di catatan transfer</li>
                <li>Verifikasi maks. 1×24 jam kerja</li>
            </ul>
        </div>

        {{-- Upload Bukti --}}
        <div class="p-5 rounded-2xl border border-stone-200 bg-white">
            <h3 class="font-serif text-base text-choco-900 mb-4"><i class="fas fa-upload text-gold-500 mr-2"></i>Unggah Bukti Transfer</h3>
            @if(session('success'))
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-3 rounded-xl bg-rose-50 text-rose-600 text-sm mb-4">{{ session('error') }}</div>
            @endif
            @if($payment->payment_proof_path)
                <div class="mb-4">
                    <img src="{{ asset('storage/'.$payment->payment_proof_path) }}" alt="Bukti transfer" class="max-h-48 rounded-xl border border-stone-200">
                    <p class="text-xs text-emerald-600 mt-2"><i class="fas fa-check-circle mr-1"></i> Bukti sudah diunggah</p>
                </div>
            @endif
            <form action="{{ route('customer.orders.uploadProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp" required
                    class="w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                <x-luxury.button type="submit" variant="primary" size="sm">Upload Bukti</x-luxury.button>
                <p class="text-[10px] text-stone-400">JPG/PNG/WEBP, maks. 5MB</p>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-8">
            @if($whatsappLink)
                <a href="{{ $whatsappLink }}" target="_blank" class="flex-1 inline-flex items-center justify-center px-6 py-3.5 rounded-2xl bg-[#25d366] text-white text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-[#1ebd56] transition-colors">
                    <i class="fab fa-whatsapp mr-2 text-lg"></i> Hubungi via WhatsApp
                </a>
            @endif
            <x-luxury.button href="{{ route('customer.orders.show', $order->id) }}" variant="outline" class="flex-1 justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan
            </x-luxury.button>
        </div>
    </x-luxury.card>

    <x-luxury.card :padding="'p-6'" class="border-stone-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-choco-900">Status Verifikasi</p>
                <p class="text-xs text-stone-400">Diperbarui {{ $payment->updated_at->format('d M Y H:i') }}</p>
            </div>
            @if($payment->status === 'success')
                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600"><i class="fas fa-check-circle mr-1"></i> Berhasil</span>
            @elseif($payment->status === 'failed')
                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
            @else
                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700"><i class="fas fa-clock mr-1"></i> Menunggu</span>
            @endif
        </div>
    </x-luxury.card>
</div>

<script>
function copyToClipboard() {
    const accountNumber = document.getElementById('accountNumber').textContent.trim();
    navigator.clipboard.writeText(accountNumber).then(() => alert('Nomor rekening berhasil disalin!'));
}
</script>
@endsection
