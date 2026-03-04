<?php

namespace App\Livewire\Dashboard\Support;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;

#[Layout('components.layouts.app', params: ['title' => 'Viewing Ticket'])]
class ViewTicket extends Component
{
    public SupportTicket $ticket;
    public $message;

    protected $rules = [
        'message' => 'required|min:2'
    ];

    public function mount(SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== auth()->id(), 403);
        $this->ticket = $ticket->load('messages.author');
    }

    public function send()
    {
        $this->validate();

        SupportTicketMessage::create([
            'support_ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
            'is_staff' => false,
        ]);

        $this->reset('message');
        $this->ticket->refresh();
    }

    public function render()
    {
        return view('livewire.dashboard.support.view-ticket');
    }
}