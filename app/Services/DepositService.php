<?php

namespace App\Services;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\AdminAlertMail;
use App\Mail\DepositApprovedMail;
use App\Mail\DepositExpiredMail;
use App\Models\CustomSetting;
use App\Models\Deposit;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\Wallet\WalletService;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class DepositService {

    /**
     * Statuses that mean money has actually landed and the deposit is
     * eligible to be credited: a full payment ("finished") or a payment
     * that came in short but that NOWPayments has stopped waiting on
     * ("partially_paid").
     */
    private const SETTLED_STATUSES = [
        DepositStatus::FINISHED,
        DepositStatus::PARTIALLYPAID,
    ];

    /**
     * Apply a NOWPayments IPN update to a deposit: sync its status and raw
     * payload, then — the first time (and only the first time) money
     * actually lands — credit the user for what was received, run the
     * deposit/matching bonuses, and notify the user and admins.
     *
     * This is the single place that turns a NOWPayments payment_status into
     * a wallet credit, used by the IPN webhook. (The admin "Approve" button
     * has its own entry point, markAsFinished(), for manually confirming a
     * deposit NOWPayments never reported — both funnel into
     * creditAndNotify() so the crediting/bonus/notification logic itself
     * only lives in one place.)
     */
    public static function applyPaymentUpdate(Deposit $deposit, array $data): void
    {
        $newStatus = DepositStatus::tryFrom($data['payment_status'] ?? '');

        if (! $newStatus) {
            Log::warning('NOWPayments webhook: unrecognized payment_status, ignoring.', [
                'deposit_id' => $deposit->id,
                'payment_status' => $data['payment_status'] ?? null,
            ]);

            return;
        }

        $deposit->status = $newStatus;
        $deposit->meta = $data;
        $deposit->save();

        // Already credited once — never credit the same deposit twice,
        // no matter how many more IPN updates NOWPayments sends for it.
        if ($deposit->received_at) {
            return;
        }

        if (! in_array($newStatus, self::SETTLED_STATUSES, true)) {
            return;
        }

        $isPartial = $newStatus === DepositStatus::PARTIALLYPAID;
        $receivedUsd = self::receivedUsdFromPayload($data, $deposit);

        $deposit->update([
            'received_at' => now(),
            'actually_paid' => $receivedUsd,
        ]);

        if ($receivedUsd <= 0) {
            Log::warning('NOWPayments webhook: settled deposit resolved to $0 received, nothing credited.', [
                'deposit_id' => $deposit->id,
                'payment_status' => $newStatus->value,
                'payload' => $data,
            ]);

            return;
        }

        self::creditAndNotify($deposit, $receivedUsd, $isPartial);
    }

    /**
     * Convert NOWPayments' `actually_paid` — reported in the *paid
     * currency's own units* (e.g. BTC), never USD — into the USD amount to
     * credit.
     *
     * NOWPayments' own invoice was created with `price_amount` (the USD
     * amount, same as $deposit->amount) and `pay_currency` (the crypto the
     * user chose); the IPN payload echoes `price_amount` back plus
     * `pay_amount` (the crypto amount that was expected) and `actually_paid`
     * (the crypto amount that arrived) — both in the same pay_currency
     * units, so their ratio is the fraction of the invoice that was
     * fulfilled. Applying that ratio to price_amount gives the USD value,
     * regardless of which coin was used. This is also what naturally
     * produces the right number for a partial payment: a user who sends
     * half the expected BTC gets credited for half the USD amount, not a
     * few cents' worth of raw BTC units (the previous bug).
     */
    private static function receivedUsdFromPayload(array $data, Deposit $deposit): float
    {
        $priceAmountUsd = (float) ($data['price_amount'] ?? $deposit->amount);
        $payAmountCrypto = (float) ($data['pay_amount'] ?? 0);
        $actuallyPaidCrypto = (float) ($data['actually_paid'] ?? 0);

        if ($payAmountCrypto <= 0 || $actuallyPaidCrypto <= 0) {
            return 0.0;
        }

        $fulfilledRatio = $actuallyPaidCrypto / $payAmountCrypto;

        return round($priceAmountUsd * $fulfilledRatio, 2);
    }

    /**
     * Credit the user for a settled deposit, run the deposit/matching
     * bonuses, and notify the user and admins. Shared by the NOWPayments
     * webhook (applyPaymentUpdate) and the admin "Approve" action
     * (markAsFinished) so both behave identically instead of maintaining
     * two copies of the same credit -> bonus -> email sequence.
     *
     * @return float The deposit bonus credited alongside this deposit, if any.
     */
    public static function creditAndNotify(Deposit $deposit, float $usdAmount, bool $isPartial = false): float
    {
        $user = $deposit->user()->lockForUpdate()->first();

        WalletService::credit(
            $user,
            $usdAmount,
            LedgerReference::DEPOSIT,
            $deposit->id,
            $isPartial ? 'Partial deposit received' : 'Deposit received',
            LedgerAsset::DEPOSIT
        );

        $bonus = self::depositBonus($deposit);
        self::matchingDepositBonus($deposit);

        Mail::to($deposit->user->email)->send(new DepositApprovedMail(
            $usdAmount,
            $deposit->reference,
            $deposit->currency,
            now()->format('l, d F Y • h:i A'),
            'https://invora.ai/dashboard',
            $bonus,
            $isPartial ? $deposit->amount : null
        ));

        AdminNotifier::notify(new AdminAlertMail(
            subjectLine: $isPartial ? 'Partial Deposit Received ⚠️' : 'Deposit Approved ✅',
            badge: $isPartial ? 'PARTIAL DEPOSIT' : 'DEPOSIT APPROVED',
            badgeColor: $isPartial ? 'warning' : 'success',
            heading: $isPartial ? 'A deposit was only partially paid' : 'A deposit was credited',
            intro: $isPartial
                ? "{$deposit->user->email} paid less than requested — only the received amount was credited. Review if follow-up is needed."
                : "{$deposit->user->email}'s deposit has been credited.",
            rows: [
                'User' => $deposit->user->email,
                'Reference' => $deposit->reference,
                'Requested' => '$' . number_format($deposit->amount, 2),
                'Credited' => '$' . number_format($usdAmount, 2),
                'Currency' => strtoupper($deposit->currency),
                'Deposit ID' => (string) $deposit->id,
            ],
            url: url('/admin/deposits'),
            ctaLabel: 'View Deposit'
        ));

        return $bonus;
    }

    public static function matchingDepositBonus($deposit) {
        $matchingEnable = (boolean) CustomSetting::get('enabled_matching_bonus', false);
        if (!$matchingEnable) return;

        // idempotency guard in case this runs twice for the same deposit
        $alreadyMatched = WalletLedger::where('reference_type', LedgerReference::MATCHINGDEPOSITBONUS)
            ->where('reference_id', $deposit->id)
            ->exists();
        if ($alreadyMatched) return;

        $pct = $deposit->actually_paid > 500 ? 100 : 50;

        $bonus = (float) bcmul((string) $deposit->actually_paid, bcdiv((string) $pct, '100', 8), 8);
        if ($bonus <= 0) return;

        WalletService::credit(
            $deposit->user,
            $bonus,
            LedgerReference::MATCHINGDEPOSITBONUS,
            $deposit->id,
            "matching deposit bonus ({$pct}%)",
            LedgerAsset::DEPOSITBONUSBALANCE
        );

        NotificationService::createForUser($deposit->user, [
            'title' => 'Matching Deposit Bonus Received 🎉',
            'message' => "You received a {$pct}% matching bonus on your deposit!",
        ]);

        return $bonus;
    }
    public static function depositBonus($deposit) {
        $hasReceivedBonus = WalletLedger::where('user_id', $deposit->user_id)
            ->where('reference_type', LedgerReference::DEPOSITBONUS)
            ->exists();

        if ($hasReceivedBonus) {
            return 0;
        }


        if ($deposit->bonus > 0) {
            // return $deposit->bonus;
            return;
        }
        $bonus = (float) CustomSetting::get('deposit_bonus', 0);
        $bonusDuration = (int) CustomSetting::get('deposit_bonus_duration_days', 0);

        if($bonus <= 0) return;

        $deposit->update([
            'bonus' => $bonus,
            // 'bonus_expires_at' => now()->addDays($bonusDuration),
        ]);

        WalletService::credit(
            $deposit->user,
            $bonus,
            LedgerReference::DEPOSITBONUS,
            $deposit->id,
            "deposit received",
            LedgerAsset::DEPOSITBONUSBALANCE
        );

        NotificationService::createForUser($deposit->user, [
            'title' => 'Deposit Bonus Received 🎉',
            'message' => 'You have received your deposit bonus! Check your balance to see the updated amount.',
        ]);

        return $deposit->bonus;
    }

    /**
     * Manually confirm a deposit from the admin panel — used when a payment
     * arrived outside NOWPayments' own IPN (e.g. a bank/manual transfer, or
     * NOWPayments never called back).
     *
     * $creditAmount lets the admin credit less than the requested amount
     * when the user underpaid (e.g. paid crypto short of what was asked
     * for) — it defaults to the full requested amount when omitted. It can
     * never exceed $deposit->amount: that's enforced here server-side, not
     * just by the admin form's own max-value validation, since a form
     * constraint alone is never a substitute for a server-side guard on
     * money moving into a wallet. Crediting less than requested marks the
     * deposit "partially paid" rather than "finished", matching how the
     * webhook already represents an underpaid deposit — same status, same
     * meaning, regardless of which path settled it. Either way this shares
     * the same crediting/bonus/notification logic as the webhook via
     * creditAndNotify().
     */
    public static function markAsFinished(Deposit $deposit, ?float $creditAmount = null) {
        if ($deposit->status === DepositStatus::FINISHED) {
            throw new Halt('Deposit already processed.');
        }
        if (in_array($deposit->status, [DepositStatus::FINISHED, DepositStatus::CANCELLED, DepositStatus::FAILED, DepositStatus::EXPIRED], true)) {
            throw new Halt('This deposit cannot be approved.');
        }

        $requestedAmount = (float) $deposit->amount;
        $creditAmount = $creditAmount ?? $requestedAmount;

        if ($creditAmount <= 0) {
            throw new Halt('Amount to credit must be greater than zero.');
        }

        
        if (bccomp((string) $creditAmount, (string) $requestedAmount, 2) === 1) {
            throw new Halt('Amount to credit cannot exceed the requested amount ($' . number_format($requestedAmount, 2) . ').');
        }

        $isPartial = bccomp((string) $creditAmount, (string) $requestedAmount, 2) === -1;

        DB::transaction(function() use ($deposit, $creditAmount, $isPartial) {
            $deposit->update([
                'status' => $isPartial ? DepositStatus::PARTIALLYPAID : DepositStatus::FINISHED,
                'received_at' => $deposit->received_at ?? now(),
                'actually_paid' => $creditAmount
            ]);

            self::creditAndNotify($deposit, $creditAmount, $isPartial);
        });
    }

    public static function debitForInvestment(User $user, float $amount): array {
        return DB::transaction(function () use ($user, $amount) {
            $remaining = $amount;
            $fromBonus = 0.0;

            $bonusBalance = $user->getBalance(LedgerAsset::DEPOSITBONUSBALANCE);

            if ($bonusBalance > 0) {
                $fromBonus = min($bonusBalance, $remaining);

                WalletService::debit(
                    $user,
                    $fromBonus,
                    LedgerReference::BOT_INVESTMENT,
                    null,
                    'investment debit (bonus balance)',
                    LedgerAsset::DEPOSITBONUSBALANCE
                );

                $remaining -= $fromBonus;
            }

            if ($remaining > 0) {
                $depositBalance = $user->getBalance(LedgerAsset::DEPOSIT);

                if ($depositBalance < $remaining) {
                    throw new \Exception('Insufficient balance.');
                }

                WalletService::debit(
                    $user,
                    $remaining,
                    LedgerReference::BOT_INVESTMENT,
                    null,
                    'investment debit (main balance)',
                    LedgerAsset::DEPOSIT
                );
            }

            return [
                'from_bonus' => $fromBonus,
                'from_deposit' => $remaining, // real money
            ];
        });
    }

    public static function expireOldDeposits(): void {
        $deposits = Deposit::whereIn('status', [
                DepositStatus::PENDING,
                DepositStatus::WAITING
            ])
            ->where('created_at', '<', now()->subMinutes(90))
            ->get();

        foreach ($deposits as $deposit) {
            DB::transaction(function() use ($deposit) {
                $deposit->update([
                    'status' => DepositStatus::EXPIRED
                ]);

                Mail::to($deposit->user->email)->send(new DepositExpiredMail(
                    $deposit->amount,
                    $deposit->reference,
                    $deposit->currency,
                    now()->format('l, d F Y • h:i A'),
                    'https://invora.ai/dashboard',
                ));

            });

        }
    }
}
