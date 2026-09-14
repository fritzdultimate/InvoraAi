<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\TradingAsset;
use App\Services\TradeSimulatorMultiexchangeService;
use Illuminate\Support\Facades\Http;

class TradeMultiexchangeController extends Controller {
    protected $simulator;

    // Coinalyze exchange suffixes mapping
    const EXCHANGE_SUFFIXES = [
        'binance' => '_PERP.A',      // BTCUSDT_PERP.A
        'bybit' => '.6',        // BTCUSDT.6
        'okx' => '_PERP.3',          // BTCUSDT_PERP.3
        'bitmex' => '_PERP.0',       // BTCUSDT_PERP.0
        'huobi' => '_PERP.4'     // BTCUSDT_PERP.4,
        // Add more as needed
    ];

    public function __construct(TradeSimulatorMultiexchangeService $simulator) {
        $this->simulator = $simulator;
    }

    /**
     * Main trading cycle
     */
    public function executeTradingCycle() {
        try {
            


            $canOpenTrades = $this->simulator->isOptimalEntryWindow();
            
            if ($canOpenTrades) {
                $this->scanAndOpenTrades();
            } else {
            }

            $this->updateOpenTrades();
            $this->checkReentryOpportunities();


            return response()->json([
                'success' => true,
                'timestamp' => now()->toDateTimeString(),
                'in_entry_window' => $canOpenTrades,
                'exchanges_monitored' => TradeSimulatorMultiexchangeService::SUPPORTED_EXCHANGES
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan and open trades across all exchanges
     */
    protected function scanAndOpenTrades() {
        $activeAssets = TradingAsset::where('active', true)->get();
        
        

        $opened = 0;
        $skipped = 0;

        foreach ($activeAssets as $asset) {
            try {
                // Check for existing trade
                $existingTrade = Trade::where('trading_asset_id', $asset->id)
                    ->where('status', 'open')
                    ->first();

                if ($existingTrade) {
                    $skipped++;
                    continue;
                }

                // Get funding rates from ALL supported exchanges
                $fundingRates = $this->getFundingRatesAllExchanges($asset);

                if (empty($fundingRates)) {
                    $skipped++;
                    continue;
                }

                $trade = $this->simulator->openTrade($asset, $fundingRates);

                if ($trade) {
                    $opened++;
                } else {
                    $skipped++;
                }

                usleep(200000); // 200ms delay

            } catch (\Exception $e) {
                $skipped++;
            }
        }

        
    }

    /**
     * Update existing open trades
     */
    protected function updateOpenTrades() {
        $openTrades = Trade::where('status', 'open')->get();

        if ($openTrades->isEmpty()) {
            return;
        }


        $updated = 0;
        $closed = 0;

        $tradesToUpdate = $openTrades->shuffle()->take(min($openTrades->count(), rand(3, 8)));

        foreach ($tradesToUpdate as $trade) {
            try {
                $statusBefore = $trade->status;
                
                $this->simulator->updateTrade($trade);
                
                $trade->refresh();
                
                if ($trade->status === 'closed' && $statusBefore === 'open') {
                    $closed++;
                    
                } else {
                    $updated++;
                }

                usleep(100000);

            } catch (\Exception $e) {
                
            }
        }

        
    }

    /**
     * Check for re-entry opportunities
     */
    protected function checkReentryOpportunities() {
        if (!$this->simulator->isOptimalEntryWindow()) {
            return;
        }

        $recentlyClosed = Trade::where('status', 'closed')
            ->where('closed_at', '>=', now()->subMinutes(30))
            ->get()
            ->unique('trading_asset_id');

        if ($recentlyClosed->isEmpty()) {
            return;
        }

        $reentered = 0;

        foreach ($recentlyClosed as $trade) {
            try {
                $hasOpen = Trade::where('trading_asset_id', $trade->trading_asset_id)
                    ->where('status', 'open')
                    ->exists();

                if ($hasOpen) {
                    continue;
                }

                $fundingRates = $this->getFundingRatesAllExchanges($trade->asset);

                if (!empty($fundingRates)) {
                    $newTrade = $this->simulator->openTrade($trade->asset, $fundingRates);
                    
                    if ($newTrade) {
                        $reentered++;
                    }
                }

                usleep(200000);

            } catch (\Exception $e) {
            }
        }

        if ($reentered > 0) {
        }
    }

    /**
     * Get funding rates from ALL supported exchanges
     * Returns associative array: ['binance' => 0.001, 'bybit' => 0.0015, ...]
     */
    public function getFundingRatesAllExchanges($asset): array {
        $symbol = strtoupper($asset->symbol);
        $fundingRates = [];

        // Build symbols string for Coinalyze API
        $symbols = [];
        foreach (self::EXCHANGE_SUFFIXES as $exchange => $suffix) {
            if ($exchange === 'bybit') {
                // Bybit uses different format: BTCUSD_PERP.0
                $symbols[] = $symbol . 'USD' . $suffix;
            } else {
                // Most use: BTCUSDT_PERP.X
                $symbols[] = $symbol . 'USDT' . $suffix;
            }
        }

        $symbolsString = implode(',', $symbols);

        try {
            $response = Http::timeout(15)
                ->retry(2, 100)
                ->withHeaders([
                    'api_key' => config('services.coinalyze.key'),
                ])
                ->get('https://api.coinalyze.net/v1/funding-rate', [
                    'symbols' => $symbolsString
                ]);

                // $response1 = Http::timeout(15)
                // ->retry(2, 100)
                // ->withHeaders([
                //     'api_key' => config('services.coinalyze.key'),
                // ])
                // ->get('https://api.coinalyze.net/v1/exchanges');

                // $data1 = $response1->json();

                // dd($data1);

                // $response1 = Http::timeout(15)
                // ->retry(2, 100)
                // ->withHeaders([
                //     'api_key' => config('services.coinalyze.key'),
                // ])
                // ->get('https://api.coinalyze.net/v1/funding-rate?symbols=ETHUSDT_PERP.Y');

                // $data1 = $response1->json();

                // dd($data1);

            if ($response->successful()) {
                $data = $response->json();
                
                foreach ($data as $item) {
                    $itemSymbol = $item['symbol'] ?? '';
                    $rate = $item['value'] ?? null;

                    if ($rate === null) {
                        continue;
                    }

                    // Map symbol back to exchange
                    foreach (self::EXCHANGE_SUFFIXES as $exchange => $suffix) {
                        if (str_ends_with($itemSymbol, $suffix)) {
                            $fundingRates[$exchange] = (float) $rate;
                            break;
                        }
                    }
                }

                

                return $fundingRates;
            }

            

        } catch (\Exception $e) {
            
        }

        return [];
    }

    /**
     * Check funding window status
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
            'supported_exchanges' => TradeSimulatorMultiexchangeService::SUPPORTED_EXCHANGES,
            'exchange_count' => count(TradeSimulatorMultiexchangeService::SUPPORTED_EXCHANGES)
        ]);
    }

    /**
     * Get current trading status
     */
    public function getTradingStatus()
    {
        $openTrades = Trade::where('status', 'open')->get();
        $todayClosed = Trade::where('status', 'closed')
            ->whereDate('closed_at', today())
            ->get();

        // Count trades by exchange pair
        $exchangePairs = $openTrades->groupBy(function ($trade) {
            return $trade->long_exchange . ' / ' . $trade->short_exchange;
        })->map->count();

        return response()->json([
            'open_trades' => [
                'count' => $openTrades->count(),
                'total_position_size' => $openTrades->sum('position_size'),
                'total_pnl' => $openTrades->sum('total_net'),
                'exchange_pairs' => $exchangePairs,
                'trades' => $openTrades->map(fn($t) => [
                    'id' => $t->id,
                    'asset' => $t->asset->symbol,
                    'pair' => $t->long_exchange . ' / ' . $t->short_exchange,
                    'position' => number_format($t->position_size),
                    'pnl' => number_format($t->total_net, 2),
                    'opened' => $t->opened_at->diffForHumans(),
                ])
            ],
            'today_closed' => [
                'count' => $todayClosed->count(),
                'total_pnl' => $todayClosed->sum('total_net'),
                'winners' => $todayClosed->where('total_net', '>', 0)->count(),
            ],
            'system' => [
                'in_entry_window' => $this->simulator->isOptimalEntryWindow(),
                'next_funding' => $this->simulator->getNextFundingTime()->toDateTimeString(),
                'monitored_exchanges' => TradeSimulatorMultiexchangeService::SUPPORTED_EXCHANGES,
            ]
        ]);
    }

    /**
     * Test funding rate fetching for a specific asset
     */
    public function testFundingRates($assetSymbol)
    {
        $asset = TradingAsset::where('symbol', strtoupper($assetSymbol))->first();

        if (!$asset) {
            return response()->json([
                'error' => "Asset {$assetSymbol} not found"
            ], 404);
        }

        $rates = $this->getFundingRatesAllExchanges($asset);

        if (empty($rates)) {
            return response()->json([
                'asset' => $asset->symbol,
                'error' => 'No funding rates available',
                'exchanges_checked' => self::EXCHANGE_SUFFIXES
            ]);
        }

        // Find best pair
        $bestPair = $this->simulator->findBestTradingPair($rates);

        return response()->json([
            'asset' => $asset->symbol,
            'funding_rates' => $rates,
            'best_pair' => $bestPair,
            'recommendation' => $bestPair ? 
                "Long on {$bestPair['long_exchange']}, Short on {$bestPair['short_exchange']}" :
                "No profitable spread found",
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function scanAndCloseTrades() {
        $trades = Trade::with('asset')->where('status', 'open')->get();

        foreach ($trades as $trade) {
            try {

                $this->simulator->evaluateTradeClosure($trade);

                usleep(200000); // 200ms delay

            } catch (\Exception $e) {
                
            }
        }

    }
}