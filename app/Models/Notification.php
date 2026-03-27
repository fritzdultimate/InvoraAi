<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'is_critical' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function scopeCritical($query) {
        return $query->where('is_critical', true);
    }

    public function users() {
        return $this->belongsToMany(User::class)
            ->withPivot('read_at', 'dismissed_at')
            ->withTimestamps();
    }
}
