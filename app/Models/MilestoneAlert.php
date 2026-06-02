<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneAlert extends Model {
    protected $fillable = [
        'user_id',
        'challenge_id',
        'milestone_amount',
        'type',
        'dismissed_at',
    ];

    protected $casts = [
        'dismissed_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo {
        return $this->belongsTo(Challenge::class);
    }

    public function dismiss(): void {
        $this->update(['dismissed_at' => now()]);
    }

    public function scopeUndismissed($query) {
        return $query->whereNull('dismissed_at');
    }
}
