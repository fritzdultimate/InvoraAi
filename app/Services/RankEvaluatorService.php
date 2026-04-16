<?php
namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\DailyResidualBonus;
use App\Models\Rank;
use App\Models\RankBonus;
use App\Models\UnilevelPercentage;
use App\Models\User;
use App\Models\UserRank;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class RankEvaluatorService {
    public static function evaluate(User $user): void {
        if($user->hasRole('leader') && !$user->can_receive_rank_bonus) return;
        $volume = self::getTotalTeamVolume($user->id);
        $directReferralVolume = BotInvestment::whereIn('user_id', getDownlineUserIds($user->id, 1))->sum('amount');

        $currentLevel = $user->rank?->rank?->level ?? 0;

        $ranks = Rank::where('level', '>', $currentLevel)
            ->orderBy('level')
            ->get();

        DB::transaction(function () use ($user, $ranks, $volume, $directReferralVolume) {
            $isActive = true;//$user->isActive();
            foreach ($ranks as $rank) {
                
                $qualified =
                    $volume >= $rank->required_volume &&
                    $directReferralVolume >= $rank->direct_referrals_volume;

                if (!$qualified || !$isActive) {
                    return;
                }

            
                UserRank::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'rank_id' => $rank->id,
                        'achieved_at' => now(),
                    ]
                );

            
                RankBonus::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'rank_id' => $rank->id,
                    ],
                    [
                        'amount' => $rank->one_time_bonus,
                        'status' => 'locked',
                        // 'credited_at' => now(),
                    ]
                );

                // if ($rankBonus->wasRecentlyCreated) {
                //     WalletService::credit(
                //         $user,
                //         $rank->one_time_bonus,
                //         LedgerReference::RANKBONUS,
                //         $rank->id,
                //         'rank bonus credited',
                //         LedgerAsset::REFERRALBONUS
                //     );
                // }
            }
        });

    }

    public static function getTotalTeamVolume($userId) {
        $percentages = UnilevelPercentage::pluck('percentage', 'level');

        $total = 0;

        for ($level = 1; $level <= 10; $level++) {

            $ids = getDownlineUsersByLevel($userId, $level);


            if (empty($ids)) continue;

            $volume = BotInvestment::whereIn('user_id', $ids)->sum('amount');

            $percentage = $percentages[$level] ?? 0;

            $weighted = ($volume * $percentage) / 100;

            $total += $weighted;
        }

        return $total;
    }

    public static function distributeResidualBonus(User $user) {
        // if(!$user->isActive()) return;
        if($user->hasRole('leader') && !$user->can_receive_rank_residual_bonus) return;

        $bonus = $user->rank?->rank?->bonus ?? 0;

        if ($bonus <= 0) return;

        DB::transaction(function () use ($user, $bonus) {
            DailyResidualBonus::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'created_at' => now()->startOfDay(),
                ],
                [
                    'rank_id' => $user->rank->rank_id,
                    'amount' => $bonus,
                    // 'credited_at' => now(),
                    'status' => 'locked'
                ]
            );

            // if ($residualBonus->wasRecentlyCreated) {

            //     WalletService::credit(
            //         $user,
            //         $bonus,
            //         LedgerReference::RESIDUALBONUS,
            //         $residualBonus->id,
            //         'daily residual bonus credited',
            //         LedgerAsset::REFERRALBONUS
            //     );
            // }

        });
    }
}
