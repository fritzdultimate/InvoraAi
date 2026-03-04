<?php

namespace App\Livewire\Dashboard\Support;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SupportTicket;

#[Layout('components.layouts.app', params: ['title' => 'Support Tickets'])]
class Tickets extends Component {
    use WithPagination;

    public $status = '';

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $query = SupportTicket::where('user_id', auth()->id())
            ->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.dashboard.support.tickets', [
            'tickets' => $query->paginate(10)
        ]);
    }
}