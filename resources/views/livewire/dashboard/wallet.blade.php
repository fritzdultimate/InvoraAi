@push('styles')
<style>
    .iw { width: 100%; min-width: 0; max-width: 1100px; margin: 0 auto; display: grid; gap: 20px; }
    .iw > * { min-width: 0; }
    .iw-convert-form, .iw-rows, .iw-list { grid-template-columns: minmax(0, 1fr); }
    .iw-convert-form > *, .iw-summary > *, .iw-convert > * { min-width: 0; }
    .iw-source strong, .iw-source span { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .iw-amount input { width: 100%; }
    .iw-amount .iw-max { flex: 0 0 auto; }
    .iw-chip { min-width: 0; }
    .iw-to { min-width: 0; }
    .iw-to small { white-space: nowrap; }
    .iw-head h1 { font-size: 26px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; }
    .iw-head p { color: var(--text-secondary); font-size: 14px; margin-top: 4px; }

    .iw-card { background: linear-gradient(160deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01)); border: 1px solid var(--border); border-radius: 20px; }

    /* total */
    .iw-hero { position: relative; overflow: hidden; padding: 24px; display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; justify-content: space-between;
        background: radial-gradient(120% 140% at 0% 0%, rgba(99,102,241,0.28), transparent 55%), linear-gradient(160deg, #141a2e, #0d1222); }
    .iw-hero::after { content: ''; position: absolute; right: -60px; top: -60px; width: 220px; height: 220px; border-radius: 50%; background: rgba(99,102,241,0.12); filter: blur(10px); }
    .iw-label { font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-secondary); font-weight: 600; }
    .iw-total { font-size: 38px; font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-top: 6px; font-variant-numeric: tabular-nums; }
    .iw-split { display: flex; height: 6px; border-radius: 6px; overflow: hidden; background: rgba(255,255,255,0.06); margin-top: 14px; width: min(360px, 70vw); }
    .iw-split span { display: block; height: 100%; }
    .iw-legend { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 10px; font-size: 12px; color: var(--text-secondary); }
    .iw-legend i { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; }
    .iw-hero-actions { display: flex; gap: 10px; position: relative; z-index: 1; }
    .iw-btn { display: inline-flex; align-items: center; gap: 8px; height: 44px; padding: 0 18px; border-radius: 12px; font-weight: 600; font-size: 14px; transition: .2s; border: 1px solid transparent; }
    .iw-btn-primary { background: var(--accent); color: #fff; box-shadow: 0 8px 24px var(--accent-glow); }
    .iw-btn-primary:hover { filter: brightness(1.1); }
    .iw-btn-primary:disabled { opacity: .45; box-shadow: none; cursor: not-allowed; }
    .iw-btn-ghost { background: rgba(255,255,255,0.04); border-color: var(--border); color: var(--text-primary); }
    .iw-btn-ghost:hover { background: rgba(255,255,255,0.08); }

    /* balances */
    .iw-tiles { display: grid; gap: 14px; grid-template-columns: repeat(3, 1fr); }
    @media (max-width: 860px) { .iw-tiles { grid-template-columns: 1fr; } }
    .iw-tile { padding: 18px; display: flex; flex-direction: column; gap: 14px; }
    .iw-tile-top { display: flex; align-items: center; justify-content: space-between; }
    .iw-icon { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-size: 20px; }
    .iw-tag { font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 8px; background: rgba(255,255,255,0.05); color: var(--text-secondary); }
    .iw-tile-amount { font-size: 24px; font-weight: 700; color: var(--text-primary); font-variant-numeric: tabular-nums; }
    .iw-tile-name { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }
    .iw-tile-btn { align-self: flex-start; font-size: 13px; font-weight: 600; color: #a5b4fc; display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 10px; background: rgba(99,102,241,0.1); transition: .2s; }
    .iw-tile-btn:hover { background: rgba(99,102,241,0.2); }

    /* converter */
    .iw-convert { display: grid; grid-template-columns: 1.15fr 1fr; }
    @media (max-width: 860px) { .iw-convert { grid-template-columns: 1fr; } }
    .iw-convert-form { padding: 22px; display: grid; gap: 18px; }
    .iw-convert-form h2, .iw-history h2 { font-size: 17px; font-weight: 700; color: var(--text-primary); }
    .iw-sub { font-size: 13px; color: var(--text-secondary); margin-top: 3px; }
    .iw-field-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block; }
    .iw-sources { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .iw-source { text-align: left; padding: 12px 14px; border-radius: 14px; border: 1px solid var(--border); background: rgba(255,255,255,0.02); transition: .2s; }
    .iw-source strong { display: block; font-size: 14px; color: var(--text-primary); }
    .iw-source span { font-size: 12px; color: var(--text-secondary); font-variant-numeric: tabular-nums; }
    .iw-source.active { border-color: var(--accent); background: rgba(99,102,241,0.1); box-shadow: 0 0 0 3px var(--accent-glow); }
    .iw-to { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 14px; border: 1px dashed var(--border); color: var(--text-primary); font-size: 14px; }
    .iw-to small { margin-left: auto; color: var(--text-secondary); font-variant-numeric: tabular-nums; }
    .iw-amount { display: flex; align-items: center; height: 56px; border-radius: 14px; border: 1px solid var(--border); background: rgba(0,0,0,0.25); padding: 0 8px 0 16px; transition: .2s; }
    .iw-amount:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .iw-amount.bad { border-color: rgba(239,68,68,0.6); box-shadow: 0 0 0 3px rgba(239,68,68,0.12); }
    .iw-amount b { color: var(--text-secondary); font-size: 20px; font-weight: 600; margin-right: 6px; }
    .iw-amount input { flex: 1; min-width: 0; background: transparent; border: 0; outline: 0; color: #fff; font-size: 22px; font-weight: 700; font-variant-numeric: tabular-nums; }
    .iw-amount input::placeholder { color: rgba(255,255,255,0.2); }
    .iw-max { height: 34px; padding: 0 12px; border-radius: 10px; background: rgba(99,102,241,0.15); color: #c7d2fe; font-size: 12px; font-weight: 700; letter-spacing: .04em; }
    .iw-chips { display: flex; gap: 8px; margin-top: 10px; }
    .iw-chip { flex: 1; height: 32px; border-radius: 10px; border: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-secondary); transition: .2s; }
    .iw-chip:hover { color: var(--text-primary); border-color: rgba(255,255,255,0.18); }
    .iw-error { font-size: 12px; color: #f87171; margin-top: 8px; }
    .iw-note { font-size: 12px; color: var(--text-secondary); display: flex; gap: 14px; flex-wrap: wrap; }
    .iw-note span { display: inline-flex; align-items: center; gap: 5px; }

    .iw-summary { padding: 22px; border-left: 1px solid var(--border-soft); background: rgba(0,0,0,0.18); border-radius: 0 20px 20px 0; display: flex; flex-direction: column; gap: 14px; }
    @media (max-width: 860px) { .iw-summary { border-left: 0; border-top: 1px solid var(--border-soft); border-radius: 0 0 20px 20px; } }
    .iw-receive { font-size: 34px; font-weight: 800; color: #fff; letter-spacing: -0.02em; font-variant-numeric: tabular-nums; }
    .iw-rows { display: grid; gap: 10px; }
    .iw-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--text-secondary); }
    .iw-row strong { color: var(--text-primary); font-weight: 600; font-variant-numeric: tabular-nums; }
    .iw-row .arrow { color: var(--text-secondary); margin: 0 6px; }
    .iw-row .up { color: #4ade80; }
    .iw-divider { height: 1px; background: var(--border-soft); }

    /* stats + history */
    .iw-stats { display: grid; gap: 14px; grid-template-columns: repeat(3, 1fr); }
    @media (max-width: 860px) { .iw-stats { grid-template-columns: 1fr; } }
    .iw-stat { padding: 16px 18px; }
    .iw-stat strong { display: block; font-size: 20px; font-weight: 700; color: var(--text-primary); margin-top: 6px; font-variant-numeric: tabular-nums; }
    .iw-history { padding: 20px 22px; }
    .iw-list { margin-top: 14px; display: grid; }
    .iw-item { display: flex; align-items: center; gap: 14px; padding: 13px 0; border-top: 1px solid var(--border-soft); }
    .iw-item:first-child { border-top: 0; }
    .iw-item-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .iw-item-when { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
    .iw-item-amount { margin-left: auto; font-weight: 700; color: #4ade80; font-variant-numeric: tabular-nums; }
    .iw-empty { text-align: center; padding: 34px 10px; color: var(--text-secondary); font-size: 14px; }
    .iw-empty iconify-icon { font-size: 34px; opacity: .5; display: block; margin: 0 auto 8px; }

    /* confirm */
    .iw-overlay { position: fixed; inset: 0; z-index: 9999; display: grid; place-items: center; padding: 16px; background: rgba(2,6,23,0.72); backdrop-filter: blur(8px); }
    .iw-dialog { width: 100%; max-width: 400px; padding: 24px; border-radius: 22px; background: linear-gradient(160deg, #151b30, #0d1222); border: 1px solid var(--border); box-shadow: 0 30px 80px rgba(0,0,0,0.6); }
    .iw-dialog h3 { font-size: 18px; font-weight: 700; color: #fff; }
    .iw-flow { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 18px 0; padding: 14px; border-radius: 14px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-soft); }
    .iw-flow div { text-align: center; flex: 1; }
    .iw-flow small { display: block; font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .06em; }
    .iw-flow strong { color: #fff; font-size: 15px; }

    .iw, .iw-tiles, .iw-convert, .iw-stats { grid-template-columns: minmax(0, 1fr); }
    .iw-tiles { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .iw-convert { grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr); }
    .iw-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .iw-item > div:nth-child(2) { min-width: 0; }
    .iw-item-title { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .iw-item-amount { white-space: nowrap; }

    @media (max-width: 860px) {
        .iw-tiles, .iw-convert, .iw-stats { grid-template-columns: minmax(0, 1fr); }
    }

    @media (max-width: 600px) {
        .iw { gap: 14px; }
        .iw-head h1 { font-size: 22px; }
        .iw-head p { font-size: 13px; }

        .iw-hero { padding: 18px; flex-direction: column; align-items: stretch; gap: 16px; }
        .iw-total { font-size: 30px; }
        .iw-split { width: 100%; }
        .iw-hero-actions { width: 100%; }
        .iw-hero-actions .iw-btn { flex: 1; justify-content: center; }

        /* balances become a compact list */
        .iw-tiles { gap: 10px; }
        .iw-tile { padding: 14px; flex-direction: row; flex-wrap: wrap; align-items: center; gap: 12px; }
        .iw-tile-top { flex: 0 0 auto; }
        .iw-tile-top .iw-tag { display: none; }
        .iw-tile > div:nth-child(2) { flex: 1; min-width: 0; }
        .iw-tile-amount { font-size: 19px; }
        .iw-tile-name { font-size: 12px; }
        .iw-tile-btn { align-self: center; padding: 6px 9px; font-size: 12px; }

        .iw-convert-form, .iw-summary { padding: 16px; }
        .iw-sources { gap: 8px; }
        .iw-source { padding: 10px 12px; }
        .iw-source strong { font-size: 13px; }
        .iw-amount { height: 52px; padding-left: 12px; }
        .iw-amount input { font-size: 20px; }
        .iw-chips { gap: 6px; }
        .iw-chip { font-size: 11px; }
        .iw-receive { font-size: 28px; }
        .iw-row { gap: 10px; flex-wrap: wrap; }
        .iw-row > span:last-child { margin-left: auto; text-align: right; }
        .iw-summary .iw-btn { width: 100%; }

        .iw-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
        .iw-stat { padding: 12px; }
        .iw-stat .iw-label { font-size: 10px; letter-spacing: 0.04em; }
        .iw-stat strong { font-size: 15px; overflow-wrap: anywhere; }

        .iw-history { padding: 16px; }
        .iw-item { gap: 10px; }
        .iw-item .iw-icon { width: 34px; height: 34px; font-size: 17px; flex: 0 0 auto; }
        .iw-item-title { font-size: 13px; }
        .iw-item-when { font-size: 11px; }

        /* confirm slides up from the bottom like a sheet */
        .iw-overlay { place-items: end center; padding: 0; }
        .iw-dialog { max-width: none; border-radius: 22px 22px 0 0; padding: 20px 18px calc(20px + env(safe-area-inset-bottom)); }
    }

    @media (max-width: 360px) {
        .iw-stats { grid-template-columns: minmax(0, 1fr); }
        .iw-sources { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@php
    $total = $balances['main'] + $balances['profit'] + $balances['referral'];
    $share = fn ($v) => $total > 0 ? round($v / $total * 100, 2) : 0;
@endphp

<div class="iw" x-data="walletConvert(@js($minimum))">

    <div class="iw-head">
        <h1>Wallet</h1>
        <p>All your balances in one place. Move profit and referral earnings into your main balance any time.</p>
    </div>

    {{-- total --}}
    <div class="iw-card iw-hero">
        <div style="position: relative; z-index: 1">
            <div class="iw-label">Total balance</div>
            <div class="iw-total">${{ number_format($total, 2) }}</div>
            <div class="iw-split">
                <span style="width: {{ $share($balances['main']) }}%; background: #6366f1"></span>
                <span style="width: {{ $share($balances['profit']) }}%; background: #22c55e"></span>
                <span style="width: {{ $share($balances['referral']) }}%; background: #f59e0b"></span>
            </div>
            <div class="iw-legend">
                <span><i style="background:#6366f1"></i>Main</span>
                <span><i style="background:#22c55e"></i>Profit</span>
                <span><i style="background:#f59e0b"></i>Referral</span>
            </div>
        </div>
        <div class="iw-hero-actions">
            <a href="{{ route('deposit') }}" class="iw-btn iw-btn-primary"><iconify-icon icon="solar:card-send-bold"></iconify-icon> Deposit</a>
            <a href="{{ route('withdrawal') }}" class="iw-btn iw-btn-ghost"><iconify-icon icon="ph:coins-bold"></iconify-icon> Withdraw</a>
        </div>
    </div>

    {{-- balances --}}
    <div class="iw-tiles">
        <div class="iw-card iw-tile">
            <div class="iw-tile-top">
                <div class="iw-icon" style="background: rgba(99,102,241,0.15); color: #a5b4fc"><iconify-icon icon="solar:wallet-money-bold"></iconify-icon></div>
                <span class="iw-tag">Withdrawable</span>
            </div>
            <div>
                <div class="iw-tile-amount">${{ number_format($balances['main'], 2) }}</div>
                <div class="iw-tile-name">Main balance</div>
            </div>
            <a href="{{ route('withdrawal') }}" class="iw-tile-btn">Withdraw <iconify-icon icon="mdi:arrow-right"></iconify-icon></a>
        </div>

        <div class="iw-card iw-tile">
            <div class="iw-tile-top">
                <div class="iw-icon" style="background: rgba(34,197,94,0.14); color: #4ade80"><iconify-icon icon="mdi:trending-up"></iconify-icon></div>
                <span class="iw-tag">From your bots</span>
            </div>
            <div>
                <div class="iw-tile-amount">${{ number_format($balances['profit'], 2) }}</div>
                <div class="iw-tile-name">Profit balance</div>
            </div>
            <button type="button" class="iw-tile-btn" @click="pick('profit')">Convert to main <iconify-icon icon="mdi:swap-horizontal"></iconify-icon></button>
        </div>

        <div class="iw-card iw-tile">
            <div class="iw-tile-top">
                <div class="iw-icon" style="background: rgba(245,158,11,0.14); color: #fbbf24"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
                <span class="iw-tag">From your team</span>
            </div>
            <div>
                <div class="iw-tile-amount">${{ number_format($balances['referral'], 2) }}</div>
                <div class="iw-tile-name">Referral bonus</div>
            </div>
            <button type="button" class="iw-tile-btn" @click="pick('referral')">Convert to main <iconify-icon icon="mdi:swap-horizontal"></iconify-icon></button>
        </div>
    </div>

    {{-- converter --}}
    <div class="iw-card iw-convert" id="convert" x-ref="convert">
        <div class="iw-convert-form">
            <div>
                <h2>Convert to main balance</h2>
                <div class="iw-sub">Your main balance is what you can withdraw.</div>
            </div>

            <div>
                <span class="iw-field-label">From</span>
                <div class="iw-sources">
                    <button type="button" class="iw-source" :class="from === 'profit' && 'active'" @click="from = 'profit'">
                        <strong>Profit</strong>
                        <span x-text="money(balance('profit'))"></span>
                    </button>
                    <button type="button" class="iw-source" :class="from === 'referral' && 'active'" @click="from = 'referral'">
                        <strong>Referral bonus</strong>
                        <span x-text="money(balance('referral'))"></span>
                    </button>
                </div>
            </div>

            <div>
                <span class="iw-field-label">To</span>
                <div class="iw-to">
                    <iconify-icon icon="solar:wallet-money-bold" style="color:#a5b4fc"></iconify-icon> Main balance
                    <small x-text="money(balance('main'))"></small>
                </div>
            </div>

            <div>
                <span class="iw-field-label">Amount</span>
                <div class="iw-amount" :class="problem && amount !== '' && 'bad'">
                    <b>$</b>
                    <input type="text" inputmode="decimal" placeholder="0.00" x-model="amount" x-ref="amount"
                        @input="amount = amount.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').replace(/^(\d*\.\d{0,2}).*$/, '$1')"
                        @keydown.enter.prevent="ask()">
                    <button type="button" class="iw-max" @click="part(1)">MAX</button>
                </div>
                <div class="iw-chips">
                    <template x-for="p in [0.25, 0.5, 0.75]">
                        <button type="button" class="iw-chip" @click="part(p)" x-text="(p * 100) + '%'"></button>
                    </template>
                    <button type="button" class="iw-chip" @click="part(1)">All</button>
                </div>
                <div class="iw-error" x-show="problem && amount !== ''" x-text="problem" x-cloak></div>
            </div>

            <div class="iw-note">
                <span><iconify-icon icon="mdi:information-outline"></iconify-icon> Minimum <span x-text="money(min)"></span></span>
                <span><iconify-icon icon="mdi:lightning-bolt"></iconify-icon> Instant</span>
                <span><iconify-icon icon="mdi:tag-off-outline"></iconify-icon> No fee</span>
            </div>
        </div>

        <div class="iw-summary">
            <div>
                <div class="iw-label">You'll receive</div>
                <div class="iw-receive" x-text="money(value())"></div>
            </div>
            <div class="iw-divider"></div>
            <div class="iw-rows">
                <div class="iw-row">
                    <span x-text="from === 'profit' ? 'Profit balance' : 'Referral bonus'"></span>
                    <span><strong x-text="money(balance(from))"></strong><span class="arrow">→</span><strong x-text="money(Math.max(0, balance(from) - value()))"></strong></span>
                </div>
                <div class="iw-row">
                    <span>Main balance</span>
                    <span><strong x-text="money(balance('main'))"></strong><span class="arrow">→</span><strong class="up" x-text="money(balance('main') + (problem ? 0 : value()))"></strong></span>
                </div>
                <div class="iw-row"><span>Fee</span><strong>Free</strong></div>
                <div class="iw-row"><span>Arrives</span><strong>Instantly</strong></div>
            </div>
            <button type="button" class="iw-btn iw-btn-primary" style="justify-content:center; margin-top:auto" :disabled="!!problem || busy" @click="ask()">
                <span x-show="!busy" x-text="value() > 0 ? 'Convert ' + money(value()) : 'Enter an amount'"></span>
                <span x-show="busy" x-cloak>Converting…</span>
            </button>
        </div>
    </div>

    {{-- totals --}}
    <div class="iw-stats">
        <div class="iw-card iw-stat"><div class="iw-label">Converted from profit</div><strong>${{ number_format($fromProfit, 2) }}</strong></div>
        <div class="iw-card iw-stat"><div class="iw-label">Converted from referral</div><strong>${{ number_format($fromReferral, 2) }}</strong></div>
        <div class="iw-card iw-stat"><div class="iw-label">Conversions</div><strong>{{ number_format($times) }}</strong></div>
    </div>

    {{-- history --}}
    <div class="iw-card iw-history">
        <h2>Conversion history</h2>
        <div class="iw-list">
            @forelse ($history as $row)
                @php $isProfit = $row->reference_type === \App\Enums\LedgerReference::PROFITTRANSFER; @endphp
                <div class="iw-item">
                    <div class="iw-icon" style="{{ $isProfit ? 'background: rgba(34,197,94,0.14); color: #4ade80' : 'background: rgba(245,158,11,0.14); color: #fbbf24' }}">
                        <iconify-icon icon="{{ $isProfit ? 'mdi:trending-up' : 'mdi:account-group' }}"></iconify-icon>
                    </div>
                    <div>
                        <div class="iw-item-title">{{ $isProfit ? 'Profit' : 'Referral bonus' }} → Main balance</div>
                        <div class="iw-item-when">{{ $row->created_at->format('M j, Y · g:i A') }}</div>
                    </div>
                    <div class="iw-item-amount">+${{ number_format($row->credit, 2) }}</div>
                </div>
            @empty
                <div class="iw-empty">
                    <iconify-icon icon="mdi:swap-horizontal-circle-outline"></iconify-icon>
                    No conversions yet. When you move profit or referral earnings to your main balance, they'll show here.
                </div>
            @endforelse
        </div>
        @if ($history->hasPages())
            <div style="margin-top: 14px">{{ $history->links() }}</div>
        @endif
    </div>

    {{-- confirm --}}
    <div class="iw-overlay" x-show="confirming" x-transition.opacity x-cloak @keydown.escape.window="confirming = false" @click.self="confirming = false">
        <div class="iw-dialog" x-transition>
            <h3>Confirm conversion</h3>
            <div class="iw-sub">This moves money inside your account. It can't be undone.</div>
            <div class="iw-flow">
                <div><small x-text="from === 'profit' ? 'Profit' : 'Referral'"></small><strong x-text="money(value())"></strong></div>
                <iconify-icon icon="mdi:arrow-right" style="color:#a5b4fc; font-size:20px"></iconify-icon>
                <div><small>Main</small><strong x-text="money(value())"></strong></div>
            </div>
            <div style="display:flex; gap:10px">
                <button type="button" class="iw-btn iw-btn-ghost" style="flex:1; justify-content:center" @click="confirming = false">Cancel</button>
                <button type="button" class="iw-btn iw-btn-primary" style="flex:1; justify-content:center" :disabled="busy" @click="go()">
                    <span x-show="!busy">Yes, convert</span><span x-show="busy" x-cloak>Converting…</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function walletConvert(min) {
        return {
            min: Number(min),
            from: 'profit',
            amount: '',
            busy: false,
            confirming: false,

            balance(key) {
                return Number(this.$wire.balances?.[key] ?? 0);
            },
            value() {
                const v = parseFloat(this.amount);
                return isNaN(v) ? 0 : Math.round(v * 100) / 100;
            },
            get problem() {
                const v = this.value();
                if (v <= 0) return 'Enter an amount to convert.';
                if (v < this.min) return 'The minimum is ' + this.money(this.min) + '.';
                if (v > this.balance(this.from)) return 'That is more than your ' + (this.from === 'profit' ? 'profit' : 'referral') + ' balance.';
                return '';
            },
            money(v) {
                return '$' + Number(v || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            part(p) {
                const v = Math.floor(this.balance(this.from) * p * 100) / 100;
                this.amount = v > 0 ? v.toFixed(2) : '';
            },
            pick(key) {
                this.from = key;
                this.$refs.convert.scrollIntoView({ behavior: 'smooth', block: 'start' });
                setTimeout(() => this.$refs.amount.focus(), 350);
            },
            ask() {
                if (!this.problem) this.confirming = true;
            },
            async go() {
                this.busy = true;
                const ok = await this.$wire.convert(this.from, this.amount);
                this.busy = false;
                this.confirming = false;
                if (ok) this.amount = '';
            },
        };
    }
</script>
@endpush
