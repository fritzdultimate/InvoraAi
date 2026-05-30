<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {
    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'is_active',
        'phase'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function categories() {
        return $this->hasMany(ChallengeCategory::class);
    }
}
