<?php

namespace App\Services;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NowPaymentsService {
    protected static $endpoint = 'https://api.nowpayments.io/v1';
    protected static $apiKey;
    protected static $ipnSecret;

    public function __construct() {

        $settings = Cache::remember(
            'payment_settings_nowpayments',
            now()->addMinutes(30), // cache for 30 mins
            fn () => PaymentSetting::where('provider', 'nowpayments')
                ->where('is_active', true)
                ->first()
        );

        // $settings = PaymentSetting::where('provider', 'nowpayments')
        //         ->where('is_active', true)
        //         ->first();


        self::$apiKey = $settings?->api_key ?? config('services.nowpayments.api_key');
        self::$ipnSecret = $settings?->ipn_secret ?? config('services.nowpayments.ipn_secret');
    }

    /**
     * Create invoice
     */
    public static function createInvoice($deposit) {
        $payload = [
            'price_amount' => (float) $deposit->amount,
            'price_currency' => 'usd',
            'order_id' => $deposit->id,
            'pay_currency' => strtoupper($deposit->currency),
            'ipn_callback_url' => route('webhooks.nowpayments'),
            'order_description' => $deposit->narration . ' | creaded by user - ' . Auth::id()
        ];

        $res = Http::withHeaders([
            'x-api-key' => self::$apiKey,
        ])->post(self::$endpoint . '/payment', $payload);

        if (!$res->successful()) {
            throw new \Exception("NOWPayments invoice error: " . $res->body());
        }

        return $res->json();
    }

    /**
     * Check invoice status
     */
    public static function checkInvoice($invoiceId)
    {
        $res = Http::withHeaders([
            'x-api-key' => self::$apiKey,
        ])->get(self::$endpoint . "/invoice/{$invoiceId}");

        if (!$res->successful()) {
            throw new \Exception("NOWPayments invoice check error: " . $res->body());
        }

        return $res->json();
    }

    /**
     * Verify webhook signature (HMAC-SHA512)
     */
    public static function verifySignature($rawPayload, $receivedSignature) {
        $settings = PaymentSetting::where('provider', 'nowpayments')
            ->where('is_active', true)
            ->first();
        \Log::info($settings->ipn_secret);

        if ($receivedSignature === null) {
            return false;
        }
        $expected = hash_hmac('sha512', $rawPayload, $settings->ipn_secret);

        return hash_equals($expected, $receivedSignature);
    }

    public function getStatus() {
        $response = Http::withHeaders([
            'x-api-key' => config('services.nowpayments.key'),
        ])->get('https://api.nowpayments.io/v1/status');

        return $response->json();
    }

    public function getCurrencies() {
        return Cache::remember('nowpayments_currencies', now()->addHours(1), function () {
            $response = Http::withHeaders([
                'x-api-key' => config('services.nowpayments.api_key'),
            ])->get('https://api.nowpayments.io/v1/merchant/coins');

            return $this->mapSelectedCurrencies($response->json());
        });
    }


    private function mapSelectedCurrencies(array $selectedCurrencies) {
        $nameMap = [
            'BTC'  => 'Bitcoin',
            'ETH'  => 'Ethereum',
            'LTC'  => 'Litecoin',
            'TRX'  => 'TRON',
            'USDT' => 'Tether',
            'USDC' => 'USD Coin',
        ];

        
        $networkSuffixes = ['ERC20','TRC20','BEP20','BEP2','OMNI','SPL','POLYGON','MATIC'];

        $out = [];

        foreach ($selectedCurrencies["selectedCurrencies"] as $raw) {
            $rawU = strtoupper(trim($raw));

            
            $foundNetwork = null;
            $symbol = $rawU;

            foreach ($networkSuffixes as $sfx) {
                if (str_ends_with($rawU, $sfx)) {
                    $foundNetwork = $sfx;
                    $symbol = substr($rawU, 0, strlen($rawU) - strlen($sfx));
                    break;
                }
            }

            
            $symbol = trim($symbol);

            
            if ($symbol === '') continue;

            
            if (! isset($out[$symbol])) {
                $symbol_lower = trim(strtolower($symbol));
                $out[$symbol] = [
                    'currency'    => $symbol,
                    'label'       => $nameMap[$symbol] ?? ucfirst(strtolower($symbol)),
                    'networks'    => [],
                    'bg' => 'halpha-bg-' . $symbol_lower,
                    'icon' => 'icon-' . $symbol_lower,
                    'raw_entries' => [],
                    'meta'        => null,
                ];
            }

            
            $out[$symbol]['raw_entries'][] = $rawU;

            $networkObj = [
                'network'            => $foundNetwork,
                'label'              => $foundNetwork ? $foundNetwork : 'mainnet',
                'raw'                => $rawU,
                'min_amount'         => null,
                'max_amount'         => null,
                'deposit_enabled'    => null,
                'withdrawal_enabled' => null,
                'extra'              => null,
            ];

            // avoid duplicates
            $already = false;
            foreach ($out[$symbol]['networks'] as $n) {
                if (strtoupper($n['network'] ?? '') === strtoupper($networkObj['network'] ?? '')) {
                    $already = true;
                    break;
                }
                if (empty($n['network']) && empty($networkObj['network'])) {
                    $already = true;
                    break;
                }
            }

            if (! $already) {
                $out[$symbol]['networks'][] = $networkObj;
            }
        }

        return array_values($out);
    }

    public function getPaymentStatus($invoiceId) {
        $res = Http::withHeaders([
            'x-api-key' => self::$apiKey,
        ])->get(self::$endpoint . "/payment/$invoiceId");

        if (!$res->successful()) {
            throw new \Exception("NOWPayments invoice error: " . $res->body());
        }

        return $res->json();
    }

}
