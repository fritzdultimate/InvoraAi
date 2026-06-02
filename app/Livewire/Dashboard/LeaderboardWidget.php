<?php

namespace App\Livewire\Dashboard;

use App\Http\Controllers\LeaderboardController;
use App\Models\Challenge;
use App\Models\ChallengeCategory;
use App\Models\ChallengeEntry;
use App\Models\Deposit;
use Carbon\Carbon;
use Livewire\Component;

class LeaderboardWidget extends Component
{
    public ?Challenge $challenge = null;
    public ?ChallengeCategory $category = null;

    /** Top 3 winners */
    public array $topEntries = [];

    /** Authenticated user's own entry */
    public ?array $myEntry = null;

    /** Authenticated user's current PV */
    public float $myPV = 0;

    /** PV target from the category rewards config */
    public float $pvTarget = 10000;

    /** Whether the challenge is still running */
    public bool $isActive = false;

    /** ISO-8601 end date string, consumed by the JS countdown */
    public string $endsAt = '';

    /** Total number of participants (entries with score > 0) */
    public int $totalParticipants = 0;
    public ?array $myOutsideTop = null;

    public function mount(): void {
        try {
            app(LeaderboardController::class)->leaderBoardEntry();
        } catch (\Throwable $e) {
            logger()->error('Leaderboard cron failed: ' . $e->getMessage());
        }

        $this->loadChallenge();
    }

    // ─── Public actions ───────────────────────────────────────────────

    /** Called by Livewire polling or manually to refresh data */
    public function refresh(): void {
        $this->loadChallenge();
    }

    // ─── Private helpers ──────────────────────────────────────────────

    private function loadChallenge(): void {
        // Grab the first active challenge that has a personal_volume category
        $this->challenge = Challenge::where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();

        if (! $this->challenge) {
            return;
        }

        $this->isActive = true;
        $this->endsAt   = $this->challenge->end_at->toIso8601String();

        // Find the personal_volume category for this challenge
        $this->category = ChallengeCategory::where('challenge_id', $this->challenge->id)
            ->where('type', 'personal_volume')
            ->first();

        if (! $this->category) {
            return;
        }

        // Pull PV target from rewards JSON  e.g. {"target": 10000, "prizes": [...]}
        $rewards        = $this->category->rewards;
        $this->pvTarget = data_get($rewards, 'target', 10000);

        // Top 3 ranked entries with user relationship
        $this->topEntries = ChallengeEntry::with('user:id,name')
            ->where('challenge_category_id', $this->category->id)
            ->whereNotNull('rank')
            ->orderByRaw('CASE WHEN completed_at IS NOT NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('rank', 'asc')
            ->limit(10)
            ->get()
            ->map(fn ($e) => [
                'rank'       => $e->rank,
                'name'       => $e->user->name ?? 'Unknown',
                'avatar'     => $e->user->profile_photo_path,
                'score'      => (float) $e->score,
                'rank_change'=> (int) $e->rank_change,
                'completed'  => ! is_null($e->completed_at),
                'is_me'       => $e->user_id === auth()->id(),
            ])
            ->toArray();

        $myInTop = collect($this->topEntries)->contains('is_me', true);

        if (! $myInTop) {
            $myRow = ChallengeEntry::with('user:id,name')
                ->where('challenge_category_id', $this->category->id)
                ->where('user_id', auth()->id())
                ->first();

            if ($myRow) {
                $this->myOutsideTop = [
                    'rank'        => $myRow->rank,
                    'name'        => $myRow->user->name ?? 'Unknown',
                    'avatar'      => $myRow->user->profile_photo_path,
                    'score'       => (float) $myRow->score,
                    'rank_change' => (int) $myRow->rank_change,
                    'completed'   => ! is_null($myRow->completed_at),
                    'is_me'       => true,
                ];
            }
        }

        // Authenticated user data
        $userId = auth()->id();

        $this->myPV = Deposit::where('user_id', $userId)
            ->where('status', 'finished')
            ->whereBetween('created_at', [
                $this->challenge->start_at,
                $this->challenge->end_at,
            ])
            ->sum('actually_paid');

        $myEntry = ChallengeEntry::where('challenge_category_id', $this->category->id)
            ->where('user_id', $userId)
            ->first();

        if ($myEntry) {
            $this->myEntry = [
                'rank'        => $myEntry->rank,
                'score'       => (float) $myEntry->score,
                'rank_change' => (int) $myEntry->rank_change,
            ];
        }

        $this->totalParticipants = ChallengeEntry::where('challenge_category_id', $this->category->id)
            // ->where('score', '>', 0)
            ->count();
    }

    // ─── Computed helpers exposed to the view ────────────────────────

    public function getProgressPercentage(): float {
        if ($this->pvTarget <= 0) return 0;
        return min(100, round(($this->myPV / $this->pvTarget) * 100, 1));
    }

    public function getRemainingPV(): float {
        return max(0, $this->pvTarget - $this->myPV);
    }

    public function getPrizeForRank(int $rank): ?array {
        if (! $this->category) return null;
        $prizes = data_get($this->category->rewards, 'prizes', []);
        return $prizes[$rank - 1] ?? null;
    }

    public function render(): \Illuminate\View\View {
        return view('livewire.dashboard.leaderboard-widget', [
            'progressPct'  => $this->getProgressPercentage(),
            'remainingPV'  => $this->getRemainingPV(),
            'prizes'       => data_get($this->category?->rewards, 'prizes', [
                ['cash' => 2500, 'label' => '1st Place'],
                ['cash' => 1500, 'label' => '2nd Place'],
                ['cash' => 1000, 'label' => '3rd Place'],
            ]),
        ]);
    }
}