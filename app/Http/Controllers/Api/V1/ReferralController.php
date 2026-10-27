<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BotInvestment;
use App\Models\Deposit;
use App\Models\Rank;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\User;
use App\Services\RankEvaluatorService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('rank.rank');
        $teamIds = getDownlineUserIds($user->id);

        $teamVolume = (float) RankEvaluatorService::getTotalTeamVolume($user->id);
        $directVolume = (float) BotInvestment::whereIn('user_id', getDownlineUserIds($user->id, 1))->sum('amount');

        $currentRank = $user->rank?->rank;
        $nextRank = Rank::where('level', '>', $currentRank?->level ?? 0)->orderBy('level')->first();

        $progress = 100;
        if ($nextRank && $nextRank->required_volume > 0) {
            $progress = round(min($teamVolume / $nextRank->required_volume * 100, 100), 2);
        }

        return response()->json([
            'referral_code' => $user->affiliate_code,
            'referral_link' => url('/register?ref=' . $user->affiliate_code),
            'stats' => [
                'direct' => Referral::where('referred_by_id', $user->id)->count(),
                'team' => count(getDownlineUserIds($user->id, 7)),
                'team_volume' => $teamVolume,
                'direct_volume' => $directVolume,
                'team_deposits' => empty($teamIds) ? 0 : (float) Deposit::whereIn('user_id', $teamIds)
                    ->where('actually_paid', '>', 0)
                    ->sum('amount'),
                'total_earned' => (float) ReferralBonus::where('user_id', $user->id)
                    ->where('status', 'claimed')
                    ->sum('amount'),
            ],
            'rank' => [
                'current' => $currentRank ? ['name' => $currentRank->name, 'level' => $currentRank->level] : null,
                'next' => $nextRank ? [
                    'name' => $nextRank->name,
                    'level' => $nextRank->level,
                    'required_volume' => (float) $nextRank->required_volume,
                    'bonus' => (float) $nextRank->one_time_bonus,
                ] : null,
                'progress' => $progress,
            ],
        ]);
    }

    public function direct(Request $request)
    {
        $referrals = Referral::where('referred_by_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        $users = User::whereIn('id', $referrals->pluck('user_id'))
            ->withSum('botInvestments as invested', 'amount')
            ->get()
            ->keyBy('id');

        $data = $referrals->getCollection()->map(function ($ref) use ($users) {
            $u = $users->get($ref->user_id);

            return [
                'name' => $u ? trim($u->firstname . ' ' . $u->lastname) : 'Unknown',
                'username' => $u?->name,
                'invested' => (float) ($u?->invested ?? 0),
                'joined_at' => $u?->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => ['current_page' => $referrals->currentPage(), 'last_page' => $referrals->lastPage()],
        ]);
    }

    public function bonuses(Request $request)
    {
        $bonuses = ReferralBonus::with('fromUser')
            ->where('user_id', $request->user()->id)
            ->where('status', 'claimed')
            ->latest()
            ->paginate(20);

        $data = $bonuses->getCollection()->map(fn ($b) => [
            'id' => $b->id,
            'amount' => (float) $b->amount,
            'level' => (int) $b->level,
            'percent' => (float) $b->percent,
            'from' => $b->fromUser?->name,
            'created_at' => $b->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'data' => $data,
            'meta' => ['current_page' => $bonuses->currentPage(), 'last_page' => $bonuses->lastPage()],
        ]);
    }
}
