<?php

namespace App\Services\Sprint;

use App\Models\Challenge;
use App\Models\LeadershipMilestone;
use App\Models\User;

class MilestoneService {
    public function check(User $user, Challenge $challenge) {
        $volume = app(TeamVolumeService::class)
            ->getTotalTeamVolume($user->id, $challenge->start_at, $challenge->end_at);

        $levels = [
            'bronze' => 20000,
            'silver' => 50000,
            'gold' => 75000,
            'diamond' => 100000,
        ];

        foreach ($levels as $level => $amount) {
            if ($volume >= $amount) {
                LeadershipMilestone::firstOrCreate([
                    'user_id' => $user->id,
                    'level' => $level,
                ], [
                    'volume' => $volume,
                    'achieved_at' => now()
                ]);
            }
        }
    }
}