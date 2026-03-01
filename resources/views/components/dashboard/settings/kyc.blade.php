<div class="invora-kyc-page">

    <!-- STATUS -->
    <div class="invora-kyc-status-box">
        <div class="status-title">
            {{ strtoupper(auth()->user()->kyc_status ?? 'NOT VERIFIED') }}
        </div>
        <p>Your identity verification status</p>
    </div>

    <!-- FORM -->
    <div class="invora-kyc-grid">

        <!-- FRONT -->
        <div class="invora-upload-box full">
            <div class="upload-content">
                <i class="ri-upload-cloud-2-line"></i>
                <div>ID Front</div>
                <small>Upload clear image</small>
            </div>
            <input type="file" wire:model="id_front">
        </div>

        <!-- BACK -->
        <div class="invora-upload-box full">
            <div class="upload-content">
                <i class="ri-upload-cloud-2-line"></i>
                <div>ID Back</div>
                <small>Upload clear image</small>
            </div>
            <input type="file" wire:model="id_back">
        </div>


        <!-- ADDRESS -->
        <div class="invora-upload-box full">
            <div class="upload-content">
                <i class="ri-upload-cloud-2-line"></i>
                <div>Proof of Address (Utility Bill / Bank Statement)</div>
                <small>Upload clear image</small>
            </div>
            <input type="file" wire:model="address_proof">
        </div>

    </div>

    <button class="invora-btn-primary" wire:click="submitKyc">
        Submit Verification
    </button>

</div>