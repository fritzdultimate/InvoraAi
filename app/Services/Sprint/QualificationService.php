<?php

namespace App\Services\Sprint;

use App\Models\Challenge;
use App\Models\Qualification;
use App\Models\User;

class QualificationService {
    public function check(User $user, Challenge $challenge) {
        $service = app(TeamVolumeService::class);

        $teamVolume = $service->getTotalTeamVolume($user->id, $challenge->start_at, $challenge->end_at);
        $personal = $service->getPersonalVolume($user->id, $challenge->start_at, $challenge->end_at);

        if ($personal >= 50000) {
            $this->qualify($user, $challenge, 'strategic_capital');
        }

        if ($teamVolume >= 150000) {
            $this->qualify($user, $challenge, 'network_builder');
        }
    }

    private function qualify($user, $challenge, $type) {
        Qualification::updateOrCreate([
            'user_id' => $user->id,
            'type' => $type
        ], [
            'qualified' => true,
            'qualified_at' => now()
        ]);
    }
}