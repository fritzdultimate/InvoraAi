<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RoiPromo extends Model {
    protected $fillable = [
        'name',
        'is_active',
        'starts_at',
        'ends_at',
        'multiplier',
        'boost_duration',
        'boost_days',
        'bot_ids',
        'audience',
        'require_kyc',
        'require_new_license',
        'min_amount',
        'max_amount',
        'max_per_user',
        'max_entries',
        'show_banner',
        'show_before_start',
        'banner_theme',
        'headline',
        'description',
        'cta_label',
        'show_countdown',
        'show_spots',
        'dismissible',
        'email_mode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'multiplier' => 'decimal:2',
        'bot_ids' => 'array',
        'require_kyc' => 'boolean',
        'require_new_license' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'show_banner' => 'boolean',
        'show_before_start' => 'boolean',
        'show_countdown' => 'boolean',
        'show_spots' => 'boolean',
        'dismissible' => 'boolean',
    ];

    public function investments() {
        return $this->hasMany(BotInvestment::class);
    }

    public function scopeLive(Builder $query): Builder {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now());
    }

    public function isLive(): bool {
        return $this->is_active && now()->between($this->starts_at, $this->ends_at);
    }

    public function isUpcoming(): bool {
        return $this->is_active && now()->lt($this->starts_at);
    }

    public function status(): string {
        if (! $this->is_active) return 'draft';
        if ($this->isUpcoming()) return 'scheduled';
        if ($this->isLive()) return 'live';

        return 'ended';
    }

    public function bots() {
        return Bot::whereIn('id', $this->bot_ids ?? [])->get();
    }

    public function appliesToBot(int $botId): bool {
        return in_array($botId, array_map('intval', $this->bot_ids ?? []), true);
    }

    public function botNames(): string {
        $names = $this->bots()->pluck('name');

        if ($names->count() <= 1) {
            return $names->first() ?? 'Selected bots';
        }

        return $names->slice(0, -1)->implode(', ') . ' & ' . $names->last();
    }

    public function multiplierLabel(): string {
        return rtrim(rtrim(number_format((float) $this->multiplier, 2), '0'), '.') . '×';
    }

    public function spotsTaken(): int {
        return $this->investments()->count();
    }

    public function spotsLeft(): ?int {
        if (! $this->max_entries) return null;

        return max(0, $this->max_entries - $this->spotsTaken());
    }

    public function headlineText(): string {
        return $this->headline ?: $this->botNames() . ' returns are now ' . $this->multiplierLabel();
    }

    public function descriptionText(): string {
        if ($this->description) return $this->description;

        $text = 'Deploy capital into ' . $this->botNames() . ' before the promo closes and every payout on that position runs at '
            . $this->multiplierLabel() . ' the normal return';

        $text .= $this->boost_duration === 'days' && $this->boost_days
            ? ' for ' . $this->boost_days . ' days.'
            : ' until it matures.';

        return $text . ' Existing positions are not affected.';
    }
}
