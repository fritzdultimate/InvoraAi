<?php

namespace App\Services;

use App\Models\Trade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TradeSimulatorService
{

    public function getLivePrices($asset)
    {
        $symbol = strtoupper($asset->symbol) . 'USDT';

        return Cache::remember("prices_{$symbol}", 5, function () use ($symbol) {
            $prices = [
                'binance' => null,
                'bybit' => null,
            ];

            try {
                // 🔹 Binance Price
                $binanceResponse = Http::get(
                    "https://api.binance.com/api/v3/ticker/price",
                    ['symbol' => $symbol]
                );

                if ($binanceResponse->successful()) {
                    $prices['binance'] = (float) $binanceResponse['price'];
                }

                // 🔹 Bybit Price
                $bybitResponse = Http::get(
                    "https://api.bybit.com/v5/market/tickers",
                    [
                        'category' => 'linear',
                        'symbol' => $symbol
                    ]
                );

                if ($bybitResponse->successful()) {
                    $list = $bybitResponse['result']['list'] ?? [];

                    if (!empty($list)) {
                        // use markPrice (more stable than lastPrice)
                        $prices['bybit'] = (float) $list[0]['markPrice'];
                    }
                }

            } catch (\Exception $e) {
                // optional: log error
                logger()->error('Price fetch error: ' . $e->getMessage());
            }

            return $prices;
        });
    }
    public function openTrade($asset, $funding) {
        if(!$funding) return;
        $existing = Trade::where('trading_asset_id', $asset->id)
            ->where('status', 'open')
            ->first();
        if ($existing)
            return;

        // dd($funding);

        $bybitRate = $funding['bybit'];
        $binanceRate = $funding['binance'];

        if ($bybitRate === null || $binanceRate === null) {
            return null;
        }


        if ($bybitRate > $binanceRate) {

            // Bybit funding is higher → shorts earn more there
            $shortExchange = 'Bybit';
            $longExchange = 'Binance';

            $shortFunding = $bybitRate;
            $longFunding = $binanceRate;

        } elseif ($binanceRate > $bybitRate) {

            // Binance funding is higher → shorts earn more there
            $shortExchange = 'Binance';
            $longExchange = 'Bybit';

            $shortFunding = $binanceRate;
            $longFunding = $bybitRate;

        } else {
            // equal → no edge
            return null;
        }


        $prices = $this->getLivePrices($asset);

        if (!$prices['binance'] || !$prices['bybit']) {
            return null;
        }

        $prices['binance'] += rand(-5, 5) / 100;
        $prices['bybit'] += rand(-5, 5) / 100;

        $longPrice = $prices[strtolower($longExchange)];
        $shortPrice = $prices[strtolower($shortExchange)];

        $capital = 10000;
        $fundingDiff = abs($bybitRate - $binanceRate);

        if ($fundingDiff > 0.005) {
            $risk = 0.15;
        } elseif ($fundingDiff > 0.002) {
            $risk = 0.10;
        } else {
            $risk = 0.05;
        }

        $positionSize = $capital * $risk;
        $feeRate = 0.000006;
        $fees = $positionSize * $feeRate;

        return Trade::create([
            'trading_asset_id' => $asset->id,

            'long_exchange' => $longExchange,
            'short_exchange' => $shortExchange,

            'position_size' => $positionSize,


            'entry_price_long' => $longPrice,
            'entry_price_short' => $shortPrice,

            'funding_rate_long' => $longFunding,
            'funding_rate_short' => $shortFunding,

            // 'funding_rate' => $this->getFundingRate(),
            'fees' => $fees,

            'opened_at' => now(),
        ]);
    }

    public function updateTrade($trade) {
        $prices = $this->getLivePrices($trade->asset);

        $exitLong = $prices[strtolower($trade->long_exchange)];
        $exitShort = $prices[strtolower($trade->short_exchange)];

        if (!$exitLong || !$exitShort) {
            return;
        }


        $pricePnL = ($exitLong - $trade->entry_price_long)
            + ($trade->entry_price_short - $exitShort);

        $lastFunding = $trade->last_funding_at ?? $trade->opened_at;

        // dd(now()->diffInMinutes($lastFunding, true));

        if (now()->diffInMinutes($lastFunding, true) >= 5) {

            $longFunding = $this->calculateFunding(
                $trade->position_size,
                $trade->funding_rate_long,
                'long'
            );

            $shortFunding = $this->calculateFunding(
                $trade->position_size,
                $trade->funding_rate_short,
                'short'
            );

            $fundingProfit = $trade->funding_profit + $longFunding + $shortFunding;

            $trade->last_funding_at = now();
            $trade->save();

        } else {
            $fundingProfit = $trade->funding_profit; // keep previous
        }

        $total = $pricePnL + $fundingProfit - $trade->fees;

        $trade->update([
            'exit_price_long' => $exitLong,
            'exit_price_short' => $exitShort,
            'price_pnl' => $pricePnL,
            'funding_profit' => $fundingProfit,
            'total_net' => $total,
        ]);

        // Auto close if threshold hit
        if ($total < -10 || $total > 20) {
            $trade->update([
                'status' => 'closed',
                'closed_at' => now()
            ]);
        }
    }

    public function calculateFunding($positionSize, $fundingRate, $side) {
        $amount = $positionSize * (abs($fundingRate) / 100);

        // Positive funding:
        // longs pay, shorts receive
        if ($fundingRate > 0) {

            if ($side === 'long') {
                return -$amount;
            }

            if ($side === 'short') {
                return +$amount;
            }
        }

        // Negative funding:
        // shorts pay, longs receive
        if ($fundingRate < 0) {

            if ($side === 'long') {
                return +$amount;
            }

            if ($side === 'short') {
                return -$amount;
            }
        }

        return 0;
    }
}