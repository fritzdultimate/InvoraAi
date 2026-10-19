@php
    $visa = $products->where('network', \App\Enums\CardNetwork::VISA);
    $master = $products->where('network', \App\Enums\CardNetwork::MASTERCARD);
    $open = \App\Services\Card\CardProgram::isOpen();
@endphp

<div class="ic-page">

    {{-- HERO --}}
    <section class="ic-hero">
        <div class="ic-hero__glow" aria-hidden="true"></div>
        <div class="ic-hero__copy">
            <span class="ic-pill {{ $open ? 'ic-pill--live' : 'ic-pill--muted' }}"><i></i>{{ $open ? 'Pre-orders open' : 'Pre-orders closed' }}</span>
            <h1 class="ic-hero__title">Invora Cards</h1>
            <p class="ic-hero__lead">Reserve your Visa or Mastercard today. Pay the pre-order fee from your balance, track every step, and get the fee back in full if your pre-order is cancelled or not approved.</p>

            <dl class="ic-hero__facts">
                @if ($visa->isNotEmpty())
                    <div><dt>Visa from</dt><dd>${{ number_format($visa->min('preorder_fee')) }}</dd></div>
                @endif
                @if ($master->isNotEmpty())
                    <div><dt>Mastercard limit</dt><dd>up to ${{ number_format($master->max('spending_limit')) }}</dd></div>
                @endif
                <div><dt>Fee refund</dt><dd>If not approved</dd></div>
            </dl>
        </div>

        <div class="ic-hero__cards" aria-hidden="true">
            <x-cards.visual network="mastercard" design="gold" class="ic-hero__back" :tilt="false" />
            <x-cards.visual network="visa" design="sapphire" class="ic-hero__front" :name="auth()->user()->name" />
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

    {{-- CATALOGUE --}}
    <section class="ic-section">
        <div class="ic-section__head">
            <div>
                <h2 class="ic-h2">Choose your card</h2>
                <p class="ic-muted">Every card shows its fee, limit and delivery before you commit.</p>
            </div>
            <div class="ic-segment" role="tablist">
                @foreach (['all' => 'All', 'visa' => 'Visa', 'mastercard' => 'Mastercard'] as $key => $label)
                    <button type="button" wire:click="$set('network', '{{ $key }}')" role="tab" @class(['is-active' => $network === $key]) aria-selected="{{ $network === $key ? 'true' : 'false' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="ic-grid" wire:loading.class="opacity-60" wire:target="network">
            @forelse ($products as $product)
                @php $left = $product->slotsLeft(); @endphp
                <article @class(['ic-product', 'ic-product--featured' => $product->is_featured])>
                    @if ($product->is_featured)
                        <span class="ic-product__ribbon">Most popular</span>
                    @endif

                    <x-cards.visual :network="$product->network" :design="$product->design" :name="auth()->user()->name" />

                    <div class="ic-product__head">
                        <div>
                            <h3>{{ $product->name }}</h3>
                            @if ($product->tagline)<p class="ic-muted">{{ $product->tagline }}</p>@endif
                        </div>
                        <span class="ic-chip">{{ $product->form === \App\Enums\CardForm::PHYSICAL ? 'Physical' : 'Virtual' }}</span>
                    </div>

                    <div class="ic-product__figures">
                        <div><span>Pre-order fee</span><strong>${{ number_format($product->preorder_fee, 2) }}</strong></div>
                        <div><span>Card limit</span><strong>${{ number_format($product->spending_limit) }}</strong><em>{{ \App\Models\CardProduct::LIMIT_PERIODS[$product->limit_period] ?? '' }}</em></div>
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
                            <span><iconify-icon icon="{{ $product->form === \App\Enums\CardForm::PHYSICAL ? 'solar:delivery-bold' : 'solar:bolt-bold' }}"></iconify-icon>{{ $product->delivery_estimate }}</span>
                        @endif
                        @if ($left !== null)
                            <span><iconify-icon icon="solar:fire-bold"></iconify-icon>{{ $left }} of {{ $product->stock }} left</span>
                        @endif
                    </div>

                    @if ($left !== null)
                        <div class="ic-stock"><i style="width: {{ $product->stock ? round(($product->stock - $left) / $product->stock * 100) : 0 }}%"></i></div>
                    @endif

                    <button type="button" class="ic-btn ic-btn--block {{ $product->is_featured ? '' : 'ic-btn--ghost' }}"
                            wire:click="choose({{ $product->id }})" wire:loading.attr="disabled" wire:target="choose({{ $product->id }})"
                            @disabled($blocked || $left === 0)>
                        <span wire:loading.remove wire:target="choose({{ $product->id }})">{{ $left === 0 ? 'Sold out' : 'Pre-order '.$product->network->label() }}</span>
                        <span wire:loading wire:target="choose({{ $product->id }})">Checking…</span>
                    </button>
                </article>
            @empty
                <div class="ic-empty">
                    <iconify-icon icon="solar:card-2-linear" class="text-4xl"></iconify-icon>
                    <p>No cards available right now. Check back soon.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="ic-steps">
        @foreach ([
            ['solar:card-send-bold', 'Pre-order', 'Pay the fee from your balance. It is held against your order.'],
            ['solar:shield-check-bold', 'Review', 'We check your account and approve the order.'],
            ['solar:settings-bold', 'Production', 'Your card is produced with your name on it.'],
            ['solar:delivery-bold', 'Delivery & activation', 'Physical cards ship with tracking. Virtual cards go live in your dashboard.'],
        ] as $i => [$icon, $title, $text])
            <div class="ic-step">
                <span class="ic-step__icon"><iconify-icon icon="{{ $icon }}"></iconify-icon></span>
                <div>
                    <p class="ic-step__title"><small>0{{ $i + 1 }}</small> {{ $title }}</p>
                    <p class="ic-muted">{{ $text }}</p>
                </div>
            </div>
        @endforeach
    </section>

    {{-- MY ORDERS --}}
    @if ($orders->isNotEmpty())
        <section class="ic-section">
            <div class="ic-section__head">
                <h2 class="ic-h2">My pre-orders</h2>
            </div>
            <div class="ic-orders">
                @foreach ($orders as $order)
                    @php
                        $journey = \App\Enums\CardOrderStatus::journey($order->form);
                        $pos = array_search($order->status, $journey, true);
                        $progress = $pos === false ? 100 : round($pos / (count($journey) - 1) * 100);
                    @endphp
                    <a href="{{ route('cards.order', $order) }}" class="ic-order">
                        <x-cards.visual :network="$order->network" :design="$order->design" :last4="$order->last_four" :tilt="false" size="xs" />
                        <div class="ic-order__body">
                            <div class="ic-order__top">
                                <p class="ic-order__name">{{ $order->card_name }}</p>
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

    {{-- ORDER SHEET --}}
    @if ($this->product)
        @php $product = $this->product; $steps = $this->steps; $key = $this->stepKey(); @endphp
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
                    <div class="ic-sheet__preview">
                        <x-cards.visual :network="$product->network" :design="$product->design" live="$wire.name_on_card" :name="$name_on_card" />
                        <div class="ic-sheet__summary">
                            <div><span>Fee</span><strong>${{ number_format($product->preorder_fee, 2) }}</strong></div>
                            <div><span>Limit</span><strong>{{ $product->limitLabel() }}</strong></div>
                        </div>
                    </div>

                    <div class="ic-sheet__form">
                        @if ($key === 'details')
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
                                <div><dt>Type</dt><dd>{{ $product->form->label() }}</dd></div>
                                <div><dt>Name on card</dt><dd class="ic-mono">{{ strtoupper($name_on_card) }}</dd></div>
                                @if ($product->form === \App\Enums\CardForm::PHYSICAL)
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
                        <button type="button" class="ic-btn" wire:click="place" wire:loading.attr="disabled" wire:target="place">
                            <span wire:loading.remove wire:target="place">Pay ${{ number_format($product->preorder_fee, 2) }} &amp; pre-order</span>
                            <span wire:loading wire:target="place">Placing pre-order…</span>
                        </button>
                    @endif
                </footer>
            </div>
        </div>
    @endif
</div>
