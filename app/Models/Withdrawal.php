<?php

namespace App\Models;

use App\Enums\WithdrawalStatus;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model {
    public $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'status' => WithdrawalStatus::class
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function currency() {
        return $this->belongsTo(WithdrawalCurrency::class, 'withdrawal_currency_id');
    }

    public function network() {
        return $this->belongsTo(WithdrawalNetwork::class, 'withdrawal_network_id');
    }
}
