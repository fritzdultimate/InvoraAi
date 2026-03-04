<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Referral;
use App\Models\ReferralBonus;

#[Layout('components.layouts.app', params: ['title' => 'My Direct Referrals'])]
class DirectReferrals extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $directs = Referral::where('referred_by_id', auth()->id())
            ->with('user')
            ->paginate(12);

        $totalEarnings = ReferralBonus::where('user_id', auth()->id())
            ->where('level', 1)
            ->sum('amount');

        return view('livewire.dashboard.referral.direct-referrals', [
            'directs' => $directs,
            'totalEarnings' => $totalEarnings,
        ]);
    }
}