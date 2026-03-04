<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Component;
use App\Models\Referral;

class TreeView extends Component
{
    public $level1 = [];

    public function mount()
    {
        $this->level1 = Referral::where('referred_by_id', auth()->id())
            ->with('user')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.referral.tree-view');
    }
}