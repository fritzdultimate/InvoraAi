<?php

namespace App\Livewire\Dashboard;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DepositPage extends Component {
    public $deposit; // Injected deposit
    public $network;
    public $invoice;
    public $showModal = true;
    public $expiresAt;

    public function mount($deposit) {
        $this->deposit = Deposit::findOrFail($deposit);
        $this->invoice = $this->deposit->meta ?? null;
        $this->network = $this->deposit->currency ?? 'BTC';

        $this->expiresAt = $this->deposit->created_at->addMinutes(30);
    }

    public function checkDepositStatus() {
        $deposit = $this->deposit->fresh();

        if ($deposit->status === DepositStatus::FINISHED) {
            $this->dispatch('toast', [
                'message' => 'Deposit confirmed!',
                'type' => 'success'
            ]);
            $this->showModal = false;
        } else {
            $this->dispatch('toast', [
                'message' => 'Deposit not yet received.',
                'type' => 'warning'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.deposit-page');
    }
}
