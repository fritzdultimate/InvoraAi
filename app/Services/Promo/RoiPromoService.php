<?php

namespace App\Services\Promo;

use App\Mail\RoiBoostRewardMail;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\RoiPromo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RoiPromoService {
    // Live promos for a bot, best return first
    public static function liveForBot(int $botId) {
        return RoiPromo::live()
            ->orderByDesc('multiplier')
            ->get()
            ->filter(fn ($promo) => $promo->appliesToBot($botId))
            ->values();
    }

    // Returns why this user can't join, or null when they can
    public static function blockReason(RoiPromo $promo, User $user, BotLicense $license, $amount): ?string {
        if (! $promo->isLive()) {
            return 'This promo is not running right now.';
        }

        if (! $promo->appliesToBot($license->bot_id)) {
            return 'This bot is not part of the promo.';
        }

        if ($promo->audience === 'new' && $user->created_at->lt($promo->starts_at)) {
            return 'This promo is for accounts created during the promo.';
        }

        if ($promo->audience === 'existing' && $user->created_at->gte($promo->starts_at)) {
            return 'This promo is for accounts created before the promo started.';
        }

        if ($promo->require_kyc && $user->kyc_status !== 'approved') {
            return 'Complete KYC verification to join this promo.';
        }

        if ($promo->require_new_license && $license->starts_at->lt($promo->starts_at)) {
            return 'Only licenses bought during the promo qualify.';
        }

        if ($promo->min_amount && $amount < (float) $promo->min_amount) {
            return 'Deploy at least $' . number_format($promo->min_amount, 2) . ' to qualify.';
        }

        if ($promo->max_amount && $amount > (float) $promo->max_amount) {
            return 'Deploy at most $' . number_format($promo->max_amount, 2) . ' to qualify.';
        }

        if ($promo->max_per_user && $promo->investments()->where('user_id', $user->id)->count() >= $promo->max_per_user) {
            return 'You have used all your boosted positions for this promo.';
        }

        if ($promo->max_entries && $promo->spotsTaken() >= $promo->max_entries) {
            return 'All boosted spots have been taken.';
        }

        return null;
    }

    // The promo this deployment would get, if any
    public static function matchFor(User $user, BotLicense $license, $amount): ?RoiPromo {
        foreach (self::liveForBot($license->bot_id) as $promo) {
            if (self::blockReason($promo, $user, $license, (float) $amount) === null) {
                return $promo;
            }
        }

        return null;
    }

    // Called right after a new investment is created
    public static function tag(BotInvestment $investment): ?RoiPromo {
        $license = $investment->botLicense;
        $user = $investment->user;

        if (! $license || ! $user) return null;

        foreach (self::liveForBot($investment->bot_id) as $promo) {
            $tagged = DB::transaction(function () use ($promo, $investment, $user, $license) {
                // lock the promo row so two deployments can't grab the last spot together
                $promo = RoiPromo::lockForUpdate()->find($promo->id);

                if (self::blockReason($promo, $user, $license, (float) $investment->amount) !== null) {
                    return null;
                }

                $endsAt = $promo->boost_duration === 'days' && $promo->boost_days
                    ? $investment->started_at->copy()->addDays($promo->boost_days)
                    : null;

                $investment->forceFill([
                    'roi_promo_id' => $promo->id,
                    'roi_multiplier' => $promo->multiplier,
                    'roi_boost_ends_at' => $endsAt,
                ])->save();

                return $promo;
            });

            if ($tagged) return $tagged;
        }

        return null;
    }

    // Multiplier to apply on this payout (1 when not boosted)
    public static function multiplierFor(BotInvestment $investment): string {
        if (! $investment->roi_promo_id || ! $investment->roi_multiplier) {
            return '1';
        }

        if ($investment->roi_boost_ends_at && now()->gte($investment->roi_boost_ends_at)) {
            return '1';
        }

        return (string) $investment->roi_multiplier;
    }

    public static function sendRewardEmail(BotInvestment $investment, string $profit, string $extra): void {
        $promo = $investment->roiPromo;

        if (! $promo || $promo->email_mode === 'off') return;

        $isFirst = $investment->roi_first_notified_at === null;

        if ($promo->email_mode === 'first' && ! $isFirst) return;

        try {
            Mail::to($investment->user->email)->send(
                new RoiBoostRewardMail($investment, $promo, $profit, $extra, $isFirst)
            );

            if ($isFirst) {
                $investment->forceFill(['roi_first_notified_at' => now()])->saveQuietly();
            }
        } catch (\Throwable $e) {
            Log::warning('ROI boost email failed', [
                'investment_id' => $investment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
