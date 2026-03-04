<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model {
    protected $fillable = [
        'user_id',
        'referred_by_id',
        'level_1_id',
        'level_2_id',
        'level_3_id',
        'level_4_id',
        'level_5_id',
        'level_6_id',
        'level_7_id',
        'level_8_id',
        'level_9_id',
        'level_10_id',
        'total_direct_referrals',
        'total_downlines',
        'total_earnings',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Direct referrer
    public function referrer(): BelongsTo {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function level1(): BelongsTo {
        return $this->belongsTo(User::class, 'level_1_id');
    }

    public function level2(): BelongsTo {
        return $this->belongsTo(User::class, 'level_2_id');
    }

    public function level3(): BelongsTo {
        return $this->belongsTo(User::class, 'level_3_id');
    }

    public function level4(): BelongsTo {
        return $this->belongsTo(User::class, 'level_4_id');
    }

    public function level5(): BelongsTo {
        return $this->belongsTo(User::class, 'level_5_id');
    }

    public function level6(): BelongsTo {
        return $this->belongsTo(User::class, 'level_6_id');
    }

    public function level7(): BelongsTo {
        return $this->belongsTo(User::class, 'level_7_id');
    }

    public function level8(): BelongsTo {
        return $this->belongsTo(User::class, 'level_8_id');
    }

    public function level9(): BelongsTo {
        return $this->belongsTo(User::class, 'level_9_id');
    }

    public function level10(): BelongsTo {
        return $this->belongsTo(User::class, 'level_10_id');
    }

    
    // Users directly referred by this user
    public function directReferrals(): HasMany {
        return $this->hasMany(Referral::class, 'referred_by_id', 'user_id');
    }
}
