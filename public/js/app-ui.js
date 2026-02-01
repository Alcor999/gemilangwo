/**
 * Gemilang WO - UI Enhancement Scripts
 * Toast notifications, Modal confirmations, Animations
 */
(function() {
    'use strict';

    // ========== TOAST NOTIFICATIONS ==========
    window.showToast = function(message, type = 'success', duration = 4000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const icons = {
            success: 'fa-check-circle text-success',
            error: 'fa-exclamation-circle text-danger',
            warning: 'fa-exclamation-triangle text-warning',
            info: 'fa-info-circle text-info'
        };

        const bgClass = {
            success: 'bg-success',
            error: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center border-0 shadow show animate-toast-in`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas ${icons[type]} me-3 fs-5"></i>
                    <span class="text-white">${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        const bg = bgClass[type] || 'bg-primary';
        toastEl.querySelector('.toast-body').closest('.d-flex').style.background = 
            type === 'success' ? 'linear-gradient(135deg, #198754 0%, #20c997 100%)' :
            type === 'error' ? 'linear-gradient(135deg, #dc3545 0%, #e74c3c 100%)' :
            type === 'warning' ? 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)' :
            'linear-gradient(135deg, #b8860b 0%, #8b7355 100%)';

        container.appendChild(toastEl);

        const toast = new bootstrap.Toast(toastEl, { delay: duration, autohide: true });
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    };

    // Convert session flash to toast
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.alert-success');
        const errorAlert = document.querySelector('.alert-danger');
        if (successAlert && successAlert.textContent.trim()) {
            showToast(successAlert.textContent.replace(/^\s*[\s\S]*?fa-check-circle[^>]*>\s*/i, '').trim(), 'success');
            successAlert.remove();
        }
        if (errorAlert && errorAlert.textContent.trim()) {
            const errText = Array.from(errorAlert.querySelectorAll('div, li')).map(e => e.textContent).join(' ') || errorAlert.textContent;
            showToast(errText.replace(/Error!?\s*/gi, '').trim(), 'error');
            errorAlert.remove();
        }
    });

    // ========== CONFIRM MODAL ==========
    function initConfirmModals() {
        const modal = document.getElementById('confirmModal');
        if (!modal) return;

        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const form = this.closest('form') || (this.tagName === 'BUTTON' ? this.form : null);
                if (!form) return;

                const title = this.dataset.confirmTitle || 'Konfirmasi';
                const body = this.dataset.confirm || 'Apakah Anda yakin?';
                const btnText = this.dataset.confirmBtn || 'Ya, Lanjutkan';
                const btnClass = (this.dataset.confirmDanger === 'true' || this.dataset.confirmDanger === '1') ? 'btn-danger' : 'btn-primary';
                const icon = this.dataset.confirmIcon || 'fa-exclamation-triangle';

                const modalInstance = new bootstrap.Modal(modal);
                modal.querySelector('#confirmModalLabel').textContent = title;
                modal.querySelector('#confirmModalBody').textContent = body;
                modal.querySelector('#confirmModalBtn').innerHTML = `<i class="fas fa-check me-2"></i>${btnText}`;
                modal.querySelector('#confirmModalBtn').className = `btn ${btnClass} px-4`;
                const iconEl = modal.querySelector('#confirmModalIcon');
                iconEl.className = `fas ${icon} modal-icon`;
                iconEl.classList.add(btnClass === 'btn-danger' ? 'text-danger' : 'text-warning');

                const modalForm = modal.querySelector('#confirmModalForm');
                modalForm.action = form.action;
                modalForm.method = form.method || 'POST';
                modalForm.querySelectorAll('[name="_method"]').forEach(i => i.remove());
                const origMethod = form.querySelector('[name="_method"]');
                if (origMethod && origMethod.value) {
                    const m = document.createElement('input');
                    m.type = 'hidden';
                    m.name = '_method';
                    m.value = origMethod.value;
                    modalForm.appendChild(m);
                }
                modalForm.querySelectorAll('[name="_token"]').forEach(i => i.remove());
                const csrf = form.querySelector('[name="_token"]');
                if (csrf) modalForm.appendChild(csrf.cloneNode(true));
                modalForm.querySelectorAll('input:not([name="_token"]):not([name="_method"])').forEach(i => i.remove());
                form.querySelectorAll('input[type="hidden"]').forEach(input => {
                    if (input.name !== '_token' && input.name !== '_method')
                        modalForm.appendChild(input.cloneNode(true));
                });

                modalInstance.show();
            });
        });
    }

    // ========== PAGE ANIMATIONS ==========
    function initPageAnimations() {
        const content = document.querySelector('.main-content');
        if (content) {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            content.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            requestAnimationFrame(() => {
                content.style.opacity = '1';
                content.style.transform = 'translateY(0)';
            });
        }

        // Stagger card animations
        document.querySelectorAll('.card, .stat-card, .package-card').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.animation = `fadeInUp 0.5s ease forwards`;
            el.style.animationDelay = `${Math.min(i * 0.08, 0.5)}s`;
        });
    }

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes animate-toast-in {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-toast-in { animation: animate-toast-in 0.3s ease-out; }
        .table tbody tr { transition: background-color 0.2s ease; }
        .btn { transition: all 0.2s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
    `;
    document.head.appendChild(style);

    // Init on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initConfirmModals();
        initPageAnimations();
    });
})();
