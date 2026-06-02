<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\MilestoneAlert;
use App\Models\User;

class MilestoneCheckerService {
    const MILESTONES = [2500, 5000, 7500, 10000];

    /**
     * Call this after recalculating volume for a challenge.
     * Pass the challenge, the team's total volume, and optionally
     * a specific user whose personal volume changed.
     */
    public function check(Challenge $challenge, float $teamVolume, ?User $triggeringUser = null, float $personalVolume = 0): void {
        // --- Team milestones: alert ALL participants ---
        $this->checkAndAlert(
            challenge: $challenge,
            volume: $teamVolume,
            type: 'team',
            users: $this->getChallengeParticipants($challenge),
        );

        // --- Personal milestones: alert only the individual ---
        if ($triggeringUser && $personalVolume > 0) {
            $this->checkAndAlert(
                challenge: $challenge,
                volume: $personalVolume,
                type: 'personal',
                users: collect([$triggeringUser]),
            );
        }
    }

    private function checkAndAlert(Challenge $challenge, float $volume, string $type, $users): void {
        foreach (self::MILESTONES as $milestone) {
            if ($volume < $milestone) continue;

            foreach ($users as $user) {
                // Insert only if not already recorded (unique constraint protects this)
                MilestoneAlert::firstOrCreate([
                    'user_id'          => $user->id,
                    'challenge_id'     => $challenge->id,
                    'milestone_amount' => $volume,
                    'type'             => $type,
                ]);
            }
        }
    }

    private function getChallengeParticipants(Challenge $challenge)
    {
        // Adjust this query to match your challenge_entries relationship
        return User::whereHas('challengeEntries', function ($q) use ($challenge) {
            $q->where('challenge_id', $challenge->id);
        })->get();
    }
}