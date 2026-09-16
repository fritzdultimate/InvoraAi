<?php

namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\ReferralLevel;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralBonusService {
    public static function cdistribute(User $investor, BotInvestment $inv): void {
        if($investor->hasRole('leader') && !$investor->can_distribute_referral_bonus) return;
        $levels = ReferralLevel::where('is_active', true)
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        if ($levels->isEmpty()) {
            return;
        }

        $referralTree = Referral::where('user_id', $investor->id)->first();

        if (! $referralTree) {
            return;
        }

        DB::transaction(function () use ($levels, $referralTree, $inv, $investor) {
            foreach ($levels as $config) {
                $level = $config->level;
                if ($level > 10) {
                    break;
                }

                $referrerUserId = $referralTree->{"level_{$level}_id"} ?? null;

                if (! $referrerUserId) {
                    continue;
                }

                $referrer = User::find($referrerUserId);
                if (! $referrer) {
                    continue;
                }

                if ($referrer->id === $investor->id) {
                    continue;
                }

                if($investor->hasRole('leader') && !$investor->can_receive_referral_bonus) continue;

                $amount = bcmul(
                    $inv->capital,
                    bcdiv((string) $config->percent, '100', 8),
                    8
                );

                if (bccomp($amount, '0', 8) <= 0) {
                    continue;
                }

                if (ReferralBonus::where('bot_investment_id', $inv->id)
                    ->where('level', $level)
                    ->exists()) {
                    continue;
                }

                ReferralBonus::create([
                    'user_id' => $referrer->id,
                    'referred_by_id' => $referrer->id,
                    'from_user_id' => $investor->id,
                    'bot_investment_id' => $inv->id,
                    'level' => $level,
                    'percent' => $config->percent,
                    'amount' => $amount,
                    'status' => 'pending',
                    // 'lock_reason' => "system_lock",
                    'claimable_at' => now()->addDays($config->lock_days),
                    'calculated_for' => now()->startOfDay(),
                    'meta' => [
                        'bot_id' => $inv->bot->id,
                        'investment_amount' => $inv->capital,
                    ],
                ]);

                // send email
            }
        });

    }

    public static function distribute(User $investor, BotInvestment $inv): void {
        if ($investor->hasRole('leader') && !$investor->can_distribute_referral_bonus) return;

        $eligibleAmount = $inv->referral_eligible_amount ?? $inv->capital; // fallback for pre-migration rows
        if (bccomp((string) $eligibleAmount, '0', 8) <= 0) {
            return;
        }

        $levels = ReferralLevel::where('is_active', true)
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        if ($levels->isEmpty()) return;

        $referralTree = Referral::where('user_id', $investor->id)->first();
        if (!$referralTree) return;

        DB::transaction(function () use ($levels, $referralTree, $inv, $investor, $eligibleAmount) {
            foreach ($levels as $config) {
                $level = $config->level;
                if ($level > 10) break;

                $referrerUserId = $referralTree->{"level_{$level}_id"} ?? null;
                if (!$referrerUserId) continue;

                $referrer = User::find($referrerUserId);
                if (!$referrer) continue;
                if ($referrer->id === $investor->id) continue;
                if ($investor->hasRole('leader') && !$investor->can_receive_referral_bonus) continue;

                $amount = bcmul(
                    (string) $eligibleAmount,
                    bcdiv((string) $config->percent, '100', 8),
                    8
                );

                if (bccomp($amount, '0', 8) <= 0) continue;

                if (ReferralBonus::where('bot_investment_id', $inv->id)->where('level', $level)->exists()) {
                    continue;
                }

                ReferralBonus::create([
                    'user_id' => $referrer->id,
                    'referred_by_id' => $referrer->id,
                    'from_user_id' => $investor->id,
                    'bot_investment_id' => $inv->id,
                    'level' => $level,
                    'percent' => $config->percent,
                    'amount' => $amount,
                    'status' => 'pending',
                    'claimable_at' => now()->addDays($config->lock_days),
                    'calculated_for' => now()->startOfDay(),
                    'meta' => [
                        'bot_id' => $inv->bot->id,
                        'investment_amount' => $inv->capital,
                        'referral_eligible_amount' => $eligibleAmount,
                    ],
                ]);
            }
        });
    }

    
    public static function claimClaimable(): array {
        $claimedCount = 0;
        $failedCount = 0;
        $totalClaimed = '0.00000000';

        ReferralBonus::query()
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('claimable_at')
                    ->orWhere('claimable_at', '<=', now());
            })
            ->orderBy('id')
            ->chunkById(200, function ($bonuses) use (&$claimedCount, &$failedCount, &$totalClaimed) {
                foreach ($bonuses as $bonus) {
                    try {
                        $claimedAmount = DB::transaction(function () use ($bonus) {
                            // Re-fetch under lock so a concurrent run can't double-claim this row.
                            $locked = ReferralBonus::whereKey($bonus->id)->lockForUpdate()->first();

                            if (! $locked || ! $locked->isClaimable()) {
                                return null;
                            }

                            $user = $locked->user;
                            if (! $user) {
                                return null;
                            }

                            WalletService::credit(
                                $user,
                                (float) $locked->amount,
                                LedgerReference::ReferralBonus,
                                $locked->id,
                                "Referral bonus (level {$locked->level}) auto-claimed",
                                LedgerAsset::REFERRALBONUS
                            );

                            $locked->update([
                                'status' => 'claimed',
                                'claimed_at' => now(),
                            ]);

                            return $locked->amount;
                        });

                        if ($claimedAmount !== null) {
                            $claimedCount++;
                            $totalClaimed = bcadd($totalClaimed, (string) $claimedAmount, 8);
                        }
                    } catch (\Throwable $e) {
                        $failedCount++;

                        Log::error('Referral bonus auto-claim failed', [
                            'referral_bonus_id' => $bonus->id,
                            'user_id' => $bonus->user_id,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            });

        return [
            'claimed' => $claimedCount,
            'failed' => $failedCount,
            'total_amount' => $totalClaimed,
        ];
    }
}

