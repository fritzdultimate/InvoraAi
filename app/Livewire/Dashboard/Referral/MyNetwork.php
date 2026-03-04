<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Referral;
use App\Models\ReferralBonus;

class MyNetwork extends Component
{
    use WithPagination;

    public $depth = 1;

    protected $paginationTheme = 'tailwind';

    public function updatingDepth()
    {
        $this->resetPage();
    }

    public function render()
    {
        $column = "level_{$this->depth}_id";

        $network = Referral::where($column, auth()->id())
            ->with('user')
            ->paginate(12);

        $earnings = ReferralBonus::where('user_id', auth()->id())
            ->where('level', $this->depth)
            ->sum('amount');

        return view('livewire.dashboard.referral.my-network', [
            'network' => $network,
            'earnings' => $earnings
        ]);
    }
}