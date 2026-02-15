<?php

namespace App\Livewire\Dashboard;

use App\Enums\WithdrawalStatus;
use App\Models\Withdrawal;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class WithdrawalPage extends Component {
    public $withdrawal;
    public $network;
    public $invoice;
    public $showModal = true;
    public $expiresAt;

    public function mount($withdrawal) {
        $this->withdrawal = Withdrawal::findOrFail($withdrawal);
        $this->network = $this->withdrawal->network;

        $this->expiresAt = $this->withdrawal->created_at->addMinutes(30);
    }

    public function checkWithdrawalStatus() {
        $withdrawal = $this->withdrawal->fresh();

        if ($withdrawal->status === WithdrawalStatus::COMPLETED) {
            $this->dispatch('toast', [
                'message' => 'Withdrawal confirmed!',
                'type' => 'success'
            ]);
            $this->showModal = false;
        } else {
            $this->dispatch('toast', [
                'message' => 'Withdrawal not yet received.',
                'type' => 'warning'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.withdrawal-page');
    }
}
