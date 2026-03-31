@if($showConfirm)

<style>
    /* OVERLAY */
    .invora-confirm {
        position: fixed;
        inset: 0;
        background: rgba(2,6,23,0.85);
        backdrop-filter: blur(6px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeIn 0.2s ease;
    }

    /* BOX */
    .confirm-box {
        width: 92%;
        max-width: 400px;
        padding: 20px;
        border-radius: 18px;
        background: linear-gradient(145deg, #0f172a, #020617);
        border: 1px solid rgba(255,255,255,0.05);
        animation: scaleIn 0.2s ease;
    }

    /* HEADER */
    .confirm-header {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .confirm-header h4 {
        font-size: 18px;
        color: #e2e8f0;
    }

    .confirm-header p {
        font-size: 13px;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ICON */
    .confirm-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* TYPES */
    .confirm-header.danger .confirm-icon {
        background: rgba(239,68,68,0.15);
        color: #ef4444;
    }

    .confirm-header.warning .confirm-icon {
        background: rgba(251,191,36,0.15);
        color: #f59e0b;
    }

    .confirm-header.success .confirm-icon {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
    }

    /* WARNING BOX */
    .confirm-warning {
        margin-top: 14px;
        padding: 10px;
        border-radius: 10px;
        background: rgba(239,68,68,0.08);
        color: #f87171;
        font-size: 12px;
    }

    /* ACTIONS */
    .confirm-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .confirm-actions button {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        font-size: 13px;
    }

    /* BUTTONS */
    .btn-cancel {
        background: rgba(255,255,255,0.05);
        color: #cbd5f5;
    }

    .btn-confirm {
        color: #fff;
    }

    /* TYPES */
    .btn-confirm.danger {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .btn-confirm.warning {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .btn-confirm.success {
        background: linear-gradient(90deg, #22c55e, #16a34a);
    }

    /* ANIMATIONS */
    @keyframes fadeIn {
        from { opacity: 0 }
        to { opacity: 1 }
    }

    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0 }
        to { transform: scale(1); opacity: 1 }
    }
</style>

<div class="invora-confirm">

    <div class="confirm-box">

        <!-- 🔥 HEADER -->
        <div class="confirm-header {{ $type }}">
            <div class="confirm-icon">
                {{ $icon ?? '⚠️' }}
            </div>

            <div>
                <h4>{{ $title }}</h4>
                <p>{{ $message }}</p>
            </div>
        </div>

        <!-- 🔥 EXTRA WARNING -->
        @if($warning)
            <div class="confirm-warning">
                {{ $warning }}
            </div>
        @endif

        <!-- 🔥 ACTIONS -->
        <div class="confirm-actions">
            <button 
                wire:click="cancelConfirm"
                class="btn-cancel"
            >
                Cancel
            </button>

            <button 
                wire:click="confirmAction"
                class="btn-confirm {{ $type }}"
                wire:loading.attr="disabled"
                wire:target="confirmAction"
            >
                <span wire:loading.remove wire:target="confirmAction">
                    {{ $confirmText ?? 'Confirm' }}
                </span>

                <span wire:loading wire:target="confirmAction" class="btn-loader">
                    <span class="spinner"></span>
                </span>
            </button>
        </div>

    </div>

</div>

@endif