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

    // Global notifications plus the ones sent to this user
    public function scopeVisibleTo($query, $user) {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('notifications.user_id')->orWhere('notifications.user_id', $user->id);
        });
    }

    public function scopeCritical($query) {
        return $query->where('is_critical', true);
    }

    public function users() {
        return $this->belongsToMany(User::class)
            ->withPivot('read_at', 'dismissed_at')
            ->withTimestamps();
    }
}
