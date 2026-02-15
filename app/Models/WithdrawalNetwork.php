<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalNetwork extends Model {
    protected $guarded = [];

    public function currency() {
        return $this->belongsTo(WithdrawalCurrency::class, 'withdrawal_currency_id');
    }

    public function withdrawals() {
        return $this->hasMany(Withdrawal::class);
    }
}
