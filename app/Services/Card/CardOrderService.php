<?php

namespace App\Services\Card;

use App\Enums\CardForm;
use App\Enums\CardOrderStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\AdminAlertMail;
use App\Mail\CardOrderUpdateMail;
use App\Models\BotLicense;
use App\Models\CardOrder;
use App\Models\CardProduct;
use App\Models\User;
use App\Services\AdminNotifier;
use App\Services\NotificationService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CardOrderService
{
    // Why this user can't order this card right now, or null if they can.
    public function blockReason(User $user, ?CardProduct $product = null): ?string
    {
        if (! CardProgram::isEnabled() || ! CardProgram::isOpen()) {
            return 'Card pre-orders are closed at the moment.';
        }

        if (CardProgram::requiresKyc() && $user->kyc_status !== 'approved') {
            return 'Complete identity verification (KYC) to pre-order a card.';
        }

        if (CardProgram::requiresActiveLicense() && ! $this->hasActiveLicense($user)) {
            return 'An active bot license is required to pre-order a card.';
        }

        if (! $product) {
            return null;
        }

        if (! $product->is_active) {
            return 'This card is not available.';
        }

        if ($product->slotsLeft() === 0) {
            return 'This card is sold out.';
        }

        $held = CardOrder::where('user_id', $user->id)
            ->where('card_product_id', $product->id)
            ->whereNotIn('status', [CardOrderStatus::CANCELLED->value, CardOrderStatus::REJECTED->value])
            ->count();

        if ($held >= $product->max_per_user) {
            return 'You already have the maximum number of this card.';
        }

        return null;
    }

    /**
     * Charges the pre-order fee and creates the order.
     *
     * $details: name_on_card, form (needed when the card is offered as both),
     * and for physical cards the phone and address fields.
     */
    public function placeOrder(User $user, CardProduct $product, LedgerAsset $balance, array $details): CardOrder
    {
        $order = DB::transaction(function () use ($user, $product, $balance, $details) {
            // lock the user and the product so two requests can't both pass the checks
            $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $product = CardProduct::whereKey($product->id)->lockForUpdate()->firstOrFail();

            if ($reason = $this->blockReason($user, $product)) {
                throw new CardOrderException($reason);
            }

            $form = CardForm::tryFrom($details['form'] ?? '') ?? $product->forms()[0];

            if (! $product->offers($form)) {
                throw new CardOrderException("This card is not available as a {$form->label()}.");
            }

            if ($form === CardForm::PHYSICAL && empty($details['address_line1'])) {
                throw new CardOrderException('A delivery address is needed for a physical card.');
            }

            if (! in_array($balance, CardProgram::allowedBalances(), true)) {
                throw new CardOrderException('You cannot pay from that balance.');
            }

            if ((float) WalletService::getBalance($user, $balance->value) < (float) $product->preorder_fee) {
                throw new CardOrderException('Your '.strtolower($balance->label()).' is too low for this card.');
            }

            $order = CardOrder::create([
                'reference' => CardOrder::newReference(),
                'user_id' => $user->id,
                'card_product_id' => $product->id,
                'card_name' => $product->name,
                'network' => $product->network,
                'form' => $form,
                'design' => $product->design,
                'fee' => $product->preorder_fee,
                'spending_limit' => $product->spending_limit,
                'limit_period' => $product->limit_period,
                'paid_from' => $balance->value,
                'status' => CardOrderStatus::PENDING,
                'name_on_card' => strtoupper(trim($details['name_on_card'])),
                ...($form === CardForm::PHYSICAL ? [
                    'phone' => $details['phone'] ?? null,
                    'address_line1' => $details['address_line1'] ?? null,
                    'address_line2' => $details['address_line2'] ?? null,
                    'city' => $details['city'] ?? null,
                    'state' => $details['state'] ?? null,
                    'postal_code' => $details['postal_code'] ?? null,
                    'country' => $details['country'] ?? null,
                ] : []),
            ]);

            $ledger = WalletService::debit(
                $user,
                (float) $product->preorder_fee,
                LedgerReference::CARD_PREORDER,
                $order->id,
                "Card pre-order {$order->reference} ({$product->name})",
                $balance
            );

            $order->update(['debit_ledger_id' => $ledger->id]);

            return $order;
        });

        $this->tellUser($order, 'Pre-order received', "We have received your {$order->card_name} pre-order and it is now under review.");
        $this->tellAdmins($order);

        return $order;
    }

    public function canUserCancel(CardOrder $order): bool
    {
        return match (CardProgram::cancelUntil()) {
            'pending' => $order->status === CardOrderStatus::PENDING,
            'approved' => in_array($order->status, [CardOrderStatus::PENDING, CardOrderStatus::APPROVED]),
            default => false,
        };
    }

    public function cancelByUser(CardOrder $order, User $user): CardOrder
    {
        if ($order->user_id !== $user->id || ! $this->canUserCancel($order)) {
            throw new CardOrderException('This pre-order can no longer be cancelled.');
        }

        return $this->closeAndRefund($order, CardOrderStatus::CANCELLED, 'Cancelled by you. The fee has been returned to your balance.');
    }

    public function reject(CardOrder $order, string $reason): CardOrder
    {
        if (! $order->status->isOpen()) {
            throw new CardOrderException('Only open pre-orders can be rejected.');
        }

        return $this->closeAndRefund($order, CardOrderStatus::REJECTED, $reason);
    }

    /**
     * Moves an order one step forward: approved, processing, shipped or active.
     * $data carries courier/tracking for shipped and last_four/expiry for active.
     */
    public function advance(CardOrder $order, CardOrderStatus $to, array $data = []): CardOrder
    {
        $order = DB::transaction(function () use ($order, $to, $data) {
            $order = CardOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($this->nextStatus($order) !== $to) {
                throw new CardOrderException("This order can't move to {$to->label()} from {$order->status->label()}.");
            }

            $changes = ['status' => $to];

            match ($to) {
                CardOrderStatus::APPROVED => $changes['approved_at'] = now(),
                CardOrderStatus::PROCESSING => $changes['processing_at'] = now(),
                CardOrderStatus::SHIPPED => $changes += [
                    'shipped_at' => now(),
                    'courier' => $data['courier'] ?? null,
                    'tracking_number' => $data['tracking_number'] ?? null,
                    'tracking_url' => $data['tracking_url'] ?? null,
                ],
                CardOrderStatus::ACTIVE => $changes += [
                    'activated_at' => now(),
                    'last_four' => $data['last_four'] ?? null,
                    'expiry' => $data['expiry'] ?? null,
                ],
                default => null,
            };

            if ($to === CardOrderStatus::ACTIVE && (! preg_match('/^\d{4}$/', $changes['last_four'] ?? '') || ! preg_match('#^(0[1-9]|1[0-2])/\d{2}$#', $changes['expiry'] ?? ''))) {
                throw new CardOrderException('Enter the last 4 digits and the expiry (MM/YY).');
            }

            if (! empty($data['user_message'])) {
                $changes['user_message'] = $data['user_message'];
            }

            $order->update($changes);

            return $order;
        });

        [$title, $text] = match ($to) {
            CardOrderStatus::APPROVED => ['Pre-order approved', "Your {$order->card_name} pre-order has been approved and is queued for production."],
            CardOrderStatus::PROCESSING => ['Your card is in production', "Your {$order->card_name} is being produced."],
            CardOrderStatus::SHIPPED => ['Your card is on its way', "Your {$order->card_name} has been shipped".($order->courier ? " with {$order->courier}" : '').'.'],
            CardOrderStatus::ACTIVE => ['Your card is active', "Your {$order->card_name} ending in {$order->last_four} is now active."],
            default => [null, null],
        };

        if ($title) {
            $this->tellUser($order, $title, $text);
        }

        return $order;
    }

    // the status an admin can move this order to next, or null at the end
    public function nextStatus(CardOrder $order): ?CardOrderStatus
    {
        $journey = CardOrderStatus::journey($order->form);
        $position = array_search($order->status, $journey, true);

        return $position === false ? null : ($journey[$position + 1] ?? null);
    }

    protected function closeAndRefund(CardOrder $order, CardOrderStatus $status, string $message): CardOrder
    {
        $order = DB::transaction(function () use ($order, $status, $message) {
            $order = CardOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->status->isOpen() || $order->refunded_at) {
                throw new CardOrderException('This pre-order is already closed.');
            }

            $ledger = WalletService::credit(
                $order->user,
                (float) $order->fee,
                LedgerReference::CARD_PREORDER_REFUND,
                $order->id,
                "Card pre-order refund {$order->reference}",
                LedgerAsset::from($order->paid_from)
            );

            $order->update([
                'status' => $status,
                $status === CardOrderStatus::CANCELLED ? 'cancelled_at' : 'rejected_at' => now(),
                'refunded_at' => now(),
                'refund_ledger_id' => $ledger->id,
                'user_message' => $message,
            ]);

            return $order;
        });

        $this->tellUser(
            $order,
            $status === CardOrderStatus::CANCELLED ? 'Pre-order cancelled' : 'Pre-order not approved',
            $message.' $'.number_format($order->fee, 2).' has been returned to your '.strtolower(LedgerAsset::from($order->paid_from)->label()).'.'
        );

        return $order;
    }

    protected function hasActiveLicense(User $user): bool
    {
        return BotLicense::where('user_id', $user->id)->where('expires_at', '>', now())->exists();
    }

    // in-app notification plus email; runs after the money has moved, so it must never throw
    protected function tellUser(CardOrder $order, string $title, string $text): void
    {
        try {
            NotificationService::createForUser($order->user, [
                'title' => $title,
                'message' => $text,
                'meta' => ['url' => route('cards.order', $order)],
            ]);

            Mail::to($order->user)->send(new CardOrderUpdateMail($order, $title, $text));
        } catch (\Throwable $e) {
            Log::warning('Card order email failed', ['order' => $order->reference, 'error' => $e->getMessage()]);
        }
    }

    // the order is already paid at this point, so a failed alert is logged, never thrown
    protected function tellAdmins(CardOrder $order): void
    {
        try {
            $this->sendAdminAlert($order);
        } catch (\Throwable $e) {
            Log::warning('Card order admin alert failed', ['order' => $order->reference, 'error' => $e->getMessage()]);
        }
    }

    protected function sendAdminAlert(CardOrder $order): void
    {
        AdminNotifier::notify(new AdminAlertMail(
            subjectLine: "New card pre-order {$order->reference}",
            badge: 'CARD PRE-ORDER',
            badgeColor: 'info',
            heading: "{$order->user->name} pre-ordered a {$order->card_name}",
            intro: 'The fee has been charged. Review the order in the admin panel.',
            rows: [
                'Reference' => $order->reference,
                'Card' => "{$order->card_name} ({$order->network->label()}, {$order->form->label()})",
                'Fee' => '$'.number_format($order->fee, 2),
                'Paid from' => LedgerAsset::from($order->paid_from)->label(),
                'Customer' => "{$order->user->name} ({$order->user->email})",
            ],
            url: url('/admin/card-orders/'.$order->id),
            ctaLabel: 'Review order',
        ));
    }
}
