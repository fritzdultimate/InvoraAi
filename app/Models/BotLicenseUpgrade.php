<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotLicenseUpgrade extends Model {
    protected $fillable = [
        'bot_license_id',
        'user_id',
        'from_bot_id',
        'to_bot_id',
        'price_paid',
        'status'
    ];

    public function license() {
        return $this->belongsTo(BotLicense::class, 'bot_license_id');
    }

    public function fromBot() {
        return $this->belongsTo(Bot::class, 'from_bot_id');
    }

    public function toBot() {
        return $this->belongsTo(Bot::class, 'to_bot_id');
    }
}
