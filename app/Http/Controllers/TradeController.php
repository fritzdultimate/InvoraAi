<?php

namespace App\Http\Controllers;



use App\Models\Trade;
use App\Models\TradingAsset;
use App\Services\TradeSimulatorService;
use Illuminate\Support\Facades\Http;

class TradeController extends Controller
{

    public function getFundingRates($asset) {
        try {
            $symbol = strtoupper($asset->symbol);
            $symbols = "{$symbol}USDT_PERP.A,{$symbol}USD_PERP.0";

            $response = Http::withHeaders([
                'api_key' => config('services.coinalyze.key'),
            ])->get('https://api.coinalyze.net/v1/funding-rate', [
                        'symbols' => $symbols
                    ]);

            if ($response->successful()) {

                $data = collect($response->json());

                // dd($data);

                $fundingData = $this->formatFundingData($data);

                return [
                    'binance' => $fundingData['binance'],
                    'bybit' => $fundingData['bybit'],
                ];

            } else {
            }

        } catch (\Exception $e) {
        }
    }

    public function formatFundingData($response)
    {
        $data = [
            'bybit' => 0,
            'binance' => 0,
        ];

        foreach ($response as $item) {

            $symbol = $item['symbol'];
            $rate = $item['value'];

            // Detect exchange
            if (str_contains($symbol, 'USDT')) {
                // Binance style
                $data['binance'] = $rate;
            } else {
                // Bybit style
                $data['bybit'] = $rate;
            }
        }

        return $data;
    }

    public function simulate()
    {
        $simulator = new TradeSimulatorService();

        // Open new trades
        TradingAsset::where('active', true)->each(function ($asset) use ($simulator) {
            $funding = $this->getFundingRates($asset);

            $simulator->openTrade($asset, $funding);
        });

        // Update open trades
        Trade::where('status', 'open')->each(function ($trade) use ($simulator) {
            $simulator->updateTrade($trade);
        });

        Trade::where('status', 'closed')->each(function ($trade) use ($simulator) {
            $funding = $this->getFundingRates($trade->asset);

            if ($this->isGoodOpportunity($funding)) {
                $simulator->openTrade($trade->asset, $funding);
            }

            
        });

    }

    public function isGoodOpportunity($funding) {
        $bybit = (float) $funding['bybit'];
        $binance = (float) $funding['binance'];


        $diff = abs($bybit - $binance);

        $minSpread = 0.001; // 0.1%

        if ($diff < $minSpread) {
            return false;
        }

        return true;
    }


}
