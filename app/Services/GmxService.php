<?php

namespace App\Services;

use App\Models\LiveTrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GmxService {

    public static function syncAll(): void {
        $marketMeta = self::marketMetadataMap();

        $query = <<<'GRAPHQL'
        query RecentTrades {
          tradeActions(
            limit: 5
            orderBy: timestamp_DESC
            where: { eventName_eq: "OrderExecuted" }
          ) {
            id
            account
            sizeDeltaUsd
            executionPrice
            isLong
            transactionHash
            timestamp
            marketAddress
          }
        }
        GRAPHQL;

        $response = Http::post('https://gmx.squids.live/gmx-synthetics-arbitrum:prod/api/graphql', [
            'query' => $query,
        ]);

        if (! $response->successful()) {
            dd($response->body());
            report(new \Exception("GMX sync failed: {$response->body()}"));
            return;
        }

        $trades = data_get($response->json(), 'data.tradeActions', []);

        foreach ($trades as $row) {
            $marketAddress = strtolower(data_get($row, 'marketAddress', ''));
            $meta = $marketMeta[$marketAddress] ?? null;

            if (! $meta) {
                continue;
            }

            $coin = $meta['symbol'];
            $decimals = (int) $meta['decimals'];

            $sizeUsd = (float) data_get($row, 'sizeDeltaUsd') / 1e30;
            $priceUsd = (float) data_get($row, 'executionPrice') / (10 ** (30 - $decimals));
            $amount = $priceUsd > 0 ? $sizeUsd / $priceUsd : 0;

            // dd($coin, $decimals, $sizeUsd, $priceUsd, $amount);

            $hash = data_get($row, 'transactionHash');
            // $sizeUsd = data_get($row, 'sizeDeltaUsd');
            // $price = data_get($row, 'executionPrice');
            $isLong = data_get($row, 'isLong');
            $timestamp = data_get($row, 'timestamp');

            // dump($coin);

            if (! $hash || ! $coin || ! $sizeUsd || ! $timestamp) {
                // dump($hash, $coin, $sizeUsd);
                continue;
            }

            $idParts = explode(':', data_get($row, 'id', ''));
            $logIndex = $idParts[1] ?? 0;

            LiveTrade::updateOrCreate(
                [
                    'tx_hash' => $hash,
                    'log_index' => $logIndex,
                ],
                [
                    'network' => 'gmx',
                    'coin' => $coin,
                    'protocol' => 'gmx_v2',
                    'dex' => 'GMX',
                    'pair' => "{$coin}-PERP/USD",
                    'base_symbol' => $coin,
                    'quote_symbol' => 'USD',
                    'side' => $isLong ? 'buy' : 'sell',
                    'price' => $priceUsd,
                    'price_usd' => $priceUsd,
                    'amount' => (float) $amount,
                    'amount_usd' => $sizeUsd,
                    'block_time' => Carbon::createFromTimestamp($timestamp),
                    
                ]
            );
        }
    }

    protected static function marketMetadataMap(): array {
        return Cache::remember('gmx-market-metadata-map', now()->addHours(6), function () {
            $marketsResponse = Http::get('https://arbitrum-api.gmxinfra.io/markets/info');
            $tokensResponse = Http::get('https://arbitrum-api.gmxinfra.io/tokens');

            if (! $marketsResponse->successful() || ! $tokensResponse->successful()) {
                return [];
            }

            $tokens = collect(data_get($tokensResponse->json(), 'tokens', []))
                ->keyBy(fn ($t) => strtolower(data_get($t, 'address', '')));

            $map = [];

            foreach (data_get($marketsResponse->json(), 'markets', []) as $market) {
                $marketAddress = strtolower(data_get($market, 'marketToken', ''));
                $indexTokenAddress = strtolower(data_get($market, 'indexToken', ''));
                $tokenMeta = $tokens->get($indexTokenAddress);

                if ($marketAddress && $tokenMeta) {
                    $map[$marketAddress] = [
                        'symbol' => data_get($tokenMeta, 'symbol'),
                        'decimals' => data_get($tokenMeta, 'decimals'),
                    ];
                }
            }

            return $map;
        });
    }
}