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

        // members with the most real deposits first, so the cards line up with the totals above
        $network = Referral::where($column, auth()->id())
            ->with('user')
            ->orderByDesc(
                Deposit::real()
                    ->whereColumn('deposits.user_id', 'referrals.user_id')
                    ->selectRaw('COALESCE(SUM(actually_paid), 0)')
            )
            ->orderBy('referrals.id')
            ->paginate(12);

        $earnings = ReferralBonus::where('user_id', auth()->id())
            ->where('level', $this->depth)
            ->sum('amount');

        // real deposits only: what members actually paid in, never admin top-ups
        $percentage = (float) (UnilevelPercentage::where('level', $this->depth)->value('percentage') ?? 0);

        $volumes = Deposit::real()
            ->whereIn('user_id', $network->pluck('user_id'))
            ->groupBy('user_id')
            ->selectRaw('user_id, SUM(actually_paid) as volume')
            ->pluck('volume', 'user_id');

        $userEarnings = ReferralBonus::where('user_id', auth()->id())
            ->where('level', $this->depth)
            ->whereIn('from_user_id', $network->pluck('user_id'))
            ->groupBy('from_user_id')
            ->selectRaw('from_user_id, SUM(amount) as total')
            ->pluck('total', 'from_user_id');

        $levelVolume = Deposit::real()
            ->whereIn('user_id', Referral::where($column, auth()->id())->select('user_id'))
            ->sum('actually_paid');

        return view('livewire.dashboard.referral.my-network', [
            'network' => $network,
            'earnings' => $earnings,
            'percentage' => $percentage,
            'volumes' => $volumes,
            'userEarnings' => $userEarnings,
            'levelVolume' => $levelVolume,
            'levelTeamVolume' => $levelVolume * $percentage / 100,
        ]);
    }
}