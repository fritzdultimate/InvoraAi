<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotTermination extends Model {
    use SoftDeletes;
    protected $fillable = [
        'bot_investment_id',
        'penalty_percent',
        'penalty_amount',
        'amount_returned',
        'terminated_at'
    ];
}
