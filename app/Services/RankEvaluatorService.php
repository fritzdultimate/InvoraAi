<?php
namespace App\Services;

use App\Models\Rank;
use App\Models\RankBonus;
use App\Models\Referral;
use App\Models\ReferralReward;
use App\Models\Stake;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRank;

class RankEvaluatorService {
    public static function evaluate(User $user): void {
        $volume = Stake::whereIn('user_id', getDownlineUserIds($user->id))->sum('amount');

        $activeReferrals = ReferralReward::where('user_id', $user->id)
            ->whereHas('fromUser', fn($q) => $q->where('is_suspended', false))
            ->distinct('from_user_id')
            ->count('from_user_id');

        $earnings = ReferralReward::where('user_id', $user->id)
            ->sum('amount');

        $currentLevel = $user->rank?->rank?->level;

        $eligibleRank = Rank::orderBy('level')
            ->get()
            ->last(fn($rank) =>
                $volume >= $rank->required_volume &&
                $activeReferrals >= $rank->required_active_referrals &&
                $earnings >= $rank->required_earnings
            );

        if (!$eligibleRank) return;

        if($currentLevel >= $eligibleRank->level) return;

        $bonusAmount = $eligibleRank->bonus;

        UserRank::updateOrCreate(
            [ 'user_id' => $user->id ],
            [
                'rank_id' => $eligibleRank->id,
                'achieved_at' => now(),
            ]
        );

        $bonus = RankBonus::firstOrCreate(
            [
                'user_id' => $user->id,
                'rank_id' => $eligibleRank->id,
            ],
            [
                'amount' => $bonusAmount,
                'status' => 'credited',
                'credited_at' => now(),
            ]
        );

        if($bonus) {
            Transaction::create([
                'related_type' => RankBonus::class,
                'related_id' => $bonus->id,
                'amount' => $bonusAmount,
                'type' => 'rank_bonus',
                'user_id' => $user->id
            ]);
        }

    }

    protected function qualifies(User $user, Rank $rank): bool {
        $volume = Stake::where('user_id', $user->id)->sum('amount');

        $directs = Referral::where('level_1_id', $user->id)->count();

        $earnings = $user->performance_bonus ?? 0;

        return
            $volume >= $rank->required_volume &&
            $directs >= $rank->required_active_referrals &&
            $earnings >= $rank->required_earnings;
    }
}
