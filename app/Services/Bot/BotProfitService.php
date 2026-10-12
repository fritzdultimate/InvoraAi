<?php

namespace App\Services\Bot;

use App\Enums\BotInvestmentStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\BotProfitCycle;
use App\Models\BotTermination;
use App\Services\Promo\RoiPromoService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BotProfitService {
    public static function run() {
        // Expired licenses stop earning profit, but their investments still run to maturity.
        app(BotLicenseService::class)->expireLicenses();

            BotInvestment::with('bot', 'user', 'botLicense')
                ->whereIn('status', ['active', 'termination_requested'])
                ->where('next_cycle_at', '<=', now())
                ->chunkById(100, function($investments) {
                $boostedPayouts = [];

                DB::transaction(function () use($investments, &$boostedPayouts) {

                    foreach ($investments as $investment) {
                        if ($investment->is_early_terminated) continue;

                        $bot = $investment->bot;
                        $user = $investment->user;

                        // Maturity comes before the license check, so investments under an expired
                        // license still complete on their maturity date.
                        if ($investment->isMatured()) {
                            self::complete($investment);
                            continue;
                        }

                        // No active license: the investment keeps running but earns nothing.
                        if (!$investment->botLicense || !$investment->botLicense->isActive()) {
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

    // Returns the locked funds at maturity. A termination request the admin never answered
    // is settled here: the early exit fee is taken before the investment is completed.
    private static function complete(BotInvestment $investment): void {
        try {
            DB::transaction(function () use ($investment) {
                $user = $investment->user;
                $amount = (string) $investment->amount;

                $termination = $investment->status === BotInvestmentStatus::TERMINATIONREQUEST
                    ? BotTermination::where('bot_investment_id', $investment->id)->whereNull('terminated_at')->latest()->first()
                    : null;

                if ($termination) {
                    $penaltyPercent = (string) $investment->bot->early_withdrawal_penalty_percent;
                    $penalty = bcmul($amount, bcdiv($penaltyPercent, '100', 8), 8);
                    $returned = bcsub($amount, $penalty, 8);

                    WalletService::debit($user, $returned, LedgerReference::BOT_TERMINATION, $investment->id, 'early exit settled at maturity', LedgerAsset::LOCKEDBALANCE);
                    WalletService::debit($user, $penalty, LedgerReference::BOT_TERMINATION_FEE, $investment->id, 'early exit fee', LedgerAsset::LOCKEDBALANCE);
                    WalletService::credit($user, $returned, LedgerReference::BOT_TERMINATION, $investment->id, 'early exit settled at maturity', LedgerAsset::MAIN);

                    $termination->update([
                        'penalty_percent' => $penaltyPercent,
                        'penalty_amount' => $penalty,
                        'amount_returned' => $returned,
                        'terminated_at' => now(),
                    ]);
                } else {
                    WalletService::debit($user, $amount, LedgerReference::BOT_INVESTMENT_COMPLETED, $investment->id, 'investment is complete', LedgerAsset::LOCKEDBALANCE);
                    WalletService::credit($user, $amount, LedgerReference::BOT_INVESTMENT_COMPLETED, $investment->id, 'investment is complete', LedgerAsset::MAIN);
                }

                $investment->update(['status' => BotInvestmentStatus::COMPLETED]);
            });
        } catch (\Throwable $e) {
            // Runs as a savepoint inside the chunk transaction, so one failure doesn't undo other payouts.
            Log::error("Could not complete bot investment {$investment->id}: {$e->getMessage()}");
        }
    }
}
