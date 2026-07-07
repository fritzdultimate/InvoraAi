<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundingRate extends Model {
    protected $fillable = [
        'coin', 'margin_type', 'exchange', 'funding_rate', 'daily_rate',
    ];

    protected $casts = [
        'funding_rate' => 'decimal:6',
        'daily_rate' => 'decimal:6',
    ];
}