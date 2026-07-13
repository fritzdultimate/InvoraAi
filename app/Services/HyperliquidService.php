<?php

namespace App\Services;

use App\Models\LiveTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HyperliquidService {

    protected static array $coins = [
        'BTC', 'ETH', 'SOL', 'ARB', 'AVAX', 'DOGE', 'LINK', 'OP',
    ];

    public static function syncAll(): void {
        foreach (self::$coins as $coin) {
            self::syncCoin($coin);
        }
    }

    public static function syncCoin(string $coin): void {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.hyperliquid.xyz/info', [
            'type' => 'recentTrades',
            'coin' => $coin,
        ]);

        if (! $response->successful()) {
            dd('error');
            report(new \Exception("Hyperliquid sync failed for {$coin}: {$response->body()}"));
            return;
        }


        $trades = collect($response->json())
            ->sortByDesc('time')
            ->take(1);

        // dd($trades);

        foreach ($trades as $row) {
            $tid = data_get($row, 'tid');
            $px = data_get($row, 'px');
            $sz = data_get($row, 'sz');
            $side = data_get($row, 'side'); // 'B' = buy, 'A' = sell
            $time = data_get($row, 'time');
            $hash = data_get($row, 'hash');

            if (! $tid || ! $px || ! $sz || ! $side || ! $time) {
                continue;
            }

            $amountUsd = (float) $px * (float) $sz;

            LiveTrade::updateOrCreate(
                [
                    'tx_hash' => $hash ?? (string) $tid,
                    'log_index' => 0,
                ],
                [
                    'network' => 'hyperliquid',
                    'coin' => $coin, // new column — filter/replace 'network' pills with this
                    'protocol' => 'hyperliquid_perp',
                    'dex' => 'Hyperliquid',
                    'pair' => "{$coin}-PERP/USDC",
                    'base_symbol' => $coin,
                    'quote_symbol' => 'USDC',
                    'side' => $side === 'B' ? 'buy' : 'sell',
                    'price' => $px,
                    'price_usd' => $px, // Hyperliquid perps are USDC-quoted already
                    'amount' => $sz,
                    'amount_usd' => $amountUsd,
                    'block_time' => Carbon::createFromTimestampMs($time),
                ]
            );
        }
    }
}