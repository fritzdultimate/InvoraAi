<?php

namespace App\Services\Sprint;

use App\Models\Deposit;

class TeamVolumeService {

    public function getPersonalVolume($userId, $start, $end) {
        return Deposit::where('user_id', $userId)
            ->where('status', 'finished')
            ->whereBetween('created_at', [$start, $end])
            ->sum('actually_paid');
    }
}