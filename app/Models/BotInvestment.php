<?php

namespace App\Models;

use App\Enums\BotInvestmentStatus;
use Illuminate\Database\Eloquent\Model;

class BotInvestment extends Model {
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
        'next_cycle_at'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'total_profit' => 'decimal:8',
        'started_at' => 'datetime',
        'matures_at' => 'datetime',
        'is_early_terminated' => 'boolean',
        'status' => BotInvestmentStatus::class,
    ];

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
}
