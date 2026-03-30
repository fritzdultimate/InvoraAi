<?php
namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\Rank;
use App\Models\RankBonus;
use App\Models\UnilevelPercentage;
use App\Models\User;
use App\Models\UserRank;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class RankEvaluatorService {
    public static function evaluate(User $user): void {
        $volume = self::getTotalTeamVolume($user->id);
        $directReferralVolume = BotInvestment::whereIn('user_id', getDownlineUserIds($user->id, 1))->sum('amount');

        $currentLevel = $user->rank?->rank?->level ?? 0;

        $ranks = Rank::where('level', '>', $currentLevel)
            ->orderBy('level')
            ->get();

        DB::transaction(function () use ($user, $ranks, $volume, $directReferralVolume) {
            $isActive = $user->isActive();
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

            
                $rankBonus = RankBonus::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'rank_id' => $rank->id,
                    ],
                    [
                        'amount' => $rank->one_time_bonus,
                        'status' => 'credited',
                        'credited_at' => now(),
                    ]
                );

                if ($rankBonus->wasRecentlyCreated) {
                    WalletService::credit(
                        $user,
                        $rank->one_time_bonus,
                        LedgerReference::RANKBONUS,
                        $rank->id,
                        'rank bonus credited',
                        LedgerAsset::REFERRALBONUS
                    );
                }
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
}
