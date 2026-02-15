<?php

namespace App\Models;

use App\Enums\BotLicenseStatus;
use Illuminate\Database\Eloquent\Model;

class BotLicense extends Model {
    protected $fillable = [
        'user_id',
        'bot_id',
        'starts_at',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => BotLicenseStatus::class,
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bot() {
        return $this->belongsTo(Bot::class);
    }

    public function isActive(): bool {
        return $this->status === BotLicenseStatus::ACTIVE
            && now()->lessThan($this->expires_at);
    }

}
