<div class="invora-profile-wrapper">

    <!-- HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Create Support Ticket
            </div>
            <div class="invora-profile-meta">
                Tell us what’s happening — our team will respond quickly
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="invora-card mt-4">

        <!-- SUBJECT -->
        <div class="invora-input">
            <label>Subject</label>
            <input type="text"
                wire:model.defer="subject"
                placeholder="Briefly describe your issue">
            @error('subject')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- TYPE -->
        <div class="invora-input mt-3">
            <label>Ticket Type</label>
            <select wire:model="type" class="invora-select">
                <option value="general">General Inquiry</option>
                <option value="deposit">Deposit Issue</option>
                <option value="withdrawal">Withdrawal Issue</option>
                <option value="referral">Referral Issue</option>
                <option value="technical">Technical Problem</option>
            </select>
        </div>

        <!-- PRIORITY -->
        <div class="invora-input mt-3">
            <label>Priority</label>

            <div class="priority-selector">

                <button type="button"
                    wire:click="$set('priority','low')"
                    class="priority-pill {{ $priority === 'low' ? 'active low' : '' }}">
                    Low
                </button>

                <button type="button"
                    wire:click="$set('priority','medium')"
                    class="priority-pill {{ $priority === 'medium' ? 'active normal' : '' }}">
                    Medium
                </button>

                <button type="button"
                    wire:click="$set('priority','high')"
                    class="priority-pill {{ $priority === 'high' ? 'active high' : '' }}">
                    High
                </button>

            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="invora-input mt-3">
            <label>Description</label>
            <textarea
                wire:model.defer="description"
                rows="6"
                placeholder="Describe your issue in detail..."
                class="ticket-textarea"
            ></textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- SUBMIT -->
        <button
            wire:click="submit"
            wire:loading.attr="disabled"
            class="invora-btn-primary mt-4"
        >
            <span wire:loading.remove>
                Submit Ticket
            </span>
            <span wire:loading>
                Creating...
            </span>
        </button>

    </div>

</div>