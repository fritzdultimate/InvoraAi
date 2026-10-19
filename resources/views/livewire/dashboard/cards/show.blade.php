@php
    use App\Enums\CardOrderStatus;
    $status = $order->status;
    $currentIndex = array_search($status, $journey, true);
@endphp

<div class="ic-page">
    <a href="{{ route('cards') }}" class="ic-back"><iconify-icon icon="solar:arrow-left-linear"></iconify-icon> All cards</a>

    <section class="ic-detail">
        <div class="ic-detail__card">
            <div class="ic-hero__aurora" aria-hidden="true"></div>
            <x-cards.visual :network="$order->network" :design="$order->design" :name="$order->name_on_card" :last4="$order->last_four" :expiry="$order->expiry" size="lg" flip />

            <div class="ic-detail__headline">
                <span class="ic-status ic-status--{{ $status->color() }}">{{ $status->label() }}</span>
                <h1 class="ic-h2">{{ $order->card_name }}</h1>
                <p class="ic-muted ic-mono">{{ $order->reference }}</p>
            </div>

            @if ($order->user_message)
                <div class="ic-banner {{ $status === CardOrderStatus::REJECTED ? 'ic-banner--warn' : 'ic-banner--info' }}">
                    <iconify-icon icon="solar:chat-round-line-bold" class="text-xl"></iconify-icon>
                    <p>{{ $order->user_message }}</p>
                </div>
            @endif

            @if ($status === CardOrderStatus::SHIPPED && $order->tracking_number)
                <div class="ic-track">
                    <div>
                        <small>{{ $order->courier ?: 'Courier' }}</small>
                        <p class="ic-mono">{{ $order->tracking_number }}</p>
                    </div>
                    @if ($order->tracking_url)
                        <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="ic-btn ic-btn--sm">Track parcel</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="ic-detail__side">
            <div class="ic-panel">
                <h2 class="ic-h4">Progress</h2>
                <ol class="ic-timeline">
                    @foreach ($journey as $i => $step)
                        @php
                            $done = $currentIndex !== false && $i <= $currentIndex;
                            $time = $order->stepTime($step);
                        @endphp
                        <li @class(['is-done' => $done, 'is-current' => $i === $currentIndex, 'is-bad' => $step->isClosed()])>
                            <span class="ic-timeline__dot"></span>
                            <div>
                                <p>{{ $step === CardOrderStatus::PENDING ? 'Pre-order placed' : $step->label() }}</p>
                                <small>{{ $done && $time ? $time->format('d M Y, H:i') : 'Waiting' }}</small>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="ic-panel">
                <h2 class="ic-h4">Order details</h2>
                <dl class="ic-review">
                    <div><dt>Type</dt><dd>{{ $order->form->label() }}</dd></div>
                    <div><dt>Card limit</dt><dd>{{ $order->limitLabel() }}</dd></div>
                    <div><dt>Name on card</dt><dd class="ic-mono">{{ $order->name_on_card }}</dd></div>
                    @if ($order->isPhysical())
                        <div><dt>Deliver to</dt><dd>{{ $order->shippingAddress() }}</dd></div>
                    @endif
                    <div><dt>Paid from</dt><dd>{{ \App\Enums\LedgerAsset::from($order->paid_from)->label() }}</dd></div>
                    <div class="ic-review__total"><dt>Pre-order fee</dt><dd>${{ number_format($order->fee, 2) }}</dd></div>
                    @if ($order->refunded_at)
                        <div><dt>Refunded</dt><dd class="ic-good">${{ number_format($order->fee, 2) }} · {{ $order->refunded_at->format('d M Y') }}</dd></div>
                    @endif
                </dl>
            </div>

            @if ($canCancel)
                <div class="ic-panel ic-panel--quiet" x-data="{ confirm: false }">
                    <p class="ic-muted">Changed your mind? You can cancel while your pre-order is {{ strtolower($status->label()) }} and get the full fee back.</p>
                    <button type="button" class="ic-btn ic-btn--danger ic-btn--block" x-show="!confirm" x-on:click="confirm = true">Cancel pre-order</button>
                    <div x-show="confirm" x-cloak class="ic-confirm">
                        <p>Cancel and refund ${{ number_format($order->fee, 2) }}?</p>
                        <div>
                            <button type="button" class="ic-btn ic-btn--ghost" x-on:click="confirm = false">Keep it</button>
                            <button type="button" class="ic-btn ic-btn--danger" wire:click="cancel" wire:loading.attr="disabled">Yes, cancel</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
