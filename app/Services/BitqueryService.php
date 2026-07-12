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

        // dd('here');

        $query = <<<'GRAPHQL'
        query LatestTrades($network: evm_network!, $protocols: [String!], $quotes: [String!]) {
          EVM(network: $network, dataset: realtime) {
            DEXTradeByTokens(
              orderBy: {descending: Block_Time}
              limit: {count: 2}
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

        $response = Http::withHeaders([
            // 'X-API-KEY' => config('services.bitquery.key'),
            'Authorization' => 'Bearer ' . config('services.bitquery.key'),
        ])->post('https://streaming.bitquery.io/graphql', [
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

        // dd('here 2');

        $trades = data_get($response->json(), 'data.EVM.DEXTradeByTokens', []);

        foreach ($trades as $row) {
            $hash = data_get($row, 'Transaction.Hash');
            $baseSymbol = data_get($row, 'Trade.Currency.Symbol');
            $quoteSymbol = data_get($row, 'Trade.Side.Currency.Symbol');
            $side = data_get($row, 'Trade.Side.Type');
            $amount = data_get($row, 'Trade.Amount');
            $amountUsd = data_get($row, 'Trade.AmountInUSD');
            $blockTime = data_get($row, 'Block.Time');

            if (! $hash || ! $baseSymbol || ! $quoteSymbol || ! $side || ! $amount || ! $amountUsd || ! $blockTime) {
                continue;
            }

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
                    'block_time' => Carbon::parse($blockTime),
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
}