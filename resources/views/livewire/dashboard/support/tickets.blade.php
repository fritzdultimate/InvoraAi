<div class="invora-profile-wrapper">

    <!-- 🔥 HEADER CARD -->
    <div class="support-header-card">

        <div>
            <div class="invora-profile-name">
                Support Tickets
            </div>
            <div class="invora-profile-meta">
                Get help. Track responses. Stay informed.
            </div>
        </div>

        <a href="{{ route('support.create') }}" class="support-create-btn">
            <iconify-icon icon="mdi:plus-circle-outline"></iconify-icon>
            <span class="btn-text">New Ticket</span>
        </a>

    </div>


    <!-- 🔥 FILTER PILLS -->
    <div class="support-filter-bar">

        <button
            wire:click="$set('status','')"
            class="filter-pill {{ $status === '' ? 'active' : '' }}"
        >
            All
        </button>

        <button
            wire:click="$set('status','open')"
            class="filter-pill {{ $status === 'open' ? 'active open' : '' }}"
        >
            Open
        </button>

        <button
            wire:click="$set('status','in_progress')"
            class="filter-pill {{ $status === 'in_progress' ? 'active pending' : '' }}"
        >
            In progress
        </button>

        <button
            wire:click="$set('status','resolved')"
            class="filter-pill {{ $status === 'resolved' ? 'active closed' : '' }}"
        >
            Resolved
        </button>

        <button
            wire:click="$set('status','closed')"
            class="filter-pill {{ $status === 'closed' ? 'active closed' : '' }}"
        >
            Closed
        </button>

    </div>


    <!-- 🔥 TICKET CARDS -->
    <div class="support-ticket-grid mt-4">

        @forelse($tickets as $ticket)

            <a href="{{ route('support.view', $ticket->id) }}" class="ticket-card">

                <div class="ticket-left">

                    <div class="ticket-number">
                        #{{ $ticket->ticket_number }}
                    </div>

                    <div class="ticket-subject capitalize">
                        {{ $ticket->subject }}
                    </div>

                    <div class="ticket-meta">
                        {{ ucfirst($ticket->priority) }} Priority • {{ $ticket->created_at->diffForHumans() }}
                    </div>

                </div>

                <div class="ticket-right">
                    <span class="ticket-status {{ $ticket->status }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>

            </a>

        @empty
            <div class="invora-card">
                No tickets yet.
            </div>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>

</div>