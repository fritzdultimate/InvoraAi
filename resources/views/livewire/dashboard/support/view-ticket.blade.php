<div class="invora-profile-wrapper">

    <!-- HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Ticket #{{ $ticket->ticket_number }}
            </div>
            <div class="invora-profile-meta">
                {{ $ticket->subject }}
            </div>
        </div>
    </div>

    <!-- THREAD -->
    <div class="ticket-thread mt-4">

        @foreach($ticket->messages as $msg)

            <div class="ticket-row {{ $msg->is_staff ? 'staff' : 'user' }}">

                <!-- AVATAR -->
                <div class="ticket-avatar">
                    {{ strtoupper(substr($msg->author->name,0,1)) }}
                </div>

                <!-- MESSAGE -->
                <div class="ticket-bubble">

                    <div class="ticket-message-text">
                        {{ $msg->message }}
                    </div>

                    <div class="ticket-message-meta">
                        {{ $msg->author->name }} • {{ $msg->created_at->diffForHumans() }}
                    </div>

                </div>

            </div>

        @endforeach

    </div>


    <!-- REPLY BOX -->
    @if($ticket->status !== 'closed')

        <div class="ticket-reply-card mt-4">

            <textarea
                wire:model.defer="message"
                rows="3"
                placeholder="Write your reply..."
                class="ticket-textarea"
            ></textarea>

            @error('message')
                <p class="form-error">{{ $message }}</p>
            @enderror

            <div class="ticket-reply-footer">

                <span class="ticket-hint">
                    Press send to reply
                </span>

                <button
                    wire:click="send"
                    wire:loading.attr="disabled"
                    class="ticket-send-btn"
                >
                    <span wire:loading.remove>
                        Send Reply
                    </span>

                    <span wire:loading>
                        Sending...
                    </span>
                </button>

            </div>

        </div>

    @endif

</div>