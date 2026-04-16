<?php

namespace App\Livewire\Dashboard;

use App\Models\Bot;
use Livewire\Component;

class InvestmentCalculator extends Component {

    public $bots = [];
    public $selectedBotId;

    public $amount = 100;
    public $compound = false;

    public $result = [];

    public function updated() {
        $this->calculate();
    }

    public function mount() {
        $this->bots = Bot::where('is_active', true)->get();

        if ($this->bots->count()) {
            $this->selectedBotId = $this->bots->first()->id;
        }

        $this->calculate();
    }

    public function getBotProperty()
    {
        return Bot::find($this->selectedBotId);
    }

    public function render() {
        return view('livewire.dashboard.investment-calculator');
    }


    public function calculate()
    {
        if (!$this->bot) return;

        $bot = $this->bot;

        $capital = (float)$this->amount;

        // $roi = $bot->daily_return_percent;
        $dailyRoi = $bot->daily_return_percent;

        $cycleHours = $bot->payout_interval_hours;
        $durationDays  = $bot->license_duration_days;

        $totalHours = $durationDays * 24;
        $cycles = floor($totalHours / $cycleHours);

        $penalty = $bot->early_withdrawal_penalty_percent;

        $current = $capital;
        $profit = 0;
        $timeline = [];

        $cyclesPerDay = 24 / $cycleHours;
        $cycleRate = ($dailyRoi / 100) / $cyclesPerDay;

        for ($i = 1; $i <= $cycles; $i++) {

            $cycleProfit = $current * $cycleRate;

            if ($this->compound) {
                $current += $cycleProfit;
            } else {
                $profit += $cycleProfit;
            }

            $timeline[] = [
                'hour' => $i * $cycleHours,
                'value' => $this->compound ? $current : $capital + $profit
            ];
        }

        if ($this->compound) {
            $profit = $current - $capital;
        }

        $final = $capital + $profit;

        $earlyPenalty = ($capital * $penalty) / 100;
        $earlyReturn = $capital - $earlyPenalty + $profit;

        $this->result = [
            'final' => $final,
            'profit' => $profit,
            'per_cycle' => $capital * $cycleRate,
            'daily' => $capital * ($dailyRoi / 100),
            'roi' => ($profit / max($capital, 1)) * 100,
            'matures_at' => now()->addDays($durationDays)->toDateString(),
            'early_return' => $earlyReturn,
            'timeline' => $timeline
        ];
    }


}
