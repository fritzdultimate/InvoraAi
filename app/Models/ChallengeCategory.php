<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeCategory extends Model {
    public $fillable = [
        'challenge_id',
        'phase',
        'type',
        'rewards',
        'prize_pool',
        'min_activation_amount'
    ];

    protected $casts = [
        'rewards' => 'array'
    ];

    public function challenge() {
        return $this->belongsTo(Challenge::class);
    }

    public function challengeEntries() {
        return $this->hasMany(ChallengeEntry::class);
    }

}
