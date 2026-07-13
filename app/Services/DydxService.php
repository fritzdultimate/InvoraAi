<?php

namespace App\Services;

use App\Models\LiveTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DydxService {

    protected static array $markets = [
        'BTC-USD', 'ETH-USD', 'SOL-USD', 'ARB-USD', 'AVAX-USD', 'DOGE-USD',
    ];

    public static function syncAll(): void {
        // foreach (self::$markets as $market) {
        //     self::syncMarket($market);
        // }

        $responses = Http::pool(fn ($pool) => collect(self::$markets)
            ->map(fn ($market) => $pool->as($market)
                ->timeout(5)
                ->connectTimeout(3)
                ->get("https://indexer.dydx.trade/v4/trades/perpetualMarket/{$market}", ['limit' => 1])
            )
        );

        foreach (self::$markets as $market) {
            $response = $responses[$market];

            if (! $response instanceof \Illuminate\Http\Client\Response || ! $response->successful()) {
                report(new \Exception("dYdX sync failed for {$market}"));
                continue;
            }

            self::processMarketTrades($market, $response);
        }
    }

    public static function syncMarket(string $market): void {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->retry(1, 200)
                ->get("https://indexer.dydx.trade/v4/trades/perpetualMarket/{$market}", [
                    'limit' => 1,
                ]);
        } catch(\Illuminate\Http\Client\ConnectionException $e) {
            report(new \Exception("dYdX connection failed for {$market}: {$e->getMessage()}"));
            return;
        }

        if (! $response->successful()) {
            report(new \Exception("dYdX sync failed for {$market}: {$response->body()}"));
            return;
        }

        $trades = data_get($response->json(), 'trades', []);
        $coin = str($market)->before('-USD')->toString();

        foreach ($trades as $row) {
            $id = data_get($row, 'id');
            $side = data_get($row, 'side'); // expect BUY | SELL
            $size = data_get($row, 'size');
            $price = data_get($row, 'price');
            $createdAt = data_get($row, 'createdAt');

            if (! $id || ! $side || ! $size || ! $price || ! $createdAt) {
                continue;
            }

            $amountUsd = (float) $size * (float) $price;

            LiveTrade::updateOrCreate(
                [
                    'tx_hash' => (string) $id,
                    'log_index' => 0,
                ],
                [
                    'network' => 'dydx',
                    'coin' => $coin,
                    'protocol' => 'dydx_v4',
                    'dex' => 'dYdX',
                    'pair' => "{$coin}-PERP/USDC",
                    'base_symbol' => $coin,
                    'quote_symbol' => 'USDC',
                    'side' => strtolower($side) === 'buy' ? 'buy' : 'sell',
                    'price' => $price,
                    'price_usd' => $price,
                    'amount' => $size,
                    'amount_usd' => $amountUsd,
                    'block_time' => Carbon::parse($createdAt),
                ]
            );
        }
    }

    protected static function processMarketTrades(string $market, $response): void {
        $trades = data_get($response->json(), 'trades', []);
        $coin = str($market)->before('-USD')->toString();

        foreach ($trades as $row) {
            $id = data_get($row, 'id');
            $side = data_get($row, 'side'); // expect BUY | SELL
            $size = data_get($row, 'size');
            $price = data_get($row, 'price');
            $createdAt = data_get($row, 'createdAt');

            if (! $id || ! $side || ! $size || ! $price || ! $createdAt) {
                continue;
            }

            $amountUsd = (float) $size * (float) $price;

            LiveTrade::updateOrCreate(
                [
                    'tx_hash' => (string) $id,
                    'log_index' => 0,
                ],
                [
                    'network' => 'dydx',
                    'coin' => $coin,
                    'protocol' => 'dydx_v4',
                    'dex' => 'dYdX',
                    'pair' => "{$coin}-PERP/USDC",
                    'base_symbol' => $coin,
                    'quote_symbol' => 'USDC',
                    'side' => strtolower($side) === 'buy' ? 'buy' : 'sell',
                    'price' => $price,
                    'price_usd' => $price,
                    'amount' => $size,
                    'amount_usd' => $amountUsd,
                    'block_time' => Carbon::parse($createdAt),
                ]
            );
        }
    }
}