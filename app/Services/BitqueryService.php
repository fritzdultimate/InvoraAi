<?php

namespace App\Services;

use App\Models\LiveTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BitqueryService {

    protected static array $networks = [
        'eth' => [
            'protocols' => ['uniswap_v2', 'uniswap_v3'],
            'quotes' => [
                '0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2', // WETH
                '0xdac17f958d2ee523a2206206994597c13d831ec7', // USDT
                '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48', // USDC
                '0x6b175474e89094c44da98b954eedeac495271d0f', // DAI
            ],
        ],
        'bsc' => [
            'protocols' => ['pancake_swap_v3'],
            'quotes' => [
                '0xbb4cdb9cbd36b01bd1cbaebf2de08d9173bc095c', // WBNB
                '0x55d398326f99059ff775485246999027b3197955', // USDT (BSC)
                '0xe9e7cea3dedca5984780bafc599bd69add087d56', // BUSD
            ],
        ],
        'arbitrum' => [
            'protocols' => ['uniswap_v3'],
            'quotes' => [
                '0x82af49447d8a07e3bd95bd0d56f35241523fbab1', // WETH (Arbitrum)
                '0xff970a61a04b1ca14834a43f5de4533ebddb5cc8', // USDC.e
            ],
        ],
    ];

    private $stablecoins = [
        'USDT',
        'USDC',
        'DAI',
        'BUSD'
    ];

    public static function syncAll(): void {
        foreach (array_keys(self::$networks) as $network) {
            self::syncNetwork($network);
        }
    }

    public static function syncNetwork(string $network): void {
        $config = self::$networks[$network] ?? null;
        if (! $config) {
            return;
        }

        $query = <<<'GRAPHQL'
        query LatestTrades($network: evm_network!, $protocols: [String!], $quotes: [String!]) {
          EVM(network: $network, dataset: realtime) {
            DEXTradeByTokens(
              orderBy: {descending: Block_Time}
              limit: {count: 50}
              where: {
                TransactionStatus: {Success: true}
                Trade: {
                  Dex: {ProtocolName: {in: $protocols}}
                  Side: {Currency: {SmartContract: {in: $quotes}}}
                  Currency: {SmartContract: {notIn: $quotes}}
                }
              }
            ) {
              Block { Time }
              Transaction { Hash }
              Log { Index }
              Trade {
                Dex { ProtocolName }
                Currency { Symbol }
                Side {
                  Type
                  Currency { Symbol }
                }
                Price
                PriceInUSD
                Amount
                AmountInUSD
              }
            }
          }
        }
        GRAPHQL;

        $response = Http::withToken(config('services.bitquery.key'))
            ->post('https://streaming.bitquery.io/graphql', [
                'query' => $query,
                'variables' => [
                    'network' => $network,
                    'protocols' => $config['protocols'],
                    'quotes' => $config['quotes'],
                ],
            ]);

        if (! $response->successful()) {
            report(new \Exception("Bitquery sync failed for {$network}: {$response->body()}"));
            return;
        }

        $trades = data_get($response->json(), 'data.EVM.DEXTradeByTokens', []);

        foreach ($trades as $row) {
            $hash = data_get($row, 'Transaction.Hash');
            if (! $hash) {
                continue;
            }

            $baseSymbol = data_get($row, 'Trade.Currency.Symbol');
            $quoteSymbol = data_get($row, 'Trade.Side.Currency.Symbol');

            LiveTrade::updateOrCreate(
                [
                    'tx_hash' => $hash,
                    'log_index' => data_get($row, 'Log.Index', 0),
                ],
                [
                    'network' => $network,
                    'protocol' => data_get($row, 'Trade.Dex.ProtocolName'),
                    'dex' => self::prettyDex(data_get($row, 'Trade.Dex.ProtocolName')),
                    'pair' => "{$baseSymbol}/{$quoteSymbol}",
                    'base_symbol' => $baseSymbol,
                    'quote_symbol' => $quoteSymbol,
                    'side' => data_get($row, 'Trade.Side.Type'), // buy | sell
                    'price' => data_get($row, 'Trade.Price'),
                    'price_usd' => data_get($row, 'Trade.PriceInUSD'),
                    'amount' => data_get($row, 'Trade.Amount'),
                    'amount_usd' => data_get($row, 'Trade.AmountInUSD'),
                    'block_time' => Carbon::parse(data_get($row, 'Block.Time')),
                ]
            );
        }
    }

    protected static function prettyDex(?string $protocol): string {
        return match ($protocol) {
            'uniswap_v2' => 'Uniswap v2',
            'uniswap_v3' => 'Uniswap v3',
            'pancake_swap_v3' => 'PancakeSwap v3',
            default => ucfirst(str_replace('_', ' ', $protocol ?? 'Unknown')),
        };
    }

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

    private function calculateTradeDetails($buySymbol, $sellSymbol, $buyAmount, $sellAmount, $buyPriceInUSD, $sellPriceInUSD) {
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

    public function formatTrades(array $trades) {
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