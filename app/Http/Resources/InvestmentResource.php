<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $start = $this->started_at;
        $end = $this->matures_at;
        $progress = 0;

        if ($start && $end && $end->gt($start)) {
            $progress = min(1, max(0, $start->diffInSeconds(now()) / $start->diffInSeconds($end)));
        }

        return [
            'id' => $this->uuid,
            'code' => $this->code,
            'bot' => $this->bot?->name,
            'amount' => (float) $this->amount,
            'total_profit' => (float) $this->total_profit,
            'daily_return_percent' => (float) ($this->meta['daily_return_percent'] ?? $this->bot?->daily_return_percent ?? 0),
            'status' => $this->status?->value,
            'roi_boosted' => $this->isRoiBoosted(),
            'roi_multiplier' => $this->roi_multiplier ? (float) $this->roi_multiplier : null,
            'progress' => round($progress, 4),
            'started_at' => $start?->toIso8601String(),
            'matures_at' => $end?->toIso8601String(),
            'next_cycle_at' => $this->next_cycle_at ? \Carbon\Carbon::parse($this->next_cycle_at)->toIso8601String() : null,
            'cycles' => $this->whenLoaded('cycles', fn () => $this->cycles->map(fn ($c) => [
                'amount' => (float) $c->profit_amount,
                'percent' => (float) $c->percent,
                'at' => \Carbon\Carbon::parse($c->cycle_at)->toIso8601String(),
            ])),
        ];
    }
}
