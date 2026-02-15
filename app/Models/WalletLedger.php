<?php

namespace App\Models;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use Illuminate\Database\Eloquent\Model;

class WalletLedger extends Model {
    protected $fillable = [
        'user_id',
        'credit',
        'debit',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'asset'
    ];

    protected $casts = [
        'credit' => 'decimal:8',
        'debit' => 'decimal:8',
        'balance_after' => 'decimal:8',
        'asset' => LedgerAsset::class,
        'reference_type' => LedgerReference::class
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
