<?php

namespace App\Livewire\Dashboard\Support;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\SupportTicket;
use Illuminate\Support\Str;

#[Layout('components.layouts.app', params: ['title' => 'Create Ticket'])]
class CreateTicket extends Component
{
    public $subject;
    public $description;
    public $priority = 'medium';
    public $type = 'general';

    protected $rules = [
        'subject' => 'required|min:5',
        'description' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'type' => $this->type,
            'status' => 'open',
            'ticket_number' => strtoupper(Str::random(8))
        ]);

        return redirect()->route('support.index');
    }

    public function render()
    {
        return view('livewire.dashboard.support.create-ticket');
    }
}