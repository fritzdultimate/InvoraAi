<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\User;

class ReferralCreationService {
    public static function createFor(User $user, ?User $referrer): Referral {
        $data = [
            'user_id' => $user->id,
            'referred_by_id' => $referrer?->id,
        ];

        if ($referrer) {
            $parentReferral = Referral::where('user_id', $referrer->id)->first();

            $data['level_1_id'] = $referrer->id;

            for ($i = 2; $i <= 10; $i++) {
                $prev = 'level_' . ($i - 1) . '_id';
                $curr = 'level_' . $i . '_id';

                $data[$curr] = $parentReferral?->$prev;
            }
        }

        return Referral::create($data);
    }
}
