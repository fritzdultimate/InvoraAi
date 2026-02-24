<?php

namespace App\Services\Bot;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\BotProfitCycle;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class BotProfitService
{
    public static function run() {
        

            BotInvestment::with('bot', 'user')
                ->where('status', 'active')
                ->where('next_cycle_at', '<=', now())
                ->chunkById(100, function($investments) {
                DB::transaction(function () use($investments) {

                    foreach ($investments as $investment) {
                        if ($investment->is_early_terminated) continue;

                        $bot = $investment->bot;
                        $user = $investment->user;

                        
                        if ($investment->isMatured()) {
                            $investment->update(['status' => 'completed']);
                            continue;
                        }

                        $intervalsPerDay = 24 / $bot->payout_interval_hours;

                        $intervalPercent = bcdiv(
                            (string) $bot->daily_return_percent,
                            (string) $intervalsPerDay,
                            8
                        );

                        $profit = bcmul(
                            (string) $investment->amount,
                            bcdiv($intervalPercent, '100', 8),
                            8
                        );

                        BotProfitCycle::create([
                            'bot_investment_id' => $investment->id,
                            'user_id' => $user->id,
                            'profit_amount' => $profit,
                            'cycle_at' => now(),
                            'percent' => $intervalPercent,
                            // 'meta' => json_encode([
                            //     'interval_percent' => $intervalPercent
                            // ])
                        ]);

                        $investment->total_profit = bcadd(
                            (string) $investment->total_profit,
                            $profit,
                            8
                        );

                        $investment->next_cycle_at = now()->addHours($bot->payout_interval_hours);
                        $investment->save();


                        $user->profit_balance = bcadd($user->profit_balance, $profit, 8);
                        $user->save();

                        // 🧾 LEDGER ENTRY
                        WalletService::credit(
                            $user,
                            $profit,
                            LedgerReference::INVESTMENT_PROFIT,
                            $investment->id,
                            null,
                            LedgerAsset::PROFIT
                        );

                        // 🕒 UPDATE LAST PAYOUT
                        $investment->update([
                            'last_payout_at' => now()
                        ]);
                    }
                });
            });
    }
}