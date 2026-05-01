<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model {
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_funding_at' => 'datetime'
    ];

    public function asset() {
        return $this->belongsTo(TradingAsset::class, 'trading_asset_id');
    }
}
