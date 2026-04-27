<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveTrade extends Model {
    protected $fillable = [
        'tx_hash',
        'protocol',
        'buy_amount',
        'buy_symbol',
        'buy_price_usd',
        'sell_amount',
        'sell_symbol',
        'sell_price_usd',
        'block_time',
    ];
}
 