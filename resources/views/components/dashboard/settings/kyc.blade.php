<div class="invora-kyc-page">

    <!-- STATUS -->
    @php
        $status = auth()->user()->kyc_status ?? 'unsubmitted';

        $map = [
            'unsubmitted' => [
                'class' => 'invora-kyc-unsubmitted',
                'icon' => '📝',
                'title' => 'Unsubmitted',
                'desc' => 'You have not submitted your identity verification yet.'
            ],
            'pending' => [
                'class' => 'invora-kyc-pending',
                'icon' => '⏳',
                'title' => 'Pending Review',
                'desc' => 'Your identity verification is currently under review.'
            ],
            'approved' => [
                'class' => 'invora-kyc-approved',
                'icon' => '✔',
                'title' => 'Verified',
                'desc' => 'Your identity has been successfully verified.'
            ],
            'rejected' => [
                'class' => 'invora-kyc-rejected',
                'icon' => '⚠',
                'title' => 'Verification Rejected',
                'desc' => 'Your verification was rejected. Please review and resubmit.'
            ]
        ];

        $current = $map[$status];
    @endphp

    <div class="invora-kyc-status-box {{ $current['class'] }}">

        <div class="kyc-status-top">
            <span class="kyc-status-icon">{{ $current['icon'] }}</span>

            <div>
                <div class="status-title">
                    {{ strtoupper($current['title']) }}
                </div>

                <p class="status-desc">
                    {{ $current['desc'] }}
                </p>
            </div>
        </div>

    </div>

    @if (!auth()->user()->kyc)
        
    
        <!-- FORM -->
        <div class="invora-kyc-grid">

            <div class="invora-field">
                <label class="invora-label">Document Type</label>

                <div class="invora-select-wrap">
                    <select wire:model="document_type" class="invora-input">
                        <option value="" selected>Select Document Type</option>
                        <option value="passport">Passport</option>
                        <option value="national_id">National ID</option>
                        <option value="drivers_license">Drivers License</option>
                    </select>
                </div>
            </div>

            <!-- FRONT -->
            <div class="invora-upload-box full">
                @if ($id_front)
                    <img src="{{ $id_front->temporaryUrl() }}"
                        class="halpha-w-full halpha-h-40 halpha-object-cover halpha-rounded">
                    <p class="halpha-text-xs halpha-text-gray-400 halpha-mt-2">
                        Click to replace image
                    </p>
                @else
                    <div class="upload-content">
                        <i class="ri-upload-cloud-2-line"></i>
                        <div>ID Front</div>
                        <small>Upload clear image</small>
                    </div>
                @endif
                <input type="file" wire:model="id_front">
            </div>

            <!-- BACK -->
            <div class="invora-upload-box full">
                @if ($id_back)
                    <img src="{{ $id_back->temporaryUrl() }}"
                        class="halpha-w-full halpha-h-40 halpha-object-cover halpha-rounded">
                    <p class="halpha-text-xs halpha-text-gray-400 halpha-mt-2">
                        Click to replace image
                    </p>
                @else
                    <div class="upload-content">
                        <i class="ri-upload-cloud-2-line"></i>
                        <div>ID Back</div>
                        <small>Upload clear image</small>
                    </div>
                @endif
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
                <input type="text" wire:model="country" disabled>
            </div>

            <div class="invora-input">
                <label>Date of Birth</label>
                <input type="date" disabled wire:model="dob">
            </div>

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

        <button class="invora-btn-primary" wire:click="submit" wire:loading.attr="disabled">
            <span wire:target="submit" wire:loading.remove>Submit Verification</span>
            <span class="spinner" wire:target="submit" wire:loading></span>
        </button>
    @endif

</div>