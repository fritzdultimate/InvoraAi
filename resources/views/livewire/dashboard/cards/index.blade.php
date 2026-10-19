@php
    use App\Enums\CardForm;
    use App\Models\CardProduct;
    use App\Services\Card\CardProgram;

    $open = CardProgram::isOpen();
    $all = CardProduct::available()->get();
    $maxLimit = max((float) $all->max('spending_limit'), 1);
    $lowestFee = $all->min('preorder_fee');
    $offersVirtual = $all->contains(fn ($p) => $p->offers(CardForm::VIRTUAL));
    $offersPhysical = $all->contains(fn ($p) => $p->offers(CardForm::PHYSICAL));
    $first = auth()->user()->name;
@endphp

<div class="ic-page">

    {{-- ============ HERO ============ --}}
    <section class="ic-hero">
        <div class="ic-hero__aurora" aria-hidden="true"></div>
        <div class="ic-hero__grid" aria-hidden="true"></div>

        <div class="ic-hero__copy">
            <span class="ic-pill {{ $open ? 'ic-pill--live' : 'ic-pill--muted' }}"><i></i>{{ $open ? 'Pre-orders now open' : 'Pre-orders paused' }}</span>
            <h1 class="ic-hero__title">Your Invora,<br><span>in your wallet.</span></h1>
            <p class="ic-hero__lead">Reserve a Visa or Mastercard in a few taps. Pay the pre-order fee from your balance, follow every step of production and delivery, and get the full fee back if your order is cancelled or not approved.</p>

            <div class="ic-hero__cta">
                <a href="#catalogue" class="ic-btn ic-btn--lg">Choose your card <iconify-icon icon="solar:arrow-right-linear"></iconify-icon></a>
                <a href="#how" class="ic-btn ic-btn--ghost ic-btn--lg">How it works</a>
            </div>

            <ul class="ic-hero__trust">
                <li><iconify-icon icon="solar:shield-check-bold"></iconify-icon>Fee refunded if not approved</li>
                <li><iconify-icon icon="solar:map-point-wave-bold"></iconify-icon>Live order tracking</li>
                @if ($lowestFee)
                    <li><iconify-icon icon="solar:tag-price-bold"></iconify-icon>From ${{ number_format($lowestFee) }}</li>
                @endif
            </ul>
        </div>

        <div class="ic-stage" aria-hidden="true">
            <div class="ic-stage__ring"></div>
            <div class="ic-stage__ring ic-stage__ring--2"></div>
            <x-cards.visual network="mastercard" design="gold" class="ic-stage__back" :tilt="false" :name="$first" />
            <x-cards.visual network="visa" design="sapphire" class="ic-stage__front" :name="$first" flip />
            <span class="ic-float ic-float--1"><iconify-icon icon="solar:wi-fi-router-minimalistic-bold"></iconify-icon>Contactless</span>
            <span class="ic-float ic-float--2"><iconify-icon icon="solar:card-transfer-bold"></iconify-icon>Up to ${{ number_format($maxLimit >= 1000 ? $maxLimit / 1000 : $maxLimit) }}{{ $maxLimit >= 1000 ? 'k' : '' }} limit</span>
            <span class="ic-float ic-float--3"><i></i>Tap the card to flip it</span>
        </div>
    </section>

    @if ($notice)
        <div class="ic-banner ic-banner--info">
            <iconify-icon icon="solar:info-circle-bold" class="text-xl"></iconify-icon>
            <p>{{ $notice }}</p>
        </div>
    @endif

    @if ($blocked)
        <div class="ic-banner ic-banner--warn">
            <iconify-icon icon="solar:shield-warning-bold" class="text-xl"></iconify-icon>
            <p>{{ $blocked }}</p>
            @if (str_contains($blocked, 'KYC'))
                <a href="{{ route('profile') }}" class="ic-btn ic-btn--sm">Verify now</a>
            @endif
        </div>
    @endif

    {{-- ============ MY PRE-ORDERS ============ --}}
    @if ($orders->isNotEmpty())
        <section class="ic-section">
            <div class="ic-section__head">
                <div>
                    <p class="ic-eyebrow">Your cards</p>
                    <h2 class="ic-h2">My pre-orders</h2>
                </div>
            </div>
            <div class="ic-orders">
                @foreach ($orders as $order)
                    @php
                        $journey = \App\Enums\CardOrderStatus::journey($order->form);
                        $pos = array_search($order->status, $journey, true);
                        $progress = $pos === false ? 100 : max(round($pos / (count($journey) - 1) * 100), 8);
                    @endphp
                    <a href="{{ route('cards.order', $order) }}" class="ic-order">
                        <x-cards.visual :network="$order->network" :design="$order->design" :last4="$order->last_four" :tilt="false" size="xs" />
                        <div class="ic-order__body">
                            <div class="ic-order__top">
                                <p class="ic-order__name">{{ $order->card_name }} <small>· {{ $order->form === CardForm::PHYSICAL ? 'Physical' : 'Virtual' }}</small></p>
                                <span class="ic-status ic-status--{{ $order->status->color() }}">{{ $order->status->label() }}</span>
                            </div>
                            <p class="ic-muted ic-mono">{{ $order->reference }} · {{ $order->created_at->format('d M Y') }}</p>
                            <div class="ic-progress ic-progress--{{ $order->status->color() }}"><i style="width: {{ $progress }}%"></i></div>
                        </div>
                        <iconify-icon icon="solar:alt-arrow-right-linear" class="ic-order__arrow"></iconify-icon>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ FACTS ============ --}}
    <section class="ic-facts">
        <div class="ic-fact">
            <span class="ic-fact__icon"><iconify-icon icon="solar:card-2-bold"></iconify-icon></span>
            <p><strong>Visa or Mastercard</strong>Pick the network and limit that fit how you spend.</p>
        </div>
        <div class="ic-fact">
            <span class="ic-fact__icon ic-fact__icon--cyan"><iconify-icon icon="{{ $offersVirtual && $offersPhysical ? 'solar:widget-5-bold' : ($offersVirtual ? 'solar:smartphone-2-bold' : 'solar:delivery-bold') }}"></iconify-icon></span>
            <p>
                <strong>{{ $offersVirtual && $offersPhysical ? 'Physical or virtual' : ($offersVirtual ? 'Virtual card' : 'Delivered to you') }}</strong>
                {{ $offersVirtual && $offersPhysical ? 'Ship a card to your door or use it from your dashboard.' : ($offersVirtual ? 'Goes live in your dashboard once approved.' : 'Shipped with a tracking number you can follow.') }}
            </p>
        </div>
        <div class="ic-fact">
            <span class="ic-fact__icon ic-fact__icon--green"><iconify-icon icon="solar:wallet-money-bold"></iconify-icon></span>
            <p><strong>Pay from your balance</strong>No new deposit needed if your balance covers the fee.</p>
        </div>
        <div class="ic-fact">
            <span class="ic-fact__icon ic-fact__icon--amber"><iconify-icon icon="solar:refresh-circle-bold"></iconify-icon></span>
            <p><strong>Full refund promise</strong>Cancelled or not approved? Every cent goes back.</p>
        </div>
    </section>

    {{-- ============ CATALOGUE ============ --}}
    <section class="ic-section" id="catalogue">
        <div class="ic-section__head">
            <div>
                <p class="ic-eyebrow">The cards</p>
                <h2 class="ic-h2">Choose your card</h2>
                <p class="ic-muted">Fee, limit and delivery are shown up front. No surprises later.</p>
            </div>
            <div class="ic-segment" role="tablist">
                @foreach (['all' => 'All', 'visa' => 'Visa', 'mastercard' => 'Mastercard'] as $key => $label)
                    <button type="button" wire:click="$set('network', '{{ $key }}')" role="tab" @class(['is-active' => $network === $key]) aria-selected="{{ $network === $key ? 'true' : 'false' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="ic-grid" wire:loading.class="opacity-60" wire:target="network">
            @forelse ($products as $product)
                @php
                    $left = $product->slotsLeft();
                    $meter = round($product->spending_limit / $maxLimit * 100);
                @endphp
                <article @class(['ic-product', 'ic-product--featured' => $product->is_featured]) wire:key="product-{{ $product->id }}">
                    <div class="ic-product__stage ic-glow--{{ $product->design }}">
                        @if ($product->is_featured)
                            <span class="ic-product__ribbon"><iconify-icon icon="solar:crown-star-bold"></iconify-icon>Most popular</span>
                        @endif
                        <x-cards.visual :network="$product->network" :design="$product->design" :name="$first" flip />
                    </div>

                    <div class="ic-product__body">
                        <div class="ic-product__head">
                            <div>
                                <h3>{{ $product->name }}</h3>
                                @if ($product->tagline)<p class="ic-muted">{{ $product->tagline }}</p>@endif
                            </div>
                        </div>

                        <div class="ic-product__chips">
                            <span class="ic-chip">{{ $product->network->label() }}</span>
                            @foreach ($product->forms() as $f)
                                <span class="ic-chip ic-chip--soft"><iconify-icon icon="{{ $f === CardForm::PHYSICAL ? 'solar:delivery-linear' : 'solar:smartphone-2-linear' }}"></iconify-icon>{{ $f === CardForm::PHYSICAL ? 'Physical' : 'Virtual' }}</span>
                            @endforeach
                        </div>

                        <div class="ic-price">
                            <div>
                                <span class="ic-price__label">Pre-order fee</span>
                                <span class="ic-price__value">${{ number_format($product->preorder_fee) }}<small>{{ fmod((float) $product->preorder_fee, 1) ? '.'.substr(number_format($product->preorder_fee, 2), -2) : '' }}</small></span>
                                <span class="ic-price__note">one-time · refundable</span>
                            </div>
                            <div class="ic-limit">
                                <div class="ic-limit__row"><span>Card limit</span><strong>${{ number_format($product->spending_limit) }}</strong></div>
                                <div class="ic-limit__bar"><i style="width: {{ max($meter, 6) }}%"></i></div>
                                <span class="ic-limit__period">{{ CardProduct::LIMIT_PERIODS[$product->limit_period] ?? '' }}</span>
                            </div>
                        </div>

                        @if ($product->features)
                            <ul class="ic-product__features">
                                @foreach ($product->features as $feature)
                                    <li><iconify-icon icon="solar:check-circle-bold"></iconify-icon>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="ic-product__meta">
                            @if ($product->delivery_estimate)
                                <span><iconify-icon icon="solar:clock-circle-linear"></iconify-icon>{{ $product->delivery_estimate }}</span>
                            @endif
                            @if ($left !== null)
                                <span class="{{ $left <= max(3, $product->stock * 0.1) ? 'is-hot' : '' }}"><iconify-icon icon="solar:fire-bold"></iconify-icon>{{ $left }} of {{ $product->stock }} slots left</span>
                            @endif
                        </div>

                        @if ($left !== null)
                            <div class="ic-stock"><i style="width: {{ $product->stock ? round(($product->stock - $left) / $product->stock * 100) : 0 }}%"></i></div>
                        @endif

                        <button type="button" class="ic-btn ic-btn--block ic-btn--lg {{ $product->is_featured ? 'ic-btn--shine' : 'ic-btn--ghost' }}"
                                wire:click="choose({{ $product->id }})" wire:loading.attr="disabled" wire:target="choose({{ $product->id }})"
                                @disabled($blocked || $left === 0)>
                            <span wire:loading.remove wire:target="choose({{ $product->id }})">{{ $left === 0 ? 'Sold out' : 'Pre-order '.$product->network->label() }}</span>
                            <iconify-icon icon="solar:arrow-right-linear" wire:loading.remove wire:target="choose({{ $product->id }})"></iconify-icon>
                            <span wire:loading wire:target="choose({{ $product->id }})">Checking…</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="ic-empty">
                    <iconify-icon icon="solar:card-2-linear" class="text-4xl"></iconify-icon>
                    <p>No cards available right now. Check back soon.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ============ COMPARE ============ --}}
    @if ($all->count() > 1)
        <section class="ic-section">
            <div class="ic-section__head">
                <div>
                    <p class="ic-eyebrow">Side by side</p>
                    <h2 class="ic-h2">Compare the cards</h2>
                </div>
            </div>
            <div class="ic-compare">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($all as $product)
                                <th @class(['is-featured' => $product->is_featured])>
                                    <span class="ic-compare__dot ic-dot--{{ $product->design }}"></span>{{ $product->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Network</td>@foreach ($all as $p)<td>{{ $p->network->label() }}</td>@endforeach</tr>
                        <tr><td>Pre-order fee</td>@foreach ($all as $p)<td class="ic-strong">${{ number_format($p->preorder_fee, 2) }}</td>@endforeach</tr>
                        <tr><td>Card limit</td>@foreach ($all as $p)<td class="ic-strong">{{ $p->limitLabel() }}</td>@endforeach</tr>
                        <tr><td>Card type</td>@foreach ($all as $p)<td>{{ $p->formLabel() }}</td>@endforeach</tr>
                        <tr><td>Delivery</td>@foreach ($all as $p)<td>{{ $p->delivery_estimate ?: '—' }}</td>@endforeach</tr>
                        <tr><td>Refund if not approved</td>@foreach ($all as $p)<td><iconify-icon icon="solar:check-circle-bold" class="ic-good"></iconify-icon> Full</td>@endforeach</tr>
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    {{-- ============ HOW IT WORKS ============ --}}
    <section class="ic-section" id="how">
        <div class="ic-section__head">
            <div>
                <p class="ic-eyebrow">The journey</p>
                <h2 class="ic-h2">From pre-order to your pocket</h2>
            </div>
        </div>
        <ol class="ic-journey">
            @foreach ([
                ['solar:card-send-bold', 'Pre-order', 'Pick a card and pay the fee from your balance.'],
                ['solar:shield-check-bold', 'Review', 'We check your account and approve the order.'],
                ['solar:settings-bold', 'Production', 'Your card is made with your name on it.'],
                ['solar:delivery-bold', 'Delivery', 'Physical cards ship with a tracking number.'],
                ['solar:bolt-circle-bold', 'Activation', 'Your card goes live and shows in your dashboard.'],
            ] as $i => [$icon, $title, $text])
                <li class="ic-journey__step">
                    <span class="ic-journey__node"><iconify-icon icon="{{ $icon }}"></iconify-icon></span>
                    <small>Step {{ $i + 1 }}</small>
                    <p class="ic-journey__title">{{ $title }}</p>
                    <p class="ic-muted">{{ $text }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="ic-section">
        <div class="ic-section__head">
            <div>
                <p class="ic-eyebrow">Good to know</p>
                <h2 class="ic-h2">Questions, answered</h2>
            </div>
        </div>
        <div class="ic-faq">
            @foreach ([
                ['When am I charged?', 'The pre-order fee is taken from the balance you choose the moment you place the order. Nothing else is charged.'],
                ['Can I get my money back?', 'Yes. If an admin does not approve your order, or you cancel while cancelling is allowed, the full fee returns to the balance you paid from.'],
                ['What is the difference between physical and virtual?', 'A physical card is produced and shipped to your address. A virtual card is issued to your dashboard once approved, with no shipping.'],
                ['How long does it take?', 'Each card shows its delivery estimate. You can follow every step on your order page, and we email you at each stage.'],
                ['Why do I need KYC?', 'Payment cards are issued to verified people only. Complete identity verification in your profile before you pre-order.'],
            ] as [$q, $a])
                <details class="ic-faq__item">
                    <summary>{{ $q }}<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon></summary>
                    <p>{{ $a }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- ============ CLOSING ============ --}}
    @if (! $blocked)
        <section class="ic-closing">
            <div class="ic-hero__aurora" aria-hidden="true"></div>
            <div>
                <h2>Ready when you are.</h2>
                <p>Reserve yours today. The fee comes back in full if your order is not approved.</p>
            </div>
            <a href="#catalogue" class="ic-btn ic-btn--lg ic-btn--light">Pick my card <iconify-icon icon="solar:arrow-right-linear"></iconify-icon></a>
        </section>
    @endif

    {{-- ============ ORDER SHEET ============ --}}
    @if ($this->product)
        @php $product = $this->product; $steps = $this->steps; $key = $this->stepKey(); $isPhysical = $form === 'physical'; @endphp
        <div class="ic-sheet" x-data x-on:keydown.escape.window="$wire.close()" wire:key="sheet-{{ $product->id }}">
            <div class="ic-sheet__backdrop" wire:click="close"></div>
            <div class="ic-sheet__panel" role="dialog" aria-modal="true" aria-labelledby="ic-sheet-title">
                <header class="ic-sheet__header">
                    <div>
                        <p class="ic-muted">Pre-order</p>
                        <h3 id="ic-sheet-title">{{ $product->name }}</h3>
                    </div>
                    <button type="button" class="ic-icon-btn" wire:click="close" aria-label="Close"><iconify-icon icon="radix-icons:cross-2"></iconify-icon></button>
                </header>

                <ol class="ic-stepper">
                    @foreach (array_values($steps) as $i => $label)
                        <li @class(['is-done' => $i + 1 < $step, 'is-current' => $i + 1 === $step])><span>{{ $i + 1 < $step ? '✓' : $i + 1 }}</span>{{ $label }}</li>
                    @endforeach
                </ol>

                <div class="ic-sheet__body">
                    <div class="ic-sheet__preview ic-glow--{{ $product->design }}">
                        <x-cards.visual :network="$product->network" :design="$product->design" live="$wire.name_on_card" :name="$name_on_card" />
                        <div class="ic-sheet__summary">
                            <div><span>Fee</span><strong>${{ number_format($product->preorder_fee, 2) }}</strong></div>
                            <div><span>Limit</span><strong>{{ $product->limitLabel() }}</strong></div>
                            <div class="ic-sheet__summary-wide"><span>Type</span><strong>{{ $isPhysical ? 'Physical card' : 'Virtual card' }}</strong></div>
                        </div>
                    </div>

                    <div class="ic-sheet__form">
                        @if ($key === 'details')
                            @if (count($product->forms()) > 1)
                                <h4 class="ic-h4">Which type do you want?</h4>
                                <div class="ic-types">
                                    <label @class(['ic-type', 'is-selected' => $isPhysical])>
                                        <input type="radio" wire:model.live="form" value="physical" class="sr-only">
                                        <iconify-icon icon="solar:delivery-bold"></iconify-icon>
                                        <strong>Physical</strong>
                                        <small>Made and shipped to your address</small>
                                    </label>
                                    <label @class(['ic-type', 'is-selected' => ! $isPhysical])>
                                        <input type="radio" wire:model.live="form" value="virtual" class="sr-only">
                                        <iconify-icon icon="solar:smartphone-2-bold"></iconify-icon>
                                        <strong>Virtual</strong>
                                        <small>Ready in your dashboard, no shipping</small>
                                    </label>
                                </div>
                                @error('form')<em class="ic-error">{{ $message }}</em>@enderror
                            @endif

                            <h4 class="ic-h4">How should your name appear?</h4>
                            <p class="ic-muted">Up to 26 letters. It is printed exactly as entered.</p>
                            <label class="ic-field">
                                <span>Name on card</span>
                                <input type="text" wire:model="name_on_card" maxlength="26" autocomplete="cc-name" class="ic-input ic-input--caps" placeholder="ADA OBI">
                                @error('name_on_card')<em>{{ $message }}</em>@enderror
                            </label>
                        @elseif ($key === 'delivery')
                            <h4 class="ic-h4">Where should we send it?</h4>
                            <p class="ic-muted">The courier may call this number on delivery.</p>
                            <div class="ic-form-grid">
                                <label class="ic-field ic-field--full"><span>Address</span><input type="text" wire:model="address_line1" autocomplete="address-line1" class="ic-input">@error('address_line1')<em>{{ $message }}</em>@enderror</label>
                                <label class="ic-field ic-field--full"><span>Apartment, suite <small>(optional)</small></span><input type="text" wire:model="address_line2" autocomplete="address-line2" class="ic-input"></label>
                                <label class="ic-field"><span>City</span><input type="text" wire:model="city" autocomplete="address-level2" class="ic-input">@error('city')<em>{{ $message }}</em>@enderror</label>
                                <label class="ic-field"><span>State / region</span><input type="text" wire:model="state" autocomplete="address-level1" class="ic-input">@error('state')<em>{{ $message }}</em>@enderror</label>
                                <label class="ic-field"><span>Postal code <small>(optional)</small></span><input type="text" wire:model="postal_code" autocomplete="postal-code" class="ic-input"></label>
                                <label class="ic-field"><span>Country</span><input type="text" wire:model="country" autocomplete="country-name" class="ic-input">@error('country')<em>{{ $message }}</em>@enderror</label>
                                <label class="ic-field ic-field--full"><span>Phone</span><input type="tel" wire:model="phone" autocomplete="tel" class="ic-input" placeholder="+234 …">@error('phone')<em>{{ $message }}</em>@enderror</label>
                            </div>
                        @elseif ($key === 'payment')
                            <h4 class="ic-h4">Pay the pre-order fee from</h4>
                            <p class="ic-muted">${{ number_format($product->preorder_fee, 2) }} is taken now and returned if the pre-order is cancelled or not approved.</p>
                            <div class="ic-options">
                                @foreach ($balances as $value => $b)
                                    @php $short = $b['amount'] < (float) $product->preorder_fee; @endphp
                                    <label @class(['ic-option', 'is-selected' => $balance === $value, 'is-short' => $short])>
                                        <input type="radio" wire:model.live="balance" value="{{ $value }}" class="sr-only">
                                        <span class="ic-option__radio"></span>
                                        <span class="ic-option__body">
                                            <strong>{{ $b['label'] }}</strong>
                                            <small>{{ $short ? 'Not enough for this card' : 'Available' }}</small>
                                        </span>
                                        <span class="ic-option__amount">${{ number_format($b['amount'], 2) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('balance')<em class="ic-error">{{ $message }}</em>@enderror
                            @if (($balances[$balance]['amount'] ?? 0) < (float) $product->preorder_fee)
                                <a href="{{ route('deposit') }}" class="ic-link"><iconify-icon icon="solar:card-send-linear"></iconify-icon>Top up your balance</a>
                            @endif
                        @else
                            <h4 class="ic-h4">Check and confirm</h4>
                            <dl class="ic-review">
                                <div><dt>Card</dt><dd>{{ $product->name }} · {{ $product->network->label() }}</dd></div>
                                <div><dt>Type</dt><dd>{{ $isPhysical ? 'Physical card' : 'Virtual card' }}</dd></div>
                                <div><dt>Name on card</dt><dd class="ic-mono">{{ strtoupper($name_on_card) }}</dd></div>
                                @if ($isPhysical)
                                    <div><dt>Deliver to</dt><dd>{{ collect([$address_line1, $address_line2, $city, $state, $postal_code, $country])->filter()->implode(', ') }}</dd></div>
                                @endif
                                <div><dt>Card limit</dt><dd>{{ $product->limitLabel() }}</dd></div>
                                <div><dt>Paid from</dt><dd>{{ $balances[$balance]['label'] ?? '' }}</dd></div>
                                <div class="ic-review__total"><dt>Pre-order fee</dt><dd>${{ number_format($product->preorder_fee, 2) }}</dd></div>
                            </dl>
                            <label class="ic-check">
                                <input type="checkbox" wire:model="agree">
                                <span>I understand the fee is taken now and refunded in full if my pre-order is cancelled or not approved. Delivery times are estimates.</span>
                            </label>
                            @error('agree')<em class="ic-error">{{ $message }}</em>@enderror
                        @endif
                    </div>
                </div>

                <footer class="ic-sheet__footer">
                    @if ($step > 1)
                        <button type="button" class="ic-btn ic-btn--ghost" wire:click="back">Back</button>
                    @else
                        <button type="button" class="ic-btn ic-btn--ghost" wire:click="close">Cancel</button>
                    @endif

                    @if ($step < count($steps))
                        <button type="button" class="ic-btn" wire:click="next" wire:loading.attr="disabled" wire:target="next">
                            Continue <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                        </button>
                    @else
                        <button type="button" class="ic-btn ic-btn--shine" wire:click="place" wire:loading.attr="disabled" wire:target="place">
                            <span wire:loading.remove wire:target="place">Pay ${{ number_format($product->preorder_fee, 2) }} &amp; pre-order</span>
                            <span wire:loading wire:target="place">Placing pre-order…</span>
                        </button>
                    @endif
                </footer>
            </div>
        </div>
    @endif
</div>
