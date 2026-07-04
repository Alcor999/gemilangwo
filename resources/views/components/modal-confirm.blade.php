{{-- Reusable Premium Tailwind Confirmation Modal --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Wrapper -->
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div class="relative transform overflow-hidden rounded-[2rem] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md border border-stone-100/80 animate-modal-in">
            <!-- Close button -->
            <button type="button" class="absolute top-6 right-6 text-stone-400 hover:text-stone-600 transition-colors" onclick="closeConfirmModal()">
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Content -->
            <div class="text-center mt-2">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-500" id="confirmModalIconWrapper">
                    <i class="fas fa-exclamation-triangle text-2xl" id="confirmModalIcon"></i>
                </div>
                <h3 class="font-serif text-xl text-choco-900 font-bold mb-2" id="confirmModalLabel">Konfirmasi</h3>
                <p class="text-sm text-stone-500 px-2 leading-relaxed" id="confirmModalBody">Apakah Anda yakin ingin melanjutkan?</p>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-center gap-3">
                <button type="button" class="px-6 py-3 rounded-xl border border-stone-200 text-stone-600 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-50 transition-colors" onclick="closeConfirmModal()">
                    Batal
                </button>
                <form id="confirmModalForm" method="POST" class="inline m-0">
                    @csrf
                    <input type="hidden" name="_method" id="confirmModalMethod" value="POST">
                    <button type="submit" class="px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest text-white transition-all" id="confirmModalBtn">
                        Ya, Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>

<style>
.animate-modal-in {
    animation: modalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(15px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
