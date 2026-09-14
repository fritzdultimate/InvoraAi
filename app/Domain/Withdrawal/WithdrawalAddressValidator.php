<?php

namespace App\Domain\Withdrawal;

/**
 * Confirms a withdrawal address is at least the right *shape* for the
 * currency/network the user picked — e.g. it won't let a Bitcoin address
 * through when ETH/ERC20 was selected. This is a format check, not proof
 * the address exists or belongs to the user; it exists purely to catch the
 * "picked one coin, pasted another coin's address" mistake before it turns
 * into an irreversible on-chain send.
 *
 * Resolution is network-first: for multi-chain tokens (USDT, USDC, ...)
 * the coin symbol alone says nothing about the address format — the same
 * USDT can need a Tron address on TRC20 or an EVM address on ERC20/BEP20 —
 * so the selected network's short code (withdrawal_networks.name, e.g.
 * "TRC20") is checked first. The currency code (withdrawal_currencies.code,
 * e.g. "BTC") is only used as a fallback, for single-network coins where
 * the code alone is unambiguous.
 *
 * Currencies/networks not present in the maps below are left unvalidated
 * rather than wrongly rejected: a coin or network the admin adds later
 * isn't blocked just because this list hasn't caught up to it yet. Extend
 * NETWORK_PATTERNS / CURRENCY_PATTERNS as new ones are added.
 */
class WithdrawalAddressValidator
{
    private const EVM_ADDRESS = '/^0x[a-fA-F0-9]{40}$/';
    private const BTC_ADDRESS = '/^(bc1[a-zA-HJ-NP-Z0-9]{25,90}|[13][a-km-zA-HJ-NP-Z1-9]{25,34})$/';
    private const LTC_ADDRESS = '/^(ltc1[a-zA-HJ-NP-Z0-9]{25,90}|[LM3][a-km-zA-HJ-NP-Z1-9]{26,34})$/';
    private const TRON_ADDRESS = '/^T[1-9A-HJ-NP-Za-km-z]{33}$/';
    private const SOLANA_ADDRESS = '/^[1-9A-HJ-NP-Za-km-z]{32,44}$/';
    private const BEP2_ADDRESS = '/^bnb1[a-z0-9]{38}$/';

    /**
     * Network short-name (withdrawal_networks.name, normalized upper-case
     * alphanumeric — e.g. "TRC20", "BEP-20" and "trc20" all match "TRC20")
     * -> regex. Checked before CURRENCY_PATTERNS.
     */
    private const NETWORK_PATTERNS = [
        'ERC20' => self::EVM_ADDRESS,
        'ETHEREUM' => self::EVM_ADDRESS,
        'BEP20' => self::EVM_ADDRESS,
        'BSC' => self::EVM_ADDRESS,
        'POLYGON' => self::EVM_ADDRESS,
        'MATIC' => self::EVM_ADDRESS,
        'ARBITRUM' => self::EVM_ADDRESS,
        'OPTIMISM' => self::EVM_ADDRESS,
        'AVALANCHE' => self::EVM_ADDRESS,
        'AVAXC' => self::EVM_ADDRESS,
        'TRC20' => self::TRON_ADDRESS,
        'TRON' => self::TRON_ADDRESS,
        'SPL' => self::SOLANA_ADDRESS,
        'SOLANA' => self::SOLANA_ADDRESS,
        'BEP2' => self::BEP2_ADDRESS,
        'OMNI' => self::BTC_ADDRESS,
        'BITCOIN' => self::BTC_ADDRESS,
        'LITECOIN' => self::LTC_ADDRESS,
    ];

    /**
     * Currency code (withdrawal_currencies.code) -> regex. Only consulted
     * when the network didn't already resolve a pattern, and deliberately
     * omits multi-network tokens (USDT, USDC, DAI, BUSD, ...) — with no
     * recognized network for one of those, we skip validation rather than
     * guess which chain the admin meant.
     */
    private const CURRENCY_PATTERNS = [
        'BTC' => self::BTC_ADDRESS,
        'ETH' => self::EVM_ADDRESS,
        'BNB' => self::EVM_ADDRESS,
        'LTC' => self::LTC_ADDRESS,
        'TRX' => self::TRON_ADDRESS,
        'SOL' => self::SOLANA_ADDRESS,
        'MATIC' => self::EVM_ADDRESS,
        'POL' => self::EVM_ADDRESS,
        'AVAX' => self::EVM_ADDRESS,
    ];

    /**
     * @return bool True if the address matches the expected format for
     *              this currency/network, or if neither is recognized
     *              (unvalidated, never wrongly rejected).
     */
    public static function isValid(string $address, ?string $currencyCode, ?string $networkName = null): bool
    {
        $pattern = self::resolvePattern($currencyCode, $networkName);

        if ($pattern === null) {
            return true;
        }

        return (bool) preg_match($pattern, trim($address));
    }

    /**
     * A short human label for the address family that was enforced (for
     * use in the validation error message), e.g. "TRC20" or "BTC". Null
     * when nothing was enforced for this currency/network.
     */
    public static function expectedLabel(?string $currencyCode, ?string $networkName = null): ?string
    {
        if (self::resolvePattern($currencyCode, $networkName) === null) {
            return null;
        }

        $label = $networkName ?: $currencyCode;

        return $label !== null ? strtoupper($label) : null;
    }

    private static function resolvePattern(?string $currencyCode, ?string $networkName): ?string
    {
        $network = self::normalize($networkName);

        if ($network !== null && isset(self::NETWORK_PATTERNS[$network])) {
            return self::NETWORK_PATTERNS[$network];
        }

        $currency = self::normalize($currencyCode);

        if ($currency !== null && isset(self::CURRENCY_PATTERNS[$currency])) {
            return self::CURRENCY_PATTERNS[$currency];
        }

        return null;
    }

    private static function normalize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $value));
    }
}
