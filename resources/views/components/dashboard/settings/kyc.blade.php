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
            <label>ID Front</label>
            <input type="file" wire:model="id_front">
        </div>

        <!-- BACK -->
        <div class="invora-upload-box full">
            <label>ID Back</label>
            <input type="file" wire:model="id_back">
        </div>

        <!-- SELFIE -->
        <div class="invora-upload-box full">
            <label>Selfie Verification</label>
            <input type="file" wire:model="selfie">
        </div>

        <!-- ADDRESS -->
        <div class="invora-upload-box full ">
            <label>Proof of Address (Utility Bill / Bank Statement)</label>
            <input type="file" wire:model="address_proof">
        </div>

    </div>

    <button class="invora-btn-primary" wire:click="submitKyc">
        Submit Verification
    </button>

</div>