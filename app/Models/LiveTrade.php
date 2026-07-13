<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveTrade extends Model {
    protected $fillable = [
        'tx_hash', 'log_index', 'network', 'protocol', 'dex', 'pair',
        'base_symbol', 'quote_symbol', 'side',
        'price', 'price_usd', 'amount', 'amount_usd',
        'buy_amount', 'buy_symbol', 'buy_price_usd',
        'sell_amount', 'sell_symbol', 'sell_price_usd',
        'block_time',
    ];

    protected $casts = [
        'block_time' => 'datetime',
        'price' => 'decimal:18',
        'price_usd' => 'decimal:8',
        'amount' => 'decimal:18',
        'amount_usd' => 'decimal:2',
    ];

    public function getExplorerUrlAttribute(): string|null {
        return match ($this->network) {
            'hyperliquid' => "https://app.hyperliquid.xyz/explorer/tx/{$this->tx_hash}",
            'eth' => "https://etherscan.io/tx/{$this->tx_hash}",
            'bsc' => "https://bscscan.com/tx/{$this->tx_hash}",
            'arbitrum' => "https://arbiscan.io/tx/{$this->tx_hash}",
            'gmx' => "https://arbiscan.io/tx/{$this->tx_hash}",
            default => null,
        };
    }

    public function getExplorerLabelAttribute(): string|null {
        return match ($this->network) {
            'hyperliquid' => 'Hyperliquid',
            'eth' => 'Etherscan',
            'bsc' => 'BscScan',
            'arbitrum' => 'Arbiscan',
            'gmx' => 'GMX',
            default => null,
        };
    }
}
 