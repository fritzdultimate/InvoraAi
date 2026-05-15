<?php

namespace App\Services;

use App\Models\Trade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TradeSimulatorMultiexchangeService
{
    // Funding happens at: 00:00, 08:00, 16:00 UTC
    const FUNDING_HOURS = [0, 8, 16];
    
    // Open trades 15-30 mins before funding
    const MIN_MINUTES_BEFORE_FUNDING = 15;
    const MAX_MINUTES_BEFORE_FUNDING = 30;

    // Supported exchanges - add more here!
    const SUPPORTED_EXCHANGES = [
        'binance',
        'bybit',
        'bitmex',
        // 'okx',
        'huobi',
    ];

    /**
     * Get live prices from multiple exchanges
     */
    public function getLivePrices($asset)
    {
        $symbol = strtoupper($asset->symbol);

        return Cache::remember("prices_{$symbol}", 5, function () use ($symbol) {
            $prices = [];

            foreach (self::SUPPORTED_EXCHANGES as $exchange) {
                $prices[$exchange] = $this->fetchPriceFromExchange($exchange, $symbol);
            }

            // Filter out null prices
            $prices = array_filter($prices, fn($price) => $price !== null);

            Log::info("Fetched prices for {$symbol}", [
                'exchanges' => array_keys($prices),
                'prices' => $prices
            ]);

            return $prices;
        });
    }

    /**
     * Fetch price from specific exchange
     */
    protected function fetchPriceFromExchange(string $exchange, string $symbol): ?float
    {
        try {
            switch ($exchange) {
                case 'binance':
                    return $this->getBinancePrice($symbol);
                
                case 'bybit':
                    return $this->getBybitPrice($symbol);
                
                case 'okx':
                    return $this->getOkxPrice($symbol);
                
                case 'bitmex':
                    return $this->getBitmexPrice($symbol);
                
                case 'huobi':
                    return $this->getHuobiPrice($symbol);
                
                // Add more exchanges here
                // case 'kraken':
                //     return $this->getKrakenPrice($symbol);
                
                default:
                    Log::warning("Unsupported exchange: {$exchange}");
                    return null;
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch price from {$exchange}", [
                'symbol' => $symbol,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Binance price fetcher
     */
    protected function getBinancePrice(string $symbol): ?float {
        $symbol = strtoupper($symbol) . 'USDT';
        $response = Http::timeout(5)->get(
            "https://api.binance.com/api/v3/ticker/price",
            ['symbol' => $symbol]
        );

        if ($response->successful()) {
            return (float) $response['price'];
        }

        return null;
    }

    /**
     * Bybit price fetcher
     */
    protected function getBybitPrice(string $symbol): ?float {
        $symbol = strtoupper($symbol) . 'USDT';
        $response = Http::timeout(5)->get(
            "https://api.bybit.com/v5/market/tickers",
            [
                'category' => 'linear',
                'symbol' => $symbol
            ]
        );

        if ($response->successful()) {
            $list = $response['result']['list'] ?? [];
            if (!empty($list)) {
                return (float) $list[0]['markPrice'];
            }
        }

        return null;
    }

    function bitmexSymbol($symbol) {
        $map = [
            'BTC' => 'XBT',
        ];

        $base = $map[$symbol] ?? $symbol;

        return $base . 'USD';
    }

    /**
     * Bybit price fetcher
     */
    protected function getBitmexPrice(string $symbol): ?float {
        $bitmexSymbol = $this->bitmexSymbol(strtoupper($symbol));
        $response = Http::timeout(5)->get(
            "https://www.bitmex.com/api/v1/instrument",
            [
                'symbol' => $bitmexSymbol
            ]
        );

        if ($response->successful()) {
            return (float) $response[0]['lastPrice'];
        }

        return null;
    }

    /**
     * OKX price fetcher
     */
    protected function getOkxPrice(string $symbol): ?float
    {
        // OKX uses different symbol format: BTC-USDT-SWAP
        $okxSymbol = str_replace('USDT', '-USDT-SWAP', $symbol);
        
        $response = Http::timeout(5)->get(
            "https://www.okx.com/api/v5/market/ticker",
            ['instId' => $okxSymbol]
        );

        if ($response->successful()) {
            $data = $response['data'] ?? [];
            if (!empty($data)) {
                return (float) $data[0]['last'];
            }
        }

        return null;
    }

    /**
     * BingX price fetcher
     */
    protected function getHuobiPrice(string $symbol): ?float {
        $response = Http::timeout(5)->get(
            "https://api.huobi.pro/market/detail/merged",
            ['symbol' => strtolower($symbol) . 'usdt']
        );

        // dd($response->successful());

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['tick']['close'])) {
                return (float) $data['tick']['close'];
            }
        }

        return null;
    }

    /**
     * Find the best long/short pair from all available exchanges
     * Returns array with best exchanges to use and the spread
     */
    public function findBestTradingPair(array $fundingRates): ?array {
        // Filter out exchanges with null funding rates
        $validRates = array_filter($fundingRates, fn($rate) => $rate !== null);

        if (count($validRates) < 2) {
            Log::info('Need at least 2 exchanges with valid funding rates');
            return null;
        }

        // Sort funding rates
        asort($validRates); // Ascending order

        // Get exchange with lowest funding rate (best for LONG)
        $longExchange = array_key_first($validRates);
        $longRate = $validRates[$longExchange];

        // Get exchange with highest funding rate (best for SHORT)
        end($validRates);
        $shortExchange = key($validRates);
        $shortRate = $validRates[$shortExchange];

        // Calculate spread
        $spread = abs($shortRate - $longRate);

        // Validate spread is meaningful
        if ($spread < 0.0005) { // 0.05% minimum
            Log::info('Spread too low across all exchanges', [
                'spread' => $spread,
                'long_exchange' => $longExchange,
                'short_exchange' => $shortExchange
            ]);
            return null;
        }

        Log::info('Best trading pair found', [
            'long_exchange' => $longExchange,
            'long_rate' => $longRate,
            'short_exchange' => $shortExchange,
            'short_rate' => $shortRate,
            'spread' => $spread
        ]);

        return [
            'long_exchange' => $longExchange,
            'long_rate' => $longRate,
            'short_exchange' => $shortExchange,
            'short_rate' => $shortRate,
            'spread' => $spread,
            'all_rates' => $validRates // For logging/analysis
        ];
    }

    /**
     * Check if current time is in the optimal window to open a trade
     */
    public function isOptimalEntryWindow(): bool
    {
        $now = now()->timezone('UTC');
        $currentMinute = $now->minute;
        $currentHour = $now->hour;

        foreach (self::FUNDING_HOURS as $fundingHour) {
            $minutesUntilFunding = $this->getMinutesUntilFunding($fundingHour, $currentHour, $currentMinute);

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
    private function getMinutesUntilFunding(int $fundingHour, int $currentHour, int $currentMinute): int
    {
        if ($currentHour < $fundingHour) {
            return (($fundingHour - $currentHour) * 60) - $currentMinute;
        } elseif ($currentHour == $fundingHour) {
            return 60 - $currentMinute;
        }
        return -1;
    }

    /**
     * Get the next funding timestamp
     */
    public function getNextFundingTime(): Carbon
    {
        $now = now()->timezone('UTC');
        $currentHour = $now->hour;

        $nextFundingHour = null;
        foreach (self::FUNDING_HOURS as $hour) {
            if ($hour > $currentHour) {
                $nextFundingHour = $hour;
                break;
            }
        }

        if ($nextFundingHour === null) {
            return $now->copy()->addDay()->setTime(self::FUNDING_HOURS[0], 0, 0);
        }

        return $now->copy()->setTime($nextFundingHour, 0, 0);
    }

    /**
     * Calculate dynamic capital based on funding rate difference
     */
    public function calculateDynamicCapital(float $fundingSpread): float
    {
        $minCapital = 20000;
        $maxCapital = 100000;

        if ($fundingSpread >= 0.01) {
            return $maxCapital;
        } elseif ($fundingSpread >= 0.005) {
            return $minCapital + (($maxCapital - $minCapital) * rand(70, 90) / 100);
        } elseif ($fundingSpread >= 0.003) {
            return $minCapital + (($maxCapital - $minCapital) * rand(50, 70) / 100);
        } elseif ($fundingSpread >= 0.001) {
            return $minCapital + (($maxCapital - $minCapital) * rand(30, 50) / 100);
        } else {
            return $minCapital * 0.5;
        }
    }

    /**
     * Open trade with automatic best-pair selection
     */
    public function openTrade($asset, $fundingRates)
    {
        if (!$fundingRates || empty($fundingRates)) {
            return null;
        }

        // Check if we're in optimal entry window
        if (!$this->isOptimalEntryWindow()) {
            return null;
        }

        // Check if trade already exists
        $existing = Trade::where('trading_asset_id', $asset->id)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return null;
        }

        // Find best trading pair across all exchanges
        $bestPair = $this->findBestTradingPair($fundingRates);


        if (!$bestPair) {
            return null;
        }

        // Get prices from selected exchanges
        $prices = $this->getLivePrices($asset);

        // dd($prices);

        $longExchange = $bestPair['long_exchange'];
        $shortExchange = $bestPair['short_exchange'];

        if (!isset($prices[$longExchange]) || !isset($prices[$shortExchange])) {
            Log::warning('Missing prices for selected exchanges', [
                'long' => $longExchange,
                'short' => $shortExchange,
                'available' => array_keys($prices)
            ]);
            return null;
        }

        $longPrice = $prices[$longExchange];
        $shortPrice = $prices[$shortExchange];

        // Add minor price variation for realism
        $longPrice += (rand(-10, 10) / 10000);
        $shortPrice += (rand(-10, 10) / 10000);

        // Dynamic capital allocation
        $capital = $this->calculateDynamicCapital($bestPair['spread']);

        // Position sizing with risk management
        if ($bestPair['spread'] >= 0.008) {
            $leverage = rand(8, 10);
        } elseif ($bestPair['spread'] >= 0.005) {
            $leverage = rand(5, 7);
        } elseif ($bestPair['spread'] >= 0.003) {
            $leverage = rand(3, 5);
        } else {
            $leverage = rand(2, 3);
        }

        $positionSize = $capital * $leverage;

        // Calculate fees (0.06% per side × 2 sides × 2 operations = 0.24% total)
        $feeRate = 0.0006;
        $fees = $positionSize * $feeRate * 2;

        $trade = Trade::create([
            'trading_asset_id' => $asset->id,

            'long_exchange' => ucfirst($longExchange),
            'short_exchange' => ucfirst($shortExchange),

            'position_size' => $positionSize,

            'entry_price_long' => $longPrice,
            'entry_price_short' => $shortPrice,

            'funding_rate_long' => $bestPair['long_rate'],
            'funding_rate_short' => $bestPair['short_rate'],

            'fees' => $fees,

            'opened_at' => now(),
            'next_funding_at' => $this->getNextFundingTime(),
        ]);

        Log::info('Trade opened with multi-exchange selection', [
            'trade_id' => $trade->id,
            'asset' => $asset->symbol,
            'long' => $longExchange,
            'short' => $shortExchange,
            'spread' => $bestPair['spread'],
            'position' => $positionSize,
            'all_rates' => $bestPair['all_rates']
        ]);

        return $trade;
    }

    /**
     * Update trade using prices from the specific exchanges
     */
    public function updateTrade($trade) {
        $prices = $this->getLivePrices($trade->asset);

        $longExchange = strtolower($trade->long_exchange);
        $shortExchange = strtolower($trade->short_exchange);

        if (!isset($prices[$longExchange]) || !isset($prices[$shortExchange])) {
            Log::warning('Cannot update trade - missing exchange prices', [
                'trade_id' => $trade->id,
                'needed' => [$longExchange, $shortExchange],
                'available' => array_keys($prices)
            ]);
            return;
        }

        $exitLong = $prices[$longExchange];
        $exitShort = $prices[$shortExchange];

        // Check spread - close if arbitrage is breaking down
        $spread = abs(
            ($exitLong - $exitShort) / (($exitLong + $exitShort) / 2)
        );

        // Calculate price PnL
        $longPnL = (($exitLong - $trade->entry_price_long) / $trade->entry_price_long) * ($trade->position_size / 2);
        $shortPnL = (($trade->entry_price_short - $exitShort) / $trade->entry_price_short) * ($trade->position_size / 2);
        $pricePnL = $longPnL + $shortPnL;

        // Add minor randomness
        $pricePnL += mt_rand(-50, 50) / 100;

        // Calculate funding profit
        $fundingProfit = $this->calculateAccumulatedFunding($trade);

        // Calculate total PnL
        $total = $pricePnL + $fundingProfit - $trade->fees;

        // Update peak profit
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

        // Check if should close
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
        
        $hoursOpen = $openedAt->diffInHours($now);
        $fundingsPassed = floor($hoursOpen / 8);

        if ($fundingsPassed < 1) {
            return 0;
        }

        $fundingRateDiff = abs($trade->funding_rate_short - $trade->funding_rate_long);
        $fundingPerPeriod = ($trade->position_size / 2) * $fundingRateDiff;

        $totalFunding = $fundingPerPeriod * $fundingsPassed;

        $variance = $totalFunding * (rand(-10, 10) / 100);

        // dd([
        //     'now' => $now,
        //     'openedAt' => $openedAt,
        //     'hoursOpen' => $hoursOpen,
        //     'fundingsPassed' => $fundingsPassed,
        //     'fundingRateDiff' => $fundingRateDiff,
        //     'fundingPerPeriod' => $fundingPerPeriod,
        //     'totalFunding' => $totalFunding,
        //     'variance' => $variance
        // ]);
        
        return $totalFunding + $variance;
    }

    /**
     * Determine if trade should be closed
     */
    private function shouldCloseTrade($trade, float $spread): bool
    {
        $total = $trade->total_net;
        $positionSize = $trade->position_size;
        $now = now()->timezone('UTC');

        // Stop Loss: -2%
        if ($total <= -($positionSize * 0.02)) {
            return true;
        }

        // Take Profit: +3%
        if ($total >= ($positionSize * 0.03)) {
            return true;
        }

        // Trailing Stop
        $drawdown = $trade->peak_profit - $total;
        if ($trade->peak_profit > 100 && $drawdown >= ($trade->peak_profit * 0.40)) {
            return true;
        }

        // Spread Breakout
        if ($spread > 0.02) {
            return true;
        }

        // Time-based exits
        if ($trade->next_funding_at) {
            $minutesUntilFunding = $now->diffInMinutes($trade->next_funding_at, false);
            
            if ($minutesUntilFunding >= -5 && $minutesUntilFunding <= -2 && $total > 0) {
                return true;
            }

            if ($minutesUntilFunding < -5) {
                return true;
            }
        }

        // Quick win lock
        if ($trade->next_funding_at) {
            $minutesUntilFunding = abs($now->diffInMinutes($trade->next_funding_at, false));
            if ($total >= 50 && $minutesUntilFunding <= 10) {
                return true;
            }
        }

        return false;
    }

    /**
     * Legacy funding calculation
     */
    public function calculateFunding($positionSize, $fundingRate, $side)
    {
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