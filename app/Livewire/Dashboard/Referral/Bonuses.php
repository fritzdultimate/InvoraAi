<?php

namespace App\Livewire\Dashboard\Referral;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReferralBonus;
use Illuminate\Support\Facades\DB;

class Bonuses extends Component
{
    use WithPagination;

    public $status = '';
    public $level = '';

    protected $paginationTheme = 'tailwind';

    public function claim($id)
    {
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

            auth()->user()->increment('deposit_balance', $bonus->amount);
        });

        $this->dispatch('success', message: 'Bonus claimed.');
    }

    public function render()
    {
        $query = ReferralBonus::where('user_id', auth()->id())
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