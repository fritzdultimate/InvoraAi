<div class="invora-container" wire:poll.10s>

    <div class="invora-payment-card" x-data="{
            time: {{ $remainingSeconds }},
            copied: false,
            expired: false,
            status: '{{ $deposit->status }}'
         }" x-init="
            let interval = setInterval(() => {
                if(time > 0){
                    time--;
                } else {
                    expired = true;
                    clearInterval(interval);
                }
            }, 1000)
         ">

        <!-- HEADER -->
        <div class="invora-payment-header">
            <div>
                <h3>Complete Your Deposit</h3>
                <p x-show="!expired" x-cloak>Send the exact amount below</p>
                <p x-show="expired" class="text-red-400" x-cloak>Payment expired</p>
            </div>

            <div class="invora-status" :class="expired ? 'failed' : 'pending'">
                <span class="capitalize" x-text="expired ? 'Expired' : '{{ $deposit->status }}'"></span>
            </div>
        </div>

        <!-- COUNTDOWN -->
        <div class="invora-countdown" :class="time < 60 ? 'danger' : ''" x-show="!expired && status !== 'finished'">

            ⏳
            <span x-text="Math.floor(time/60) + ':' + ('0'+time%60).slice(-2)"></span>
        </div>

        <!-- RECEIPT UPLOADED NOTICE -->
        @if($deposit->receipt_path)
            <div class="invora-receipt-notice">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong>Receipt Submitted</strong>
                    <p>Your payment proof is under review by our team</p>
                </div>
            </div>
        @endif

        <!-- AMOUNT (BIG EMPHASIS 🔥) -->
        <div class="invora-amount-box">
            <div class="label">Amount to Send</div>
            <div class="amount">${{ number_format($deposit->amount, 2) }}</div>
        </div>

        <!-- GRID -->
        <div class="invora-payment-grid">

            <!-- QR -->
            <livewire:dashboard.qr-code address="{{  $deposit->address  }}" />

            <!-- LEFT -->
            <div>

                <div class="invora-field">
                    <label>Currency</label>
                    <div class="value uppercase">{{ $deposit->currency }}</div>
                </div>

                <!-- ADDRESS FOCUS ZONE 🔥 -->
                <div class="invora-address-box" @click="
                        navigator.clipboard.writeText('{{ $deposit->address }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                     ">

                    <div class="label">Wallet Address</div>

                    <div class="address-text">
                        {{ $deposit->address }}
                    </div>

                    <div class="copy-feedback" x-show="copied" x-cloak>
                        Copied ✅
                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="invora-actions">

                    <button class="invora-btn-primary" wire:click="checkDepositStatus" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="checkDepositStatus">I've Sent Payment</span>
                        <span wire:loading wire:target="checkDepositStatus">Checking...</span>
                    </button>

                    @if(!$deposit->receipt_path)
                        <button class="invora-btn-secondary" wire:click="openReceiptModal" type="button">
                            📎 Upload Receipt
                        </button>
                    @else
                        <button class="invora-btn-secondary disabled" disabled>
                            ✅ Receipt Uploaded
                        </button>
                    @endif

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="invora-payment-footer">
            ⚠️ Send only {{ strtoupper($deposit->currency) }}. Wrong network = loss of funds.
        </div>

    </div>

    <!-- RECEIPT UPLOAD MODAL -->
    @if($showReceiptModal)
        <div class="invora-modal-overlay" wire:click="closeReceiptModal" x-data
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">

            <div class="invora-modal-content" @click.stop x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <!-- Modal Header -->
                <div class="invora-modal-header">
                    <h3>Upload Payment Receipt</h3>
                    <button wire:click="closeReceiptModal" class="invora-modal-close">
                        ×
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="invora-modal-body">

                    <div class="invora-upload-info">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Upload a screenshot or photo of your payment confirmation. This helps us verify your transaction
                            faster.</p>
                    </div>

                    <form wire:submit.prevent="uploadReceipt">

                        <!-- File Input -->
                        <div class="invora-file-input-wrapper">
                            <label for="receipt-upload" class="invora-file-input-label">
                                @if($receipt)
                                    <div class="preview-container">
                                        <img src="{{ $receipt->temporaryUrl() }}" alt="Receipt preview">
                                        <p class="file-name">{{ $receipt->getClientOriginalName() }}</p>
                                    </div>
                                @else
                                    <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="upload-text">Click to upload or drag and drop</span>
                                    <span class="upload-hint">PNG, JPG up to 5MB</span>
                                @endif
                            </label>

                            <input 
                                type="file" 
                                id="receipt-upload" 
                                wire:model="receipt" 
                                accept="image/*"
                                class="invora-file-input-hidden"
                                x-on:livewire-upload-start="uploadProgress = true"
                                x-on:livewire-upload-finish="uploadProgress = false"
                                x-on:livewire-upload-error="uploadProgress = false"
                            >
                        </div>

                        @error('receipt')
                            <p class="invora-error-message text-red-500">{{ $message }}</p>
                        @enderror

                        <!-- Progress Bar with Real-Time Updates -->
                        <div 
                            x-data="{ uploadProgress: false, progress: 0 }"
                            x-on:livewire-upload-start="uploadProgress = true"
                            x-on:livewire-upload-finish="uploadProgress = false; progress = 0"
                            x-on:livewire-upload-error="uploadProgress = false; progress = 0"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            x-show="uploadProgress"
                            x-cloak
                            class="invora-progress-wrapper">
                            
                            <div class="invora-progress-bar">
                                <div 
                                    class="invora-progress-fill" 
                                    :style="`width: ${progress}%`"
                                    x-transition>
                                </div>
                            </div>
                            <p class="invora-progress-text" x-text="`Uploading... ${progress}%`"></p>
                        </div>

                        <!-- Modal Actions -->
                        <div class="invora-modal-actions">
                            <button type="button" wire:click="closeReceiptModal" class="invora-btn-cancel">
                                Cancel
                            </button>

                            <button type="submit" class="invora-btn-submit" wire:loading.attr="disabled"
                                wire:target="receipt, uploadReceipt" :disabled="!@js($receipt)">
                                <span wire:loading.remove wire:target="uploadReceipt">
                                    Submit Receipt
                                </span>
                                <span wire:loading wire:target="uploadReceipt">
                                    Uploading...
                                </span>
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        /* Receipt Notice */
        .invora-receipt-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .invora-receipt-notice .icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .invora-receipt-notice strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .invora-receipt-notice p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        /* Button Updates */
        .invora-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .invora-btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .invora-btn-secondary:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(107, 114, 128, 0.3);
        }

        .invora-btn-secondary.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Modal Overlay */
        .invora-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        /* Modal Content */
        .invora-modal-content {
            background: white;
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        /* Modal Header */
        .invora-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .invora-modal-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .invora-modal-close {
            background: none;
            border: none;
            font-size: 32px;
            color: #6b7280;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .invora-modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        /* Modal Body */
        .invora-modal-body {
            padding: 24px;
        }

        .invora-upload-info {
            display: flex;
            gap: 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 24px;
        }

        .invora-upload-info .icon {
            width: 20px;
            height: 20px;
            color: #3b82f6;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .invora-upload-info p {
            font-size: 14px;
            color: #1e40af;
            margin: 0;
            line-height: 1.5;
        }

        /* File Input */
        .invora-file-input-wrapper {
            margin-bottom: 16px;
        }

        .invora-file-input-label {
            display: block;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .invora-file-input-label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .invora-file-input-hidden {
            display: none;
        }

        .upload-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            color: #9ca3af;
        }

        .upload-text {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #111827 !important;
            margin-bottom: 4px;
        }

        .upload-hint {
            display: block;
            font-size: 13px;
            color: #6b7280 !important;
        }

        /* Preview */
        .preview-container {
            text-align: center;
        }

        .preview-container img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-bottom: 12px;
            object-fit: contain;
        }

        .file-name {
            font-size: 14px;
            color: #059669 !important;
            font-weight: 500;
            word-break: break-all;
        }

        /* Error Message */
        .invora-error-message {
            color: #dc2626 !important;
            font-size: 13px;
            margin-top: 8px;
        }

        /* Progress Bar */
        .invora-progress-bar {
            height: 6px;
            background: #e5e7eb !important;
            border-radius: 3px;
            overflow: hidden;
            margin: 16px 0;
        }

        .invora-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%) !important;
            transition: width 0.3s ease;
        }

        /* Modal Actions */
        .invora-modal-actions {
            display: flex;
            flex-direction: column-reverse; /* Submit on top, Cancel below on mobile */
            gap: 10px;
            margin-top: 24px;
        }

        .invora-btn-cancel {
            background: transparent;
            color: #6b7280;
            padding: 12px 20px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 15px;
            width: 100%;
        }

        .invora-btn-cancel:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #374151;
        }

        .invora-btn-submit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .invora-btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        .invora-btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Desktop: Side by side */
        @media (min-width: 640px) {
            .invora-modal-actions {
                flex-direction: row;
                gap: 12px;
            }
            
            .invora-btn-cancel,
            .invora-btn-submit {
                flex: 1;
                width: auto;
            }
        }

        /* Better touch targets on mobile */
        @media (max-width: 640px) {
            .invora-btn-cancel,
            .invora-btn-submit {
                padding: 16px 24px; /* Larger tap area */
                font-size: 16px; /* Prevent iOS zoom */
            }
            
            .invora-modal-body {
                padding: 20px;
            }
            
            .invora-modal-header {
                padding: 20px;
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .invora-modal-content {
                margin: 0;
                border-radius: 16px 16px 0 0;
                max-height: 95vh;
            }

            .invora-actions {
                gap: 10px;
            }
        }
    </style>

    <style>
        /* Progress Wrapper */
        .invora-progress-wrapper {
            margin: 16px 0;
        }

        /* Progress Bar */
        .invora-progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .invora-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .invora-progress-text {
            font-size: 13px;
            color: #6b7280;
            text-align: center;
            margin: 0;
            font-weight: 500;
        }

        /* Loading Spinner */
        .spinner {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 8px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Button Loading State */
        .invora-btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .invora-btn-cancel[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

@endpush