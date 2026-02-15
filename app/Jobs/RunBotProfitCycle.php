<?php

namespace App\Jobs;

use App\Enums\BotInvestmentStatus;
use App\Models\BotInvestment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class RunBotProfitCycle implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $investments = BotInvestment::where('status', BotInvestmentStatus::ACTIVE->value)
        ->where('is_early_terminated', false)
        ->where('next_cycle_at', '<=', now())
        ->get();

        foreach ($investments as $investment) {
            if ($investment->isMatured()) {
                continue;
            }

            $bot = $investment->bot;

            $cyclePercent = $bot->daily_return_percent / 4; // 6hr cycle
            $profit = $investment->amount * ($cyclePercent / 100);

            DB::transaction(function () use ($investment, $profit, $cyclePercent) {

                $investment->increment('total_profit', $profit);

                $investment->user->increment('balance', $profit);

                $investment->update([
                    'next_cycle_at' => now()->addHours(6)
                ]);

                $investment->cycles()->create([
                    'profit_amount' => $profit,
                    'percent' => $cyclePercent,
                    'cycle_at' => now(),
                ]);
            });
        }
    }
}
