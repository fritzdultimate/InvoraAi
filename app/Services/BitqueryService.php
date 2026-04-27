<?php

namespace App\Services;

use App\Models\LiveTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BitqueryService
{
    private $stablecoins = [
        'USDT',
        'USDC',
        'DAI',
        'BUSD'
    ];

    public static function getTrades()
    {
        $query = <<<'GRAPHQL'
{
  EVM {
    DEXTrades(
      limit: {count: 5}
      orderBy: {descending: Block_Number}
    ) {
      Trade {
        Dex {
          ProtocolName
        }
        Buy {
          Amount
          PriceInUSD
          Currency {
            Symbol
          }
        }
        Sell {
          Amount
          PriceInUSD
          Currency {
            Symbol
          }
        }
      }
      Block {
        Time
      }
      Transaction {
        Hash
      }
    }
  }
}
GRAPHQL;

        $response = Http::withHeaders([
            // 'X-API-KEY' => config('services.bitquery.key'),
            'Authorization' => 'Bearer ' . config('services.bitquery.key'),
        ])->post('https://streaming.bitquery.io/graphql', [
                    'query' => $query
                ]);

        // if (!$response->successful()) {
        //     throw new \Exception('HTTP Error: ' . $response->status());
        // }


        $data = $response->json();

        $trades = data_get($data, 'data.EVM.DEXTrades', []);

        foreach ($trades as $trade) {

            $hash = data_get($trade, 'Transaction.Hash');

            // 
            LiveTrade::updateOrCreate(
                ['tx_hash' => $hash],
                [
                    'protocol' => data_get($trade, 'Trade.Dex.ProtocolName'),

                    'buy_amount' => data_get($trade, 'Trade.Buy.Amount'),
                    'buy_symbol' => data_get($trade, 'Trade.Buy.Currency.Symbol'),
                    'buy_price_usd' => data_get($trade, 'Trade.Buy.PriceInUSD'),

                    'sell_amount' => data_get($trade, 'Trade.Sell.Amount'),
                    'sell_symbol' => data_get($trade, 'Trade.Sell.Currency.Symbol'),
                    'sell_price_usd' => data_get($trade, 'Trade.Sell.PriceInUSD'),

                    'block_time' => Carbon::parse(data_get($trade, 'Block.Time')),
                ]
            );
        }

        // return $response->json();
    }

    private function calculateTradeDetails($buySymbol, $sellSymbol, $buyAmount, $sellAmount, $buyPriceInUSD, $sellPriceInUSD)
    {
        $buyIsStable = in_array($buySymbol, $this->stablecoins);
        $sellIsStable = in_array($sellSymbol, $this->stablecoins);

        $buyValueUSD = $buyPriceInUSD * $buyAmount;
        $sellValueUSD = $sellPriceInUSD * $sellAmount;

        if ($buyIsStable && !$sellIsStable) {
            $side = 'buy';
            $symbol = "{$sellSymbol}/{$buySymbol}";
            $price = $buyAmount / $sellAmount;
            $quantity = $sellAmount;
            $cost = $buyAmount;
            $profit = null;
        } elseif (!$buyIsStable && $sellIsStable) {
            $side = 'sell';
            $symbol = "{$buySymbol}/{$sellSymbol}";
            $price = $sellAmount / $buyAmount;
            $quantity = $buyAmount;
            $cost = $sellAmount;
            $profit = $sellValueUSD - $buyValueUSD;
        } elseif (!$buyIsStable && !$sellIsStable) {
            if ($buyValueUSD >= $sellValueUSD) {
                $side = 'buy';
                $symbol = "{$buySymbol}/{$sellSymbol}";
                $price = $buyAmount / $sellAmount;
                $quantity = $buyAmount;
                $cost = $sellAmount;
                $profit = null;
            } else {
                $side = 'sell';
                $symbol = "{$sellSymbol}/{$buySymbol}";
                $price = $sellAmount / $buyAmount;
                $quantity = $sellAmount;
                $cost = $buyAmount;
                $profit = $sellValueUSD - $buyValueUSD;
            }
        } else {
            $side = $buyValueUSD >= $sellValueUSD ? 'buy' : 'sell';
            $symbol = "{$buySymbol}/{$sellSymbol}";
            $price = $buyAmount / $sellAmount;
            $quantity = $buyAmount;
            $cost = $sellAmount;
            $profit = $sellValueUSD - $buyValueUSD;
        }

        if ($profit !== null) {
            $profit = abs($profit) < 0.001 ? 0 : $profit;
        } else {
            $profit = 'N/A';
        }

        return [
            'symbol' => $symbol,
            'side' => $side,
            'buy_price' => $side === 'buy' ? $price : 1 / $price,
            'sell_price' => $side === 'sell' ? $price : 1 / $price,
            'quantity' => $quantity,
            'cost' => $cost,
            'profit_made' => $profit,
            'buy_symbol' => $buySymbol,
            'sell_symbol' => $sellSymbol,
            'buy_value_usd' => number_format($buyValueUSD, 2),
            'sell_value_usd' => number_format($sellValueUSD, 2)
        ];
    }

    public function formatTrades(array $trades)
    {
        return collect($trades)->map(function ($trade) {

            $buy = $trade['Trade']['Buy'] ?? null;
            $sell = $trade['Trade']['Sell'] ?? null;

            if (!$buy || !$sell)
                return null;

            return $this->calculateTradeDetails(
                $buy['Currency']['Symbol'] ?? null,
                $sell['Currency']['Symbol'] ?? null,
                $buy['Amount'] ?? 0,
                $sell['Amount'] ?? 0,
                $buy['PriceInUSD'] ?? 0,
                $sell['PriceInUSD'] ?? 0,
            );
        })->filter()->values();
    }
}