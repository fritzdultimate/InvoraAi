<?php

namespace App\Livewire\Dashboard;

use App\Models\BotLicense;
use App\Models\RoiPromo;
use Livewire\Component;

class RoiPromoBanner extends Component
{
    public ?int $promoId = null;
    public bool $hidden = false;

    public function mount() {
        $promo = RoiPromo::live()
            ->where('show_banner', true)
            ->orderByDesc('multiplier')
            ->orderBy('ends_at')
            ->first();

        $promo ??= RoiPromo::where('is_active', true)
            ->where('show_banner', true)
            ->where('show_before_start', true)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->first();

        $this->promoId = $promo?->id;
        $this->hidden = $promo && session()->has($this->sessionKey());
    }

    public function dismiss() {
        if (! $this->promoId) return;

        session()->put($this->sessionKey(), true);
        $this->hidden = true;
    }

    private function sessionKey(): string {
        return 'roi_promo_hidden_' . $this->promoId;
    }

    public function render() {
        $promo = $this->promoId ? RoiPromo::find($this->promoId) : null;

        if (! $promo || $this->hidden) {
            return view('livewire.dashboard.roi-promo-banner', ['promo' => null]);
        }

        $mine = $promo->investments()->where('user_id', auth()->id());

        // send people with a matching license straight to deploy, everyone else to buy one
        $hasLicense = BotLicense::where('user_id', auth()->id())
            ->whereIn('bot_id', $promo->bot_ids ?? [])
            ->where('expires_at', '>', now())
            ->exists();

        return view('livewire.dashboard.roi-promo-banner', [
            'promo' => $promo,
            'live' => $promo->isLive(),
            'myPositions' => (clone $mine)->count(),
            'myExtra' => (float) (clone $mine)->sum('roi_boost_earned'),
            'spotsLeft' => $promo->spotsLeft(),
            'ctaUrl' => $hasLicense ? route('investments.create') : route('bot'),
            'ctaLabel' => $promo->cta_label ?: ($hasLicense ? 'Deploy now' : 'Get the license'),
        ]);
    }
}
