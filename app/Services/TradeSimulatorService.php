<?php

namespace App\Services;

use App\Models\Trade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TradeSimulatorService {
    // Funding happens at: 00:00, 08:00, 16:00 UTC
    const FUNDING_HOURS = [0, 8, 16];
    
    // Open trades 15-30 mins before funding
    const MIN_MINUTES_BEFORE_FUNDING = 15;
    const MAX_MINUTES_BEFORE_FUNDING = 30;

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
                $binanceResponse = Http::timeout(5)->get(
                    "https://api.binance.com/api/v3/ticker/price",
                    ['symbol' => $symbol]
                );

                if ($binanceResponse->successful()) {
                    $prices['binance'] = (float) $binanceResponse['price'];
                }

                // 🔹 Bybit Price
                $bybitResponse = Http::timeout(5)->get(
                    "https://api.bybit.com/v5/market/tickers",
                    [
                        'category' => 'linear',
                        'symbol' => $symbol
                    ]
                );

                if ($bybitResponse->successful()) {
                    $list = $bybitResponse['result']['list'] ?? [];

                    if (!empty($list)) {
                        $prices['bybit'] = (float) $list[0]['markPrice'];
                    }
                }

            } catch (\Exception $e) {
                logger()->error('Price fetch error: ' . $e->getMessage());
            }

            return $prices;
        });
    }

    /**
     * Check if current time is in the optimal window to open a trade
     * (15-30 minutes before funding time)
     */
    public function isOptimalEntryWindow(): bool {
        $now = now()->timezone('UTC');
        $currentMinute = $now->minute;
        $currentHour = $now->hour;

        foreach (self::FUNDING_HOURS as $fundingHour) {
            // Calculate minutes until next funding
            $minutesUntilFunding = $this->getMinutesUntilFunding($fundingHour, $currentHour, $currentMinute);

            // Check if we're in the 15-30 minute window before funding
            if ($minutesUntilFunding >= self::MIN_MINUTES_BEFORE_FUNDING && 
                $minutesUntilFunding <= self::MAX_MINUTES_BEFORE_FUNDING) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get minutes until the next funding event
     */
    private function getMinutesUntilFunding(int $fundingHour, int $currentHour, int $currentMinute): int {
        if ($currentHour < $fundingHour) {
            return (($fundingHour - $currentHour) * 60) - $currentMinute;
        } elseif ($currentHour == $fundingHour) {
            return 60 - $currentMinute;
        }
        return -1; // Past this funding hour
    }

    /**
     * Get the next funding timestamp
     */
    public function getNextFundingTime(): Carbon {
        $now = now()->timezone('UTC');
        $currentHour = $now->hour;

        // Find next funding hour
        $nextFundingHour = null;
        foreach (self::FUNDING_HOURS as $hour) {
            if ($hour > $currentHour) {
                $nextFundingHour = $hour;
                break;
            }
        }

        // If no funding hour found today, use first one tomorrow
        if ($nextFundingHour === null) {
            return $now->copy()->addDay()->setTime(self::FUNDING_HOURS[0], 0, 0);
        }

        return $now->copy()->setTime($nextFundingHour, 0, 0);
    }

    /**
     * Calculate dynamic capital based on funding rate difference
     * Higher funding spread = more capital deployed
     */
    public function calculateDynamicCapital(float $fundingSpread): float
    {
        // Base capital range: $20,000 - $100,000
        $minCapital = 20000;
        $maxCapital = 100000;

        // Funding spread thresholds
        if ($fundingSpread >= 0.01) {
            // >1% spread: Deploy max capital
            return $maxCapital;
        } elseif ($fundingSpread >= 0.005) {
            // 0.5-1% spread: Deploy 70-90% capital
            return $minCapital + (($maxCapital - $minCapital) * rand(70, 90) / 100);
        } elseif ($fundingSpread >= 0.003) {
            // 0.3-0.5% spread: Deploy 50-70% capital
            return $minCapital + (($maxCapital - $minCapital) * rand(50, 70) / 100);
        } elseif ($fundingSpread >= 0.001) {
            // 0.1-0.3% spread: Deploy 30-50% capital
            return $minCapital + (($maxCapital - $minCapital) * rand(30, 50) / 100);
        } else {
            // <0.1% spread: Min capital or skip
            return $minCapital * ((rand(0, 10)/10)/2); // Only deploy $10k for very low spreads
        }
    }

    public function openTrade($asset, $funding) {
        if (!$funding) return null;

        // Check if we're in optimal entry window (15-30 mins before funding)
        if (!$this->isOptimalEntryWindow()) {
            return null;
        }

        // Check if trade already exists for this asset
        $existing = Trade::where('trading_asset_id', $asset->id)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return null;
        }

        $bybitRate = $funding['bybit'];
        $binanceRate = $funding['binance'];

        if ($bybitRate === null || $binanceRate === null) {
            return null;
        }

        // Calculate funding spread
        $fundingSpread = abs($bybitRate - $binanceRate);

        // Skip if spread is too low (<0.05% = 5 basis points)
        if ($fundingSpread < 0.0005) {
            return null;
        }

        // Determine which exchange to long and which to short
        if ($bybitRate > $binanceRate) {
            $shortExchange = 'Bybit';
            $longExchange = 'Binance';
            $shortFunding = $bybitRate;
            $longFunding = $binanceRate;
        } elseif ($binanceRate > $bybitRate) {
            $shortExchange = 'Binance';
            $longExchange = 'Bybit';
            $shortFunding = $binanceRate;
            $longFunding = $bybitRate;
        } else {
            return null;
        }

        // Get live prices
        $prices = $this->getLivePrices($asset);

        if (!$prices['binance'] || !$prices['bybit']) {
            return null;
        }

        // Add minor price variation for realism
        $prices['binance'] += (rand(-10, 10) / 10000); // ±0.1%
        $prices['bybit'] += (rand(-10, 10) / 10000);

        $longPrice = $prices[strtolower($longExchange)];
        $shortPrice = $prices[strtolower($shortExchange)];

        // Dynamic capital allocation based on funding spread
        $capital = $this->calculateDynamicCapital($fundingSpread);

        // Position sizing with risk management
        if ($fundingSpread >= 0.008) {
            $leverage = rand(8, 10); // High spread = aggressive
        } elseif ($fundingSpread >= 0.005) {
            $leverage = rand(5, 7);
        } elseif ($fundingSpread >= 0.003) {
            $leverage = rand(3, 5);
        } else {
            $leverage = rand(2, 3); // Low spread = conservative
        }

        $positionSize = $capital * $leverage;

        // Calculate fees (taker fee: 0.06% per side = 0.12% total round trip)
        $feeRate = 0.0006; // 0.06% per side
        $fees = $positionSize * $feeRate * 2; // Open + close on both sides

        return Trade::create([
            'trading_asset_id' => $asset->id,

            'long_exchange' => $longExchange,
            'short_exchange' => $shortExchange,

            'position_size' => $positionSize,

            'entry_price_long' => $longPrice,
            'entry_price_short' => $shortPrice,

            'funding_rate_long' => $longFunding,
            'funding_rate_short' => $shortFunding,

            'fees' => $fees,

            'opened_at' => now(),
            'next_funding_at' => $this->getNextFundingTime(),
        ]);
    }

    public function updateTrade($trade) {
        // Get current prices
        $prices = $this->getLivePrices($trade->asset);

        $exitLong = $prices[strtolower($trade->long_exchange)];
        $exitShort = $prices[strtolower($trade->short_exchange)];

        if (!$exitLong || !$exitShort) {
            return;
        }

        // Check spread - close if too wide (arbitrage breaking down)
        $spread = abs(
            ($exitLong - $exitShort) / (($exitLong + $exitShort) / 2)
        );

        // Calculate price PnL
        $longPnL = (($exitLong - $trade->entry_price_long) / $trade->entry_price_long) * ($trade->position_size / 2);
        $shortPnL = (($trade->entry_price_short - $exitShort) / $trade->entry_price_short) * ($trade->position_size / 2);
        $pricePnL = $longPnL + $shortPnL;

        // Add minor randomness for realism
        $pricePnL += mt_rand(-50, 50) / 100;

        // Calculate funding profit
        $fundingProfit = $this->calculateAccumulatedFunding($trade);

        // Calculate total PnL
        $total = $pricePnL + $fundingProfit - $trade->fees;

        // Update peak profit for trailing stop
        if ($total > $trade->peak_profit) {
            $trade->update(['peak_profit' => $total]);
        }

        // Update trade metrics
        $trade->update([
            'exit_price_long' => $exitLong,
            'exit_price_short' => $exitShort,
            'price_pnl' => $pricePnL,
            'funding_profit' => $fundingProfit,
            'total_net' => $total,
        ]);

        // Determine if we should close the trade
        $shouldClose = $this->shouldCloseTrade($trade, $spread);

        if ($shouldClose) {
            $trade->update([
                'status' => 'closed',
                'closed_at' => now()
            ]);
        }
    }

    /**
     * Calculate accumulated funding profit
     */
    private function calculateAccumulatedFunding($trade): float {
        $now = now()->timezone('UTC');
        $openedAt = $trade->opened_at->timezone('UTC');
        
        // Calculate how many 8-hour funding periods have passed
        $hoursOpen = $now->diffInHours($openedAt);
        $fundingsPassed = floor($hoursOpen / 8);

        if ($fundingsPassed < 1) {
            return 0; // No funding collected yet
        }

        // Calculate funding per period
        $fundingRateDiff = abs($trade->funding_rate_short - $trade->funding_rate_long);
        $fundingPerPeriod = ($trade->position_size / 2) * $fundingRateDiff;

        // Total funding collected
        $totalFunding = $fundingPerPeriod * $fundingsPassed;

        // Add some variance (±10%)
        $variance = $totalFunding * (rand(-10, 10) / 100);
        
        return $totalFunding + $variance;
    }

    /**
     * Determine if trade should be closed based on multiple conditions
     */
    private function shouldCloseTrade($trade, float $spread): bool {
        $total = $trade->total_net;
        $positionSize = $trade->position_size;
        $now = now()->timezone('UTC');

        // 1. Stop Loss: -2% of position size
        $stopLoss = $positionSize * 0.02;
        if ($total <= -$stopLoss) {
            return true;
        }

        // 2. Take Profit: +3% of position size
        $takeProfit = $positionSize * 0.03;
        if ($total >= $takeProfit) {
            return true;
        }

        // 3. Trailing Stop: If profit > $100, protect 40% of gains
        $drawdown = $trade->peak_profit - $total;
        $trailStop = $trade->peak_profit * 0.40;
        if ($trade->peak_profit > 100 && $drawdown >= $trailStop) {
            return true;
        }

        // 4. Spread Breakout: Close if spread > 2% (arbitrage broken)
        if ($spread > 0.02) {
            return true;
        }

        // 5. Time-based: Close 2-5 minutes BEFORE next funding
        if ($trade->next_funding_at) {
            $minutesUntilFunding = $now->diffInMinutes($trade->next_funding_at, false);
            
            // If funding is in 2-5 minutes and we're profitable, close to lock in gains
            if ($minutesUntilFunding >= -5 && $minutesUntilFunding <= -2 && $total > 0) {
                return true;
            }

            // If we passed funding time by more than 5 mins, close
            if ($minutesUntilFunding < -5) {
                return true;
            }
        }

        // 6. Profitable small gains: If profit > $50 and we're within 10 mins of funding
        if ($trade->next_funding_at) {
            $minutesUntilFunding = abs($now->diffInMinutes($trade->next_funding_at, false));
            if ($total >= 50 && $minutesUntilFunding <= 10) {
                return true;
            }
        }

        return false;
    }

    /**
     * Legacy method for manual funding calculation
     */
    public function calculateFunding($positionSize, $fundingRate, $side) {
        $amount = ($positionSize / 2) * (abs($fundingRate));

        if ($fundingRate > 0) {
            return $side === 'long' ? -$amount : +$amount;
        }

        if ($fundingRate < 0) {
            return $side === 'long' ? +$amount : -$amount;
        }

        return 0;
    }
}