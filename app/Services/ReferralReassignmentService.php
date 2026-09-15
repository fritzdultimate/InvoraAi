<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Lets an admin retroactively set an existing user as the downline
 * (referral) of another existing user, without editing the database
 * directly.
 *
 * Background: `referrals.level_1_id` is the real, live tree edge (a
 * user's direct sponsor) — it's what getDownlineUserIds()/getDownlineUsersByLevel()
 * walk. `level_2_id`..`level_10_id` are a denormalized SNAPSHOT of a
 * user's ancestor chain, copied once at Referral-row-creation time
 * (see ReferralCreationService::createFor()) and read directly by
 * ReferralBonusService (bonus payouts) and RankEvaluatorService (team
 * volume) for O(1) ancestor lookups.
 *
 * Re-parenting an existing user therefore isn't a single-field update:
 * if that user already has downlines of their own, their entire
 * subtree's ancestor snapshot goes stale unless it's cascaded down.
 * This service does that cascade. It never touches historical
 * ReferralBonus rows — those already-paid-out amounts are left alone.
 */
class ReferralReassignmentService
{
    /**
     * Gather info about a user's current referral position, used to
     * warn an admin before reassigning them to a new upline.
     */
    public static function inspect(User $user): array
    {
        $referral = Referral::where('user_id', $user->id)->first();

        $currentUplineId = $user->referrer_id ?: $referral?->referred_by_id;
        $currentUpline = $currentUplineId ? User::find($currentUplineId) : null;

        $downlineIds = getDownlineUserIds($user->id, 50);

        return [
            'has_upline' => (bool) $currentUpline,
            'current_upline' => $currentUpline,
            'downline_count' => count($downlineIds),
        ];
    }

    /**
     * Would making $newUpline the sponsor of $user create a circular
     * referral chain (i.e. is $newUpline already downstream of $user)?
     */
    public static function wouldCreateCycle(User $user, User $newUpline): bool
    {
        if ($user->id === $newUpline->id) {
            return true;
        }

        return in_array($newUpline->id, getDownlineUserIds($user->id, 50), true);
    }

    /**
     * Re-parent an existing user under a new sponsor.
     *
     * - Updates users.referrer_id (used for display, e.g. the "Referrer"
     *   column in the admin Users table).
     * - Rebuilds the user's own Referral row (referred_by_id + level_1_id
     *   .. level_10_id), mirroring ReferralCreationService::createFor().
     * - Cascades the new ancestor chain down through every EXISTING
     *   descendant of the user, so ReferralBonusService and
     *   RankEvaluatorService keep reading correct ancestor data for the
     *   whole subtree going forward.
     *
     * Historical ReferralBonus rows are never altered.
     *
     * @throws InvalidArgumentException if this would create a self- or
     *         circular reference.
     */
    public static function reassign(User $user, User $newUpline): Referral
    {
        if (self::wouldCreateCycle($user, $newUpline)) {
            throw new InvalidArgumentException(
                "Cannot assign — {$newUpline->name} is already downstream of {$user->name}. This would create a circular referral chain."
            );
        }

        return DB::transaction(function () use ($user, $newUpline) {
            $user->update(['referrer_id' => $newUpline->id]);

            $newUplineReferral = Referral::where('user_id', $newUpline->id)->first();

            $data = [
                'user_id' => $user->id,
                'referred_by_id' => $newUpline->id,
                'level_1_id' => $newUpline->id,
            ];

            for ($i = 2; $i <= 10; $i++) {
                $prev = 'level_' . ($i - 1) . '_id';
                $data['level_' . $i . '_id'] = $newUplineReferral?->$prev;
            }

            $referral = Referral::updateOrCreate(['user_id' => $user->id], $data);

            self::recomputeDescendants($referral);

            return $referral;
        });
    }

    /**
     * Walk down the existing downline tree (by direct-sponsor /
     * level_1_id links) and recompute each descendant's level_2_id..
     * level_10_id snapshot from its (possibly just-updated) parent row.
     * Direct sponsorship below the reassigned user never changes — only
     * the inherited ancestor chain above it does.
     */
    protected static function recomputeDescendants(Referral $parent, int $depth = 0): void
    {
        if ($depth > 50) {
            return; // safety valve, should never be hit
        }

        $children = Referral::where('referred_by_id', $parent->user_id)->get();

        foreach ($children as $child) {
            for ($i = 2; $i <= 10; $i++) {
                $prevKey = 'level_' . ($i - 1) . '_id';
                $child->{'level_' . $i . '_id'} = $parent->{$prevKey};
            }

            $child->save();

            self::recomputeDescendants($child, $depth + 1);
        }
    }
}
