<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Deposit;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\UnilevelPercentage;

#[Layout('components.layouts.app', params: ['title' => 'My Referrals'])]
class MyNetwork extends Component
{
    use WithPagination;

    public $depth = 1;
    public $refDepth = 10;

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

        // Deposit volume counted the same way as team volume for ranks (RankEvaluatorService::getTotalTeamVolume),
        // and the share of it this level adds to your team volume.
        $percentage = (float) (UnilevelPercentage::where('level', $this->depth)->value('percentage') ?? 0);

        $volumes = Deposit::whereIn('user_id', $network->pluck('user_id'))
            ->where('actually_paid', '>', 0)
            ->groupBy('user_id')
            ->selectRaw('user_id, SUM(amount) as volume')
            ->pluck('volume', 'user_id');

        $levelVolume = Deposit::whereIn('user_id', Referral::where($column, auth()->id())->select('user_id'))
            ->where('actually_paid', '>', 0)
            ->sum('amount');

        return view('livewire.dashboard.referral.my-network', [
            'network' => $network,
            'earnings' => $earnings,
            'percentage' => $percentage,
            'volumes' => $volumes,
            'levelVolume' => $levelVolume,
            'levelTeamVolume' => $levelVolume * $percentage / 100,
        ]);
    }
}