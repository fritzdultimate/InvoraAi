<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\ReferralLevel;
use App\Models\User;
use App\Models\Stake;
use App\Models\ReferralReward;
use Illuminate\Support\Facades\DB;

class ReferralBonusService {
    public static function distribute(User $staker, Stake $stake): void {
        $levels = ReferralLevel::where('is_active', true)
            ->orderBy('level')
            ->get()
            ->keyBy('level');

        if ($levels->isEmpty()) {
            return;
        }

        if($staker->is_leader) {
            return;
        }

        $referralTree = Referral::where('user_id', $staker->id)->first();

        if (! $referralTree) {
            return;
        }

        DB::transaction(function () use ($levels, $referralTree, $stake, $staker) {
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

                if ($referrer->id === $staker->id) {
                    continue;
                }

                $amount = bcmul(
                    $stake->amount,
                    bcdiv((string) $config->percent_bps, '10000', 8),
                    8
                );

                if (bccomp($amount, '0', 8) <= 0) {
                    continue;
                }

                if (ReferralReward::where('stake_id', $stake->id)
                    ->where('level', $level)
                    ->exists()) {
                    continue;
                }

                // if (ReferralReward::where('from_user_id', $staker->id)
                //     ->exists()) {
                //     continue;
                // }

                ReferralReward::create([
                    'user_id' => $referrer->id,
                    'from_user_id' => $staker->id,
                    'stake_id' => $stake->id,
                    'level' => $level,
                    'percent_bps' => $config->percent_bps,
                    'amount' => $amount,
                    'status' => 'pending',
                    'lock_reason' => "system_lock",
                    'claimable_at' => now()->addDays($stake->plan->duration),
                    'calculated_for' => now()->startOfDay(),
                    'meta' => [
                        'plan_id' => $stake->plan_id,
                        'stake_amount' => $stake->amount,
                    ],
                ]);

                // send email
            }
        });

    }
}

