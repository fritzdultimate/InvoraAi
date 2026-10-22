<?php

namespace App\Services;

use App\Enums\BotInvestmentStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

/**
 * Removes matching deposit bonuses that are 30+ days old. The bonus is taken from the
 * bonus balance first, then from the bonus part of running investments, so only real
 * deposited capital keeps trading. Profit already paid is not touched.
 */
class MatchingBonusCleanupService
{
    public const MIN_AGE_DAYS = 30;

    // Matching bonus credits old enough to remove and not removed yet
    public function dueBonuses()
    {
        $removed = WalletLedger::where('reference_type', LedgerReference::MATCHING_BONUS_REMOVED)->select('reference_id');

        return WalletLedger::where('reference_type', LedgerReference::MATCHINGDEPOSITBONUS)
            ->where('asset', LedgerAsset::DEPOSITBONUSBALANCE->value)
            ->where('credit', '>', 0)
            ->where('created_at', '<=', now()->subDays(self::MIN_AGE_DAYS))
            ->whereNotIn('id', $removed)
            ->orderBy('id')
            ->get();
    }

    /**
     * Works out (and with $apply = true, carries out) the removal per user.
     * Returns one row per user with how much comes from each place.
     */
    public function run(bool $apply = false): array
    {
        $rows = [];

        foreach ($this->dueBonuses()->groupBy('user_id') as $userId => $credits) {
            $user = User::find($userId);
            if (! $user) {
                continue;
            }

            $work = function () use ($user, $credits, $apply) {
                $bonusLeft = (float) WalletService::getBalance($user, LedgerAsset::DEPOSITBONUSBALANCE->value);
                $investments = $this->runningInvestments($user);
                $row = ['user' => $user, 'bonus' => 0.0, 'from_balance' => 0.0, 'from_investments' => 0.0, 'unrecoverable' => 0.0];

                foreach ($credits as $credit) {
                    $remaining = (float) $credit->credit;
                    $row['bonus'] += $remaining;

                    $fromBalance = min($remaining, $bonusLeft);
                    if ($fromBalance > 0) {
                        $bonusLeft -= $fromBalance;
                        $remaining -= $fromBalance;
                        $row['from_balance'] += $fromBalance;

                        if ($apply) {
                            WalletService::debit($user, $fromBalance, LedgerReference::MATCHING_BONUS_REMOVED, $credit->id, 'Matching deposit bonus adjustment', LedgerAsset::DEPOSITBONUSBALANCE);
                        }
                    }

                    foreach ($investments as $investment) {
                        if ($remaining <= 0) {
                            break;
                        }

                        $take = min($remaining, $this->bonusPart($investment));
                        if ($take <= 0) {
                            continue;
                        }

                        $remaining -= $take;
                        $row['from_investments'] += $take;
                        $investment->capital = bcsub((string) $investment->capital, (string) $take, 8);
                        $investment->amount = bcsub((string) $investment->amount, (string) $take, 8);

                        if ($apply) {
                            WalletService::debit($user, $take, LedgerReference::MATCHING_BONUS_REMOVED, $credit->id, "Matching deposit bonus adjustment ({$investment->code})", LedgerAsset::LOCKEDBALANCE);
                            $investment->save();
                        }
                    }

                    // nothing left to take it from: it was already spent on finished investments
                    if ($remaining > 0) {
                        $row['unrecoverable'] += $remaining;

                        if ($apply) {
                            // zero-amount marker so this credit is never processed again
                            WalletLedger::create([
                                'user_id' => $user->id,
                                'credit' => 0,
                                'debit' => 0,
                                'balance_after' => WalletService::getBalance($user, LedgerAsset::DEPOSITBONUSBALANCE->value),
                                'reference_type' => LedgerReference::MATCHING_BONUS_REMOVED->value,
                                'reference_id' => $credit->id,
                                'description' => 'Matching deposit bonus adjustment (already spent)',
                                'asset' => LedgerAsset::DEPOSITBONUSBALANCE->value,
                            ]);
                        }
                    }
                }

                return $row;
            };

            if (! $apply) {
                $rows[] = $work();
                continue;
            }

            try {
                $rows[] = DB::transaction($work);
            } catch (\Throwable $e) {
                report($e);
                $rows[] = ['user' => $user, 'error' => $e->getMessage()];
            }
        }

        return $rows;
    }

    private function runningInvestments(User $user)
    {
        return BotInvestment::where('user_id', $user->id)
            ->whereIn('status', [BotInvestmentStatus::ACTIVE->value, BotInvestmentStatus::TERMINATIONREQUEST->value])
            ->whereNotNull('referral_eligible_amount')
            ->orderBy('id')
            ->get();
    }

    // The part of an investment that was funded by bonus money, not a real deposit
    private function bonusPart(BotInvestment $investment): float
    {
        return max(0, (float) bcsub((string) $investment->capital, (string) $investment->referral_eligible_amount, 8));
    }
}
