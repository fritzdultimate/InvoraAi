<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingAsset extends Model {
    protected $guarded = [];

    public function trades() {
        return $this->hasMany(Trade::class);
    }
}
