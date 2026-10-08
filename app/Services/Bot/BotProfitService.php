<?php

namespace App\Services\Bot;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\BotProfitCycle;
use App\Services\Promo\RoiPromoService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class BotProfitService {
    public static function run() {
        

            BotInvestment::with('bot', 'user', 'botLicense')
                ->whereIn('status', ['active', 'termination_requested'])
                ->where('next_cycle_at', '<=', now())
                ->chunkById(100, function($investments) {
                $boostedPayouts = [];

                DB::transaction(function () use($investments, &$boostedPayouts) {

                    foreach ($investments as $investment) {
                        if ($investment->is_early_terminated) continue;

                        if (!$investment->botLicense || !$investment->botLicense->isActive()) {
                            continue;
                        }

                        $bot = $investment->bot;
                        $user = $investment->user;

                        
                        if ($investment->isMatured()) {
                            $investment->update(['status' => 'completed']);

                            WalletService::debit(
                                $user,
                                $investment->amount,
                                LedgerReference::BOT_INVESTMENT_COMPLETED,
                                $investment->id,
                                'investment is complete',
                                LedgerAsset::LOCKEDBALANCE
                            );

                            WalletService::credit(
                                $user,
                                $investment->amount,
                                LedgerReference::BOT_INVESTMENT_COMPLETED,
                                $investment->id,
                                'investment is complete',
                                LedgerAsset::MAIN
                            );
                            continue;
                        }


                        $intervalsPerDay = 24 / $bot->payout_interval_hours;

                        $intervalPercent = bcdiv(
                            (string) $bot->daily_return_percent,
                            (string) $intervalsPerDay,
                            8
                        );

                        $trend = cache()->get('market_trend', 'neutral');
                        $range = match ($trend) {
                            'bull' => [90, 130],
                            'bear' => [50, 90],
                            default => [70, 110],
                        };
                        $marketFactor = random_int($range[0], $range[1]) / 100;

                        $noise = random_int(-5, 5) / 100;

                        $adjustedPercent = bcmul(
                            $intervalPercent,
                            (string) ($marketFactor + $noise),
                            8
                        );

                        $adjustedPercent = min(
                            $adjustedPercent,
                            $intervalPercent * 1.3
                        );

                        $profit = bcmul(
                            (string) $investment->amount,
                            bcdiv($adjustedPercent, '100', 8),
                            8
                        );

                        // ROI promo: boosted positions get their payout multiplied
                        $meta = ['market_factor' => $marketFactor];
                        $multiplier = RoiPromoService::multiplierFor($investment);

                        if (bccomp($multiplier, '1', 2) === 1) {
                            $baseProfit = $profit;
                            $profit = bcmul($profit, $multiplier, 8);
                            $extra = bcsub($profit, $baseProfit, 8);

                            $investment->roi_boost_earned = bcadd((string) $investment->roi_boost_earned, $extra, 8);

                            $meta['base_profit'] = $baseProfit;
                            $meta['roi_multiplier'] = $multiplier;
                            $meta['roi_promo_id'] = $investment->roi_promo_id;

                            $boostedPayouts[] = [$investment, $profit, $extra];
                        }

                        BotProfitCycle::create([
                            'bot_investment_id' => $investment->id,
                            'user_id' => $user->id,
                            'profit_amount' => $profit,
                            'cycle_at' => now(),
                            'percent' => $intervalPercent,
                            'meta' => json_encode($meta)
                        ]);

                        $investment->total_profit = bcadd(
                            (string) $investment->total_profit,
                            $profit,
                            8
                        );

                        $investment->next_cycle_at = now()->addHours($bot->payout_interval_hours);
                        $investment->save();


                        // $user->profit_balance = bcadd($user->profit_balance, $profit, 8);
                        // $user->save();

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

                // emails go out after the payouts are saved, so a mail error never undoes a payout
                foreach ($boostedPayouts as [$investment, $profit, $extra]) {
                    RoiPromoService::sendRewardEmail($investment, $profit, $extra);
                }
            });
    }
}