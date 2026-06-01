<?php

namespace App\Services\Sprint;

use App\Models\Deposit;
use App\Models\Referral;

class TeamVolumeService {

    public function getPersonalVolume($userId, $start, $end) {
        $personal = Deposit::where('user_id', $userId)
            ->where('status', 'finished')
            ->whereBetween('created_at', [$start, $end])
            ->sum('actually_paid');

        // Direct referral deposits (level 1 only)
        $directReferralIds = Referral::where('referred_by_id', $userId)
            ->pluck('user_id');

        $directReferrals = 0;

        if ($directReferralIds->isNotEmpty()) {
            $directReferrals = Deposit::whereIn('user_id', $directReferralIds)
                ->where('status', 'finished')
                ->whereBetween('created_at', [$start, $end])
                ->sum('actually_paid');
        }

        return (float) ($personal + $directReferrals);
    }
}