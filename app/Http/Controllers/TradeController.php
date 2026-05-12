<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\TradingAsset;
use App\Services\TradeSimulatorService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TradeController extends Controller {
    protected $simulator;

    public function __construct(TradeSimulatorService $simulator) {
        $this->simulator = $simulator;
    }

    /**
     * Main cron job entry point - runs every minute
     * Handles opening new trades and updating existing ones
     */
    public function executeTradingCycle() {
        try {
            Log::info('Trading cycle started', [
                'time' => now()->toDateTimeString(),
                'utc' => now()->timezone('UTC')->toDateTimeString()
            ]);

            // Step 1: Check if we're in the optimal entry window
            $canOpenTrades = $this->simulator->isOptimalEntryWindow();
            
            if ($canOpenTrades) {
                $this->scanAndOpenTrades();
            } else {
                Log::info('Outside entry window - skipping new trades', [
                    'next_funding' => $this->simulator->getNextFundingTime()->toDateTimeString()
                ]);
            }

            // Step 2: Always update existing open trades
            $this->updateOpenTrades();

            // Step 3: Check for re-entry opportunities on recently closed trades
            $this->checkReentryOpportunities();

            Log::info('Trading cycle completed successfully');

            return response()->json([
                'success' => true,
                'timestamp' => now()->toDateTimeString(),
                'in_entry_window' => $canOpenTrades,
                'next_funding' => $this->simulator->getNextFundingTime()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error('Trading cycle failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan all active assets and open trades for good opportunities
     */
    protected function scanAndOpenTrades() {
        $activeAssets = TradingAsset::where('active', true)->get();
        
        Log::info('Scanning for trade opportunities', [
            'asset_count' => $activeAssets->count()
        ]);

        $opened = 0;
        $skipped = 0;

        foreach ($activeAssets as $asset) {
            try {
                // Check if already have open trade for this asset
                $existingTrade = Trade::where('trading_asset_id', $asset->id)
                    ->where('status', 'open')
                    ->first();

                if ($existingTrade) {
                    Log::info("Asset {$asset->symbol} already has open trade", [
                        'trade_id' => $existingTrade->id
                    ]);
                    $skipped++;
                    continue;
                }

                // Get live funding rates
                $funding = $this->getFundingRates($asset);

                if (!$funding || !isset($funding['binance']) || !isset($funding['bybit'])) {
                    Log::warning("Failed to get funding rates for {$asset->symbol}");
                    $skipped++;
                    continue;
                }

                // Validate opportunity quality
                if (!$this->isGoodOpportunity($funding)) {
                    Log::info("Insufficient spread for {$asset->symbol}", [
                        'binance' => $funding['binance'],
                        'bybit' => $funding['bybit'],
                        'spread' => abs($funding['bybit'] - $funding['binance'])
                    ]);
                    $skipped++;
                    continue;
                }

                // Attempt to open trade
                $trade = $this->simulator->openTrade($asset, $funding);

                if ($trade) {
                    $opened++;
                    Log::info("Trade opened successfully", [
                        'trade_id' => $trade->id,
                        'asset' => $asset->symbol,
                        'position_size' => $trade->position_size,
                        'spread' => abs($funding['bybit'] - $funding['binance']),
                        'next_funding' => $trade->next_funding_at->toDateTimeString()
                    ]);
                } else {
                    $skipped++;
                    Log::info("Trade not opened for {$asset->symbol} - conditions not met");
                }

                // Rate limiting - don't hammer APIs
                usleep(200000); // 200ms delay between assets

            } catch (\Exception $e) {
                Log::error("Error processing asset {$asset->symbol}", [
                    'error' => $e->getMessage()
                ]);
                $skipped++;
            }
        }

        Log::info('Scan completed', [
            'opened' => $opened,
            'skipped' => $skipped,
            'total' => $activeAssets->count()
        ]);
    }

    /**
     * Update all open trades with latest prices and PnL
     */
    protected function updateOpenTrades() {
        $openTrades = Trade::where('status', 'open')->get();

        if ($openTrades->isEmpty()) {
            Log::info('No open trades to update');
            return;
        }

        Log::info('Updating open trades', [
            'count' => $openTrades->count()
        ]);

        $updated = 0;
        $closed = 0;

        // Randomize update order to simulate realistic market activity
        $tradesToUpdate = $openTrades->shuffle()->take(min($openTrades->count(), rand(3, 8)));

        foreach ($tradesToUpdate as $trade) {
            try {
                $statusBefore = $trade->status;
                
                $this->simulator->updateTrade($trade);
                
                $trade->refresh();
                
                if ($trade->status === 'closed' && $statusBefore === 'open') {
                    $closed++;
                    Log::info("Trade closed", [
                        'trade_id' => $trade->id,
                        'asset' => $trade->asset->symbol,
                        'total_pnl' => $trade->total_net,
                        'duration' => $trade->opened_at->diffForHumans($trade->closed_at, true),
                        'reason' => $this->determineCloseReason($trade)
                    ]);
                } else {
                    $updated++;
                }

                // Small delay to prevent race conditions
                usleep(100000); // 100ms

            } catch (\Exception $e) {
                Log::error("Error updating trade {$trade->id}", [
                    'error' => $e->getMessage(),
                    'asset' => $trade->asset->symbol ?? 'unknown'
                ]);
            }
        }

        Log::info('Trade updates completed', [
            'updated' => $updated,
            'closed' => $closed
        ]);
    }

    /**
     * Check recently closed trades for re-entry opportunities
     * Only relevant if we're near a funding window
     */
    protected function checkReentryOpportunities() {
        // Only check re-entry if we're approaching a funding window
        if (!$this->simulator->isOptimalEntryWindow()) {
            return;
        }

        // Get trades closed in last 30 minutes
        $recentlyClosed = Trade::where('status', 'closed')
            ->where('closed_at', '>=', now()->subMinutes(30))
            ->get()
            ->unique('trading_asset_id'); // One per asset

        if ($recentlyClosed->isEmpty()) {
            return;
        }

        Log::info('Checking re-entry opportunities', [
            'count' => $recentlyClosed->count()
        ]);

        $reentered = 0;

        foreach ($recentlyClosed as $trade) {
            try {
                // Don't re-enter if we already have an open trade
                $hasOpen = Trade::where('trading_asset_id', $trade->trading_asset_id)
                    ->where('status', 'open')
                    ->exists();

                if ($hasOpen) {
                    continue;
                }

                // Check if the opportunity still exists
                $funding = $this->getFundingRates($trade->asset);

                if (!$funding) {
                    continue;
                }

                // Higher threshold for re-entry (0.15% instead of 0.05%)
                if ($this->isGoodOpportunity($funding, 0.0015)) {
                    $newTrade = $this->simulator->openTrade($trade->asset, $funding);
                    
                    if ($newTrade) {
                        $reentered++;
                        Log::info("Re-entered trade", [
                            'new_trade_id' => $newTrade->id,
                            'old_trade_id' => $trade->id,
                            'asset' => $trade->asset->symbol,
                            'spread' => abs($funding['bybit'] - $funding['binance'])
                        ]);
                    }
                }

                usleep(200000); // 200ms delay

            } catch (\Exception $e) {
                Log::error("Error checking re-entry for trade {$trade->id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($reentered > 0) {
            Log::info('Re-entry check completed', [
                'reentered' => $reentered
            ]);
        }
    }

    /**
     * Get live funding rates from Coinalyze API
     */
    public function getFundingRates($asset) {
        try {
            $symbol = strtoupper($asset->symbol);
            $symbols = "{$symbol}USDT_PERP.A,{$symbol}USD_PERP.0";

            $response = Http::timeout(10)
                ->retry(2, 100) // Retry twice with 100ms delay
                ->withHeaders([
                    'api_key' => config('services.coinalyze.key'),
                ])
                ->get('https://api.coinalyze.net/v1/funding-rate', [
                    'symbols' => $symbols
                ]);

            if ($response->successful()) {
                $data = collect($response->json());
                return $this->formatFundingData($data);
            }

            Log::warning("Funding API request failed for {$asset->symbol}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error("Exception fetching funding rates for {$asset->symbol}", [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Format funding data from API response
     */
    protected function formatFundingData($response) {
        $data = [
            'bybit' => null,
            'binance' => null,
        ];

        foreach ($response as $item) {
            $symbol = $item['symbol'] ?? '';
            $rate = $item['value'] ?? null;

            if ($rate === null) {
                continue;
            }

            // Detect exchange by symbol format
            if (str_contains($symbol, 'USDT')) {
                // Binance: BTCUSDT_PERP.A
                $data['binance'] = (float) $rate;
            } else {
                // Bybit: BTCUSD_PERP.0
                $data['bybit'] = (float) $rate;
            }
        }

        return $data;
    }

    /**
     * Validate if funding spread represents good opportunity
     * 
     * @param array $funding
     * @param float|null $customMinSpread Override default minimum spread
     * @return bool
     */
    public function isGoodOpportunity($funding, $customMinSpread = null) {
        $bybit = (float) ($funding['bybit'] ?? 0);
        $binance = (float) ($funding['binance'] ?? 0);

        // Must have both rates
        if ($bybit === 0.0 || $binance === 0.0) {
            return false;
        }

        $spread = abs($bybit - $binance);
        $minSpread = $customMinSpread ?? 0.0005; // Default: 0.05% (5 basis points)

        // Spread must be above minimum threshold
        if ($spread < $minSpread) {
            return false;
        }

        // Optional: Check if spread is not abnormally high (possible data error)
        $maxSpread = 0.02; // 2% - anything above is suspicious
        if ($spread > $maxSpread) {
            Log::warning('Abnormally high spread detected', [
                'spread' => $spread,
                'binance' => $binance,
                'bybit' => $bybit
            ]);
            return false;
        }

        return true;
    }

    /**
     * Determine why a trade was closed (for logging)
     */
    protected function determineCloseReason($trade): string {
        $total = $trade->total_net;
        $positionSize = $trade->position_size;
        
        if ($total <= -($positionSize * 0.02)) {
            return 'Stop Loss Hit';
        }
        
        if ($total >= ($positionSize * 0.03)) {
            return 'Take Profit Hit';
        }
        
        $drawdown = $trade->peak_profit - $total;
        if ($trade->peak_profit > 100 && $drawdown >= ($trade->peak_profit * 0.40)) {
            return 'Trailing Stop';
        }

        if ($trade->next_funding_at) {
            $minutesUntilFunding = now()->timezone('UTC')->diffInMinutes($trade->next_funding_at, false);
            if ($minutesUntilFunding >= -5 && $minutesUntilFunding <= -2) {
                return 'Pre-Funding Exit';
            }
        }

        return 'Time/Spread Exit';
    }

    /**
     * Manual endpoint to force check current window status
     */
    public function checkFundingWindow() {
        $now = now()->timezone('UTC');
        $nextFunding = $this->simulator->getNextFundingTime();
        $inWindow = $this->simulator->isOptimalEntryWindow();

        return response()->json([
            'current_time_utc' => $now->toDateTimeString(),
            'next_funding_time' => $nextFunding->toDateTimeString(),
            'minutes_until_funding' => $now->diffInMinutes($nextFunding),
            'in_entry_window' => $inWindow,
            'window_description' => $inWindow 
                ? '✅ Optimal entry window (15-30 mins before funding)'
                : '❌ Outside entry window',
            'funding_schedule' => [
                '00:00 UTC',
                '08:00 UTC', 
                '16:00 UTC'
            ]
        ]);
    }

    /**
     * Get current trading status and statistics
     */
    public function getTradingStatus() {
        $openTrades = Trade::where('status', 'open')->get();
        $todayClosed = Trade::where('status', 'closed')
            ->whereDate('closed_at', today())
            ->get();

        return response()->json([
            'open_trades' => [
                'count' => $openTrades->count(),
                'total_position_size' => $openTrades->sum('position_size'),
                'total_pnl' => $openTrades->sum('total_net'),
                'trades' => $openTrades->map(fn($t) => [
                    'id' => $t->id,
                    'asset' => $t->asset->symbol,
                    'position_size' => $t->position_size,
                    'pnl' => $t->total_net,
                    'opened_at' => $t->opened_at->diffForHumans(),
                    'next_funding' => $t->next_funding_at?->diffForHumans(),
                ])
            ],
            'today_closed' => [
                'count' => $todayClosed->count(),
                'total_pnl' => $todayClosed->sum('total_net'),
                'winners' => $todayClosed->where('total_net', '>', 0)->count(),
                'losers' => $todayClosed->where('total_net', '<', 0)->count(),
            ],
            'system_status' => [
                'in_entry_window' => $this->simulator->isOptimalEntryWindow(),
                'next_funding' => $this->simulator->getNextFundingTime()->toDateTimeString(),
            ]
        ]);
    }
}