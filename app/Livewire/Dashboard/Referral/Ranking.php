<?php

namespace App\Livewire\Dashboard\Referral;

use App\Models\DailyResidualBonus;
use App\Services\RankEvaluatorService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Rank;
use App\Models\RankBonus;
use App\Models\BotInvestment;

#[Layout('components.layouts.app', params: ['title' => 'My Rank'])]
class Ranking extends Component {
    public $user;
    public $currentRank;
    public $nextRank;

    public $teamVolume = 0;
    public $directVolume = 0;
    public $maxBonus = 0;

    public function mount() {
        $this->user = auth()->user()->load('rank.rank');

        $this->currentRank = $this->user->rank?->rank;

        $this->teamVolume = RankEvaluatorService::getTotalTeamVolume($this->user->id);

        $this->directVolume = BotInvestment::whereIn(
            'user_id',
            getDownlineUserIds($this->user->id, 1)
        )->sum('amount');

        $this->maxBonus = $this->user->deposits()->max('bonus') ?? 0;

        $this->nextRank = Rank::where('level', '>', $this->currentRank?->level ?? 0)
            ->orderBy('level')
            ->first();
    }

    public function getProgress() {
        if (!$this->nextRank) return 100;

        $progress = min(
            ($this->teamVolume / $this->nextRank->required_volume) * 100,
            100
        );

        return round($progress, 2);
    }

    public function render() {
        return view('livewire.dashboard.referral.ranking', [
            'bonuses' => RankBonus::where([
                'user_id' => auth()->id(),
                'status' => 'claimed'
            ])->latest()->get(),
            'daily_bonuses' => DailyResidualBonus::where([
                'user_id' => auth()->id(),
                'status' => 'credited'
            ])->latest()->get(),
            'ranks' => Rank::orderBy('level')->get()
        ]);
    }
}