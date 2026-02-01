{{-- Reusable Confirmation Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg animate-modal-in">
            <div class="modal-header border-0 pb-0">
                <div class="modal-icon-wrapper mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle modal-icon text-warning" id="confirmModalIcon"></i>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <h5 class="modal-title fw-bold mb-2" id="confirmModalLabel">Konfirmasi</h5>
                <p class="text-muted mb-0" id="confirmModalBody">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <form id="confirmModalForm" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4" id="confirmModalBtn">
                        <i class="fas fa-check me-2"></i>Ya, Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.animate-modal-in .modal-content {
    animation: modalSlideIn 0.3s ease-out;
}
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.modal-icon-wrapper {
    width: 64px;
    height: 64px;
    background: rgba(255, 193, 7, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-icon {
    font-size: 2rem;
}
#confirmModalForm:not([method="POST"]) [name="_method"] {
    display: none;
}
</style>
