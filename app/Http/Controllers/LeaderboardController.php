<?php

namespace App\Http\Controllers;
use App\Models\ChallengeCategory;
use App\Services\Sprint\LeaderboardEngineService;


class LeaderboardController extends Controller {

    public function leaderBoardEntry() {
        $categories = ChallengeCategory::where('phase', 1)->whereHas('challenge', function ($q) {
            $q->where('is_active', true);
        })->get();

        foreach ($categories as $category) {

            app(LeaderboardEngineService::class)
                ->calculate($category);
        }

        
    }
}
