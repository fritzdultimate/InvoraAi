<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotProfitCycle extends Model {
    public $guarded = [];

    public function investment() {
        return $this->belongsTo(BotInvestment::class);
    }
}
