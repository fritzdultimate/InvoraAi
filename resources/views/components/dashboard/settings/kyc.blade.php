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

        <div class="invora-field">
            <label class="invora-label">Document Type</label>

            <div class="invora-select-wrap">
                <select wire:model="document_type" class="invora-input">
                    <option value="" disabled selected>Select Document Type</option>
                    <option value="voters_card">Voters Card</option>
                    <option value="drivers_license">Drivers License</option>
                </select>
            </div>
        </div>

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

    </div>

    <div class="invora-form-grid">
        <div class="invora-input">
            <label>Address</label>
            <input type="text" wire:model.defer="address">
        </div>

        <div class="invora-input">
            <label>Country</label>
            <input type="text" value="{{ auth()->user()->country }}" disabled>
        </div>
<!-- 
        <div class="invora-input">
            <label>Date of Birth</label>
            <input type="date">
        </div> -->
    </div>

    @if ($errors->any())
        <div class="invora-alert invora-alert-danger">

            <div class="invora-alert-header">
                <span class="invora-alert-icon">⚠</span>

                <div>
                    <div class="invora-alert-title">
                        Verification submission failed
                    </div>

                    <div class="invora-alert-subtitle">
                        Please review the information below and correct the highlighted fields before continuing.
                    </div>
                </div>
            </div>

            <ul class="invora-alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    <button class="invora-btn-primary" wire:click="submit">
        Submit Verification
    </button>

</div>