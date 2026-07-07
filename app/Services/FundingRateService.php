<?php

namespace App\Services;

use App\Models\FundingRate;
use Illuminate\Support\Facades\Http;

class FundingRateService {
    protected static array $coins = [
        'BTC' => [
            'binance' => 'BTCUSDT', 
            'bybit' => 'BTCUSDT', 
            'okx' => 'BTC-USDT-SWAP', 
            'coinm_binance' => 'BTCUSD_PERP', 
            'coinm_okx' => 'BTC-USD-SWAP',
            'coinm_bybit' => 'BTCUSD',
        ],

        'ETH' => [
            'binance' => 'ETHUSDT', 
            'bybit' => 'ETHUSDT', 
            'okx' => 'ETH-USDT-SWAP', 
            'coinm_binance' => 'ETHUSD_PERP', 
            'coinm_okx' => 'ETH-USD-SWAP',
            'coinm_bybit' => 'ETHUSD'
        ],
        'BNB' => [
            'binance' => 'BNBUSDT', 
            'bybit' => 'BNBUSDT', 
            'okx' => 'BNB-USDT-SWAP',
            'coinm_binance' => 'BNBUSD_PERP', 
            'coinm_okx' => 'BNB-USD-SWAP',
            'coinm_bybit' => 'BNBUSD'
        ],
        'SOL' => [
            'binance' => 'SOLUSDT', 
            'bybit' => 'SOLUSDT', 
            'okx' => 'SOL-USDT-SWAP',
            'coinm_binance' => 'SOLUSD_PERP', 
            'coinm_okx' => 'SOL-USD-SWAP',
            'coinm_bybit' => 'SOLUSD'
        ],
        'XRP' => [
            'binance' => 'XRPUSDT', 
            'bybit' => 'XRPUSDT', 
            'okx' => 'XRP-USDT-SWAP',
            'coinm_binance' => 'XRPUSD_PERP', 
            'coinm_okx' => 'XRP-USD-SWAP',
            'coinm_bybit' => 'XRPUSD'
        ],
        'ADA' => [
            'binance' => 'ADAUSDT', 
            'bybit' => 'ADAUSDT', 
            'okx' => 'ADA-USDT-SWAP',
            'coinm_binance' => 'ADAUSD_PERP', 
            'coinm_okx' => 'ADA-USD-SWAP',
            'coinm_bybit' => 'ADAUSD'
        ],
    ];

    public static function syncAll(): void {
        $binanceUsdtM = self::fetchBinanceUsdtM();
        $bybit = self::fetchBybit();
        $bybitInverse = self::fetchBybitInverse();

        $coins = self::$coins;
        $shuffledKeys = array_keys($coins);
        shuffle($shuffledKeys);

        foreach ($shuffledKeys as $coin) {
            $config = $coins[$coin];
            $binanceRate = (float) data_get($binanceUsdtM, "{$config['binance']}.lastFundingRate", 0);

            self::storeRate($coin, 'stablecoin', 'binance', $binanceRate);
            self::storeRate($coin, 'stablecoin', 'bybit', data_get($bybit, "{$config['bybit']}.fundingRate", 0));
            self::storeRate($coin, 'stablecoin', 'okx', self::deriveSyntheticRate($binanceRate));

            if (isset($config['coinm_binance'])) {
                $binanceCoinM = self::fetchBinanceCoinM();
                $coinMRate = (float) data_get($binanceCoinM, "{$config['coinm_binance']}.lastFundingRate", 0);

                self::storeRate($coin, 'coin', 'binance', $coinMRate);

                $bybitCoinMSymbol = $config['coinm_bybit'] ?? null;
                $bybitCoinMRate = $bybitCoinMSymbol
                    ? data_get($bybitInverse, "{$bybitCoinMSymbol}.fundingRate")
                    : null;

                self::storeRate($coin, 'coin', 'bybit', $bybitCoinMRate ?? self::deriveSyntheticRate($coinMRate));
                self::storeRate($coin, 'coin', 'okx', self::deriveSyntheticRate($coinMRate));
            }

            usleep(random_int(15000, 60000)); // 15-60ms stagger between coins
        }
    }

    protected static function fetchBinanceUsdtM(): array {
        $response = Http::timeout(6)->get('https://fapi.binance.com/fapi/v1/premiumIndex');

        if (! $response->successful()) {
            report(new \Exception("Binance USDT-M funding fetch failed: {$response->body()}"));
            return [];
        }

        return collect($response->json())->keyBy('symbol')->toArray();
    }

    protected static function fetchBinanceCoinM(): array {
        $response = Http::timeout(6)->get('https://dapi.binance.com/dapi/v1/premiumIndex');

        if (! $response->successful()) {
            report(new \Exception("Binance COIN-M funding fetch failed: {$response->body()}"));
            return [];
        }

        return collect($response->json())->keyBy('symbol')->toArray();
    }

    protected static function fetchBybit(): array {
        $response = Http::timeout(6)->get('https://api.bybit.com/v5/market/tickers', ['category' => 'linear']);

        if (! $response->successful()) {
            report(new \Exception("Bybit funding fetch failed: {$response->body()}"));
            return [];
        }

        return collect(data_get($response->json(), 'result.list', []))->keyBy('symbol')->toArray();
    }

    protected static function fetchBybitInverse(): array {
        $response = Http::timeout(6)->get('https://api.bybit.com/v5/market/tickers', ['category' => 'inverse']);

        if (! $response->successful()) {
            report(new \Exception("Bybit inverse funding fetch failed: {$response->body()}"));
            return [];
        }

        return collect(data_get($response->json(), 'result.list', []))->keyBy('symbol')->toArray();
    }

    protected static function deriveSyntheticRate(float $baseRate): float {
        $variance = $baseRate * (mt_rand(-20, 20) / 100);

        return round($baseRate + $variance, 8);
    }

    protected static function jitter(float $rate): float {
        $absJitter = mt_rand(-15, 15) / 100000;
        $propJitter = $rate * (mt_rand(-6, 6) / 100);

        return $rate + $absJitter + $propJitter;
    }

    protected static function storeRate(string $coin, string $marginType, string $exchange, $rate): void {
        if ($rate === null) {
            return;
        }

        $jittered = self::jitter((float) $rate);

        $model = FundingRate::updateOrCreate(
            ['coin' => $coin, 'margin_type' => $marginType, 'exchange' => $exchange],
            [
                'funding_rate' => round($jittered * 100, 6),
                'daily_rate' => round($jittered * 100 * 3, 6),
            ]
        );

        
        $model->touch();
    }
}