<?php

namespace App\Livewire;

use App\Models\MilestoneAlert;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MilestonePopup extends Component
{
    public ?MilestoneAlert $currentAlert = null;
    public bool $show = false;

    protected $listeners = ['checkMilestones' => 'loadAlert'];

    public function mount(): void {
        $this->loadAlert();
    }

    public function loadAlert(): void {
        $this->currentAlert = MilestoneAlert::where('user_id', Auth::id())
            ->undismissed()
            ->latest()
            ->first();

        $this->show = $this->currentAlert !== null;
    }

    public function dismiss(): void {
        $this->currentAlert?->dismiss();
        $this->currentAlert = null;
        $this->show = false;
        $this->loadAlert(); // load next undismissed if any
    }

    public function goToLeaderboard(): void
    {
        $this->currentAlert?->dismiss();
        $this->redirect(route('leaderboard')); // adjust route name
    }

    public function render()
    {
        return view('livewire.milestone-popup');
    }
}