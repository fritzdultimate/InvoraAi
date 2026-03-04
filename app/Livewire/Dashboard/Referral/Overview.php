<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Component;
use App\Models\Referral;
use App\Models\ReferralBonus;
use Illuminate\Support\Facades\DB;

class Overview extends Component
{
    public $referral;
    public $pendingAmount = 0;
    public $claimableAmount = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->referral = Referral::where('user_id', auth()->id())->first();

        $this->pendingAmount = ReferralBonus::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->sum('amount');

        $this->claimableAmount = ReferralBonus::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('claimable_at')
                  ->orWhere('claimable_at', '<=', now());
            })
            ->sum('amount');
    }

    public function claimAll()
    {
        DB::transaction(function () {

            $bonuses = ReferralBonus::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->lockForUpdate()
                ->get();

            foreach ($bonuses as $bonus) {

                if (!$bonus->isClaimable()) {
                    continue;
                }

                $bonus->update([
                    'status' => 'claimed',
                    'claimed_at' => now(),
                ]);

                auth()->user()->increment('deposit_balance', $bonus->amount);
            }
        });

        $this->dispatch('success', message: 'Bonuses claimed successfully.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashboard.referral.overview');
    }
}