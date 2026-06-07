@php
    $breakdown = old('breakdown', $scheme->breakdown ?? [['percentage' => 100, 'label' => 'Lunas', 'days_before_event' => null]]);
    $inputClass = 'w-full px-4 py-3 rounded-xl border border-stone-200 bg-white text-choco-900 text-sm focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none transition-all';
    $labelClass = 'block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2';
@endphp

<div class="space-y-5">
    <div>
        <label class="{{ $labelClass }}">Nama Skema *</label>
        <input type="text" name="name" class="{{ $inputClass }}" value="{{ old('name', $scheme->name ?? '') }}" required>
    </div>
    <div>
        <label class="{{ $labelClass }}">Kode (unik) *</label>
        <input type="text" name="code" class="{{ $inputClass }}" value="{{ old('code', $scheme->code ?? '') }}" required>
        <p class="text-[10px] text-stone-400 mt-1">Contoh: dp_30, installment_3x, full_payment</p>
    </div>
    <div>
        <label class="{{ $labelClass }}">Minimal Hari Sebelum Acara *</label>
        <input type="number" name="min_days_before_event" class="{{ $inputClass }}" min="0" value="{{ old('min_days_before_event', $scheme->min_days_before_event ?? 0) }}" required>
    </div>
    <div>
        <label class="{{ $labelClass }}">Deskripsi</label>
        <textarea name="description" class="{{ $inputClass }}" rows="2">{{ old('description', $scheme->description ?? '') }}</textarea>
    </div>
    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" value="1" id="is_active" class="h-4 w-4 rounded border-stone-300 text-gold-500 focus:ring-gold-200" {{ old('is_active', $scheme->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-choco-900">Skema aktif (ditampilkan ke pelanggan)</label>
    </div>
</div>

<div class="mt-8 pt-6 border-t border-stone-100">
    <h3 class="font-serif text-lg text-choco-900 mb-4">Breakdown Pembayaran <span class="text-sm text-stone-400 font-sans">(total 100%)</span></h3>
    <div id="breakdownRows" class="space-y-3">
        @foreach($breakdown as $i => $row)
            <div class="grid grid-cols-12 gap-3 breakdown-row p-4 rounded-xl bg-stone-50 border border-stone-100">
                <div class="col-span-5">
                    <label class="{{ $labelClass }}">Label</label>
                    <input type="text" name="breakdown[{{ $i }}][label]" class="{{ $inputClass }}" value="{{ $row['label'] ?? '' }}" required>
                </div>
                <div class="col-span-3">
                    <label class="{{ $labelClass }}">Persentase</label>
                    <input type="number" step="0.01" name="breakdown[{{ $i }}][percentage]" class="{{ $inputClass }}" value="{{ $row['percentage'] ?? '' }}" required>
                </div>
                <div class="col-span-4">
                    <label class="{{ $labelClass }}">H-X Acara</label>
                    <input type="number" name="breakdown[{{ $i }}][days_before_event]" class="{{ $inputClass }}" placeholder="Kosong = sekarang" value="{{ $row['days_before_event'] ?? '' }}">
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" onclick="addBreakdownRow()" class="mt-3 text-[10px] font-bold uppercase tracking-widest text-gold-600 hover:text-gold-700 transition-colors">
        + Tambah Termin
    </button>
</div>

<script>
function addBreakdownRow() {
    const container = document.getElementById('breakdownRows');
    const i = container.querySelectorAll('.breakdown-row').length;
    const inputClass = 'w-full px-4 py-3 rounded-xl border border-stone-200 bg-white text-choco-900 text-sm focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none transition-all';
    const labelClass = 'block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2';
    const div = document.createElement('div');
    div.className = 'grid grid-cols-12 gap-3 breakdown-row p-4 rounded-xl bg-stone-50 border border-stone-100';
    div.innerHTML = `
        <div class="col-span-5"><label class="${labelClass}">Label</label><input type="text" name="breakdown[${i}][label]" class="${inputClass}" required></div>
        <div class="col-span-3"><label class="${labelClass}">Persentase</label><input type="number" step="0.01" name="breakdown[${i}][percentage]" class="${inputClass}" required></div>
        <div class="col-span-4"><label class="${labelClass}">H-X Acara</label><input type="number" name="breakdown[${i}][days_before_event]" class="${inputClass}" placeholder="Kosong = sekarang"></div>
    `;
    container.appendChild(div);
}
</script>
