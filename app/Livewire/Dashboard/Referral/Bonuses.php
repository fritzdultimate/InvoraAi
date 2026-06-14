<?php

namespace App\Livewire\Dashboard\Referral;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Services\Wallet\WalletService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReferralBonus;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app', params: ['title' => 'Referral Bonus'])]
class Bonuses extends Component {
    use WithPagination;

    public $status = '';
    public $level = '';

    public $refDepth = 10;

    protected $paginationTheme = 'tailwind';

    public function claim($id) { 
        DB::transaction(function () use ($id) {

            $bonus = ReferralBonus::where('id', $id)
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$bonus->isClaimable()) {
                return;
            }

            $bonus->update([
                'status' => 'claimed',
                'claimed_at' => now(),
            ]);

            WalletService::credit(
                auth()->user(),
                $bonus->amount,
                LedgerReference::ReferralBonus,
                $bonus->id,
                'credited referral bonus',
                LedgerAsset::REFERRALBONUS
            );
        });

        $this->dispatch('success', message: 'Bonus claimed.');
    }

    public function render() {
        $query = ReferralBonus::where('user_id', auth()->id())
            ->where('status', 'claimed')
            ->with('fromUser')
            ->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->level) {
            $query->where('level', $this->level);
        }

        return view('livewire.dashboard.referral.bonuses', [
            'bonuses' => $query->paginate(10),
            'total' => $query->sum('amount')
        ]);
    }
} 