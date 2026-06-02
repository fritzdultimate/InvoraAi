<?php

namespace App\Services\Sprint;

use App\Models\ChallengeCategory;
use App\Models\ChallengeEntry;
use App\Models\User;
use App\Services\MilestoneCheckerService;

class LeaderboardEngineService
{
    public function calculate(ChallengeCategory $category) {
        $target = data_get($category->rewards, 'target', 10000);

        User::chunk(200, function ($users) use ($category, $target) {
            foreach ($users as $user) {
                $score = $this->resolveScore($user->id, $category);

                app(MilestoneCheckerService::class)->check($category->challenge, $score, $user, $score);

                $existing = ChallengeEntry::where([
                    'user_id' => $user->id,
                    'challenge_category_id' => $category->id,
                    'challenge_id' => $category->challenge_id,
                ])->first();

                ChallengeEntry::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'challenge_category_id' => $category->id,
                        'challenge_id' => $category->challenge_id,
                        // 'phase' => 1
                    ],
                    [
                        'score' => $score,
                        'completed_at' => $score >= $target && is_null($existing?->completed_at)
                                          ? now()
                                          : $existing?->completed_at
                    ]
                );
            }
        });

        $this->rank($category);
    }

    private function resolveScore($userId, $category) {
        $service = app(TeamVolumeService::class);

        $start = $category->challenge->start_at;
        $end = $category->challenge->end_at;

        return match ($category->type) {
            'personal_volume' => $service->getPersonalVolume($userId, $start, $end),
            default => 0
        };

    }

    private function rank($category) {
        $entries = ChallengeEntry::where([
            'challenge_category_id' => $category->id,
            // 'phase' => 1
        ])
            ->orderByDesc('score')
            ->get();

        foreach ($entries as $index => $entry) {
            $entry->update([
                'previous_rank' => $entry->rank,
                'rank' => $index + 1,
                'rank_change' => $entry->previous_rank ? $entry->previous_rank - ($index + 1) : 0
            ]);
        }
    }
}