<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model {
    protected $fillable = [
        'user_id',
        'referred_by_id',
        'from_user_id',
        'level',
        'amount',
        'bot_investment_id',
        'status',
        'meta',
        'percent',
        'calculated_for',
        'claimed_at',
        'lock_reason',
        'claimable_at'
    ];
    protected $casts = [
        'meta' => 'array',
        'claimable_at' => 'datetime',
        'claimed_at' => 'datetime',
        'calculated_for' => 'datetime',
    ];


    public function fromUser() {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isClaimable(): bool {
        return $this->status === 'pending' && ($this->claimable_at === null || now()->gte($this->claimable_at));
    }

    public function remainingTime(): ?string {
        return $this->claimable_at && now()->lt($this->claimable_at)
            ? now()->diffForHumans($this->claimable_at, true)
            : null;
    }
}
