<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model {
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'min_amount',
        'max_amount',
        'lock_days',
        'license_duration_days',
        'daily_return_percent',
        'payout_interval_hours',
        'early_withdrawal_penalty_percent',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_amount' => 'decimal:8',
        'max_amount' => 'decimal:8',
        'daily_return_percent' => 'decimal:2',
        'early_withdrawal_penalty_percent' => 'decimal:2',
    ];

    public function licenses() {
        return $this->hasMany(BotLicense::class);
    }

    public function investments() {
        return $this->hasMany(BotInvestment::class);
    }

    public function profitCycles() {
        return $this->hasManyThrough(
            BotProfitCycle::class,
            BotInvestment::class,
            'bot_id',
            'bot_investment_id',
            'id',
            'id'
        );
    }
}
