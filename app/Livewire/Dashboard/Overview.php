<?php

namespace App\Livewire\Dashboard;

use App\Models\WalletLedger;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class  Overview extends Component {



    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        $transactions = WalletLedger::where('user_id', auth()->id())
        ->latest()
        // ->take(20)
        ->get();

        return view('livewire.dashboard.overview', [
            'transactions' => $transactions
        ]);
    }
}