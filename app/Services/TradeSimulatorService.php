<?php

namespace App\Services;

use App\Models\Trade;

class TradeSimulatorService {
    public function openTrade($asset, $funding) {
        $existing = Trade::where('trading_asset_id', $asset->id)
            ->where('status', 'open')
            ->first();
        if($existing) return;
        
        $bybitRate = $funding['bybit'];
        $binanceRate = $funding['binance'];

        // dd($bybitRate, $binanceRate);

        if ($bybitRate > 0 && $binanceRate < 0) {
            // BEST CASE
            $longExchange = 'Binance';
            $shortExchange = 'Bybit';

            $longFunding = $binanceRate;
            $shortFunding = $bybitRate;

        } elseif ($bybitRate < 0 && $binanceRate > 0) {

            $longExchange = 'Bybit';
            $shortExchange = 'Binance';

            $longFunding = $bybitRate;
            $shortFunding = $binanceRate;

        } else {
            // skip bad trades
            return null;
        }

        $basePrice = rand(20000, 70000);

        // simulate exchange price difference
        $longPrice = $basePrice + rand(-30, 30);
        $shortPrice = $basePrice + rand(-30, 30);
        
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
        $feeRate = 0.0006;
        $fees = $positionSize * $longPrice * $feeRate;

        return Trade::create([
            'trading_asset_id' => $asset->id,

            'long_exchange' => $longExchange,
            'short_exchange' => $shortExchange,

            'position_size' => rand(500, 2000),

            
            'entry_price_long' => $longPrice,
            'entry_price_short' => $shortPrice,

            'funding_rate_long' => $longFunding,
            'funding_rate_short' => $shortFunding,

            'funding_rate' => $this->getFundingRate(),
            'fees' => $fees,

            'opened_at' => now(),
        ]);
    }

    public function updateTrade($trade) {
        $priceMove = rand(-100, 100);

        $exitLong = $trade->entry_price_long + $priceMove;
        $exitShort = $trade->entry_price_short - $priceMove;

        $pricePnL = ($exitLong - $trade->entry_price_long)
                  + ($trade->entry_price_short - $exitShort);

        $longFundingProfit =
            $trade->position_size
            * $trade->entry_price_long
            * $trade->funding_rate_long;

        $shortFundingProfit =
            $trade->position_size
            * $trade->entry_price_short
            * $trade->funding_rate_short;

        // net funding (what you receive minus what you pay)
        $fundingProfit = $longFundingProfit - $shortFundingProfit;

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

    private function getFundingRate() {
        return rand(-10, 10) / 10000; // -0.001 to 0.001
    }
}