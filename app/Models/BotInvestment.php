<?php

namespace App\Models;

use App\Enums\BotInvestmentStatus;
use App\Enums\LedgerAsset;
use App\Services\Wallet\WalletService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BotInvestment extends Model {
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'bot_id',
        'bot_license_id',
        'amount',
        'capital',
        'total_profit',
        'started_at',
        'matures_at',
        'status',
        'is_early_terminated',
        'next_cycle_at',
        'uuid',
        'code',
        'referral_eligible_amount',
        'or'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'referral_eligible_amount' => 'decimal:8',
        'total_profit' => 'decimal:8',
        'started_at' => 'datetime',
        'matures_at' => 'datetime',
        'is_early_terminated' => 'boolean',
        'status' => BotInvestmentStatus::class,
        'or' => 'boolean',
        'roi_multiplier' => 'decimal:2',
        'roi_boost_ends_at' => 'datetime',
        'roi_boost_earned' => 'decimal:8',
        'roi_first_notified_at' => 'datetime',
    ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->code = 'INV-' . strtoupper(Str::random(7));
        });
    }

    public function termination() {
        return $this->hasOne(BotTermination::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bot() {
        return $this->belongsTo(Bot::class);
    }

    public function cycles() {
        return $this->hasMany(BotProfitCycle::class);
    }

    public function isMatured(): bool {
        return now()->greaterThanOrEqualTo($this->matures_at);
    }

    public function roiPromo() {
        return $this->belongsTo(RoiPromo::class);
    }

    public function isRoiBoosted(): bool {
        return $this->roi_promo_id
            && (! $this->roi_boost_ends_at || now()->lt($this->roi_boost_ends_at));
    }

    public function botLicense() {
        return $this->belongsTo(BotLicense::class);
    }

    /**
     * Investments the user can actually compound right now: running, not
     * matured, and with at least $1 of profit. Profit is capped at the
     * user's profit wallet, same as the investment page does.
     */
    public static function compoundableFor(User $user) {
        $walletProfit = (float) WalletService::getBalance($user, LedgerAsset::PROFIT->value);

        $investments = static::with('bot')
            ->where('user_id', $user->id)
            ->where('status', BotInvestmentStatus::ACTIVE->value)
            ->where('matures_at', '>', now())
            ->get()
            ->map(function ($inv) use ($walletProfit) {
                $inv->available_profit = min((float) $inv->total_profit, $walletProfit);
                $inv->roi = $inv->amount > 0 ? ($inv->total_profit / $inv->amount) * 100 : 0;
                $inv->is_best = false;

                return $inv;
            })
            ->filter(fn ($inv) => $inv->available_profit >= 1)
            ->sortByDesc('available_profit')
            ->values();

        // only recommend one when there's a real choice
        if ($investments->count() > 1) {
            $investments->first()->is_best = true;
        }

        return $investments;
    }

    // What the user should see, not just the raw status column
    public function displayStatus(): string {
        return match ($this->status) {
            BotInvestmentStatus::TERMINATIONREQUEST => 'pending_termination',
            BotInvestmentStatus::COMPLETED => 'completed',
            BotInvestmentStatus::TERMINATED => 'terminated',
            default => match (true) {
                $this->isMatured() => 'maturing',
                ! $this->botLicense || ! $this->botLicense->isActive() => 'paused',
                default => 'running',
            },
        };
    }

    public function statusLabel(): string {
        return match ($this->displayStatus()) {
            'running' => 'Running',
            'paused' => 'Paused – license expired',
            'maturing' => 'Maturing',
            'pending_termination' => 'Termination pending',
            'completed' => 'Completed',
            'terminated' => 'Terminated',
        };
    }

    public function statusBadgeClass(): string {
        return match ($this->displayStatus()) {
            'running' => 'invora-badge-green',
            'paused', 'pending_termination' => 'invora-badge-amber',
            'maturing' => 'invora-badge-blue',
            'terminated' => 'invora-badge-red',
            default => '',
        };
    }
}
