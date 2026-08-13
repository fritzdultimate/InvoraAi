<?php

namespace App\Models;

use App\Enums\BotInvestmentStatus;
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
        'locked_until',
        'meta'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'total_profit' => 'decimal:8',
        'started_at' => 'datetime',
        'matures_at' => 'datetime',
        'is_early_terminated' => 'boolean',
        'status' => BotInvestmentStatus::class,
        'meta' => 'array',
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

    public function botLicense() {
        return $this->belongsTo(BotLicense::class);
    }
}
