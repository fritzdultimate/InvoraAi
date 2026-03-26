<?php

namespace App\Services;

use App\Models\BotInvestment;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\ReferralLevel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralBonusService {
    public static function distribute(User $investor, BotInvestment $inv): void {
        $levels = ReferralLevel::where('is_active', true)
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        if ($levels->isEmpty()) {
            return;
        }

        if($investor->is_leader) {
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
}

