<?php

namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Enums\WithdrawalStatus;
use App\Mail\AdminAlertMail;
use App\Mail\WithdrawalCompletedMail;
use App\Mail\WithdrawalRejectedMail;
use App\Mail\WithdrawalRequestMail;
use App\Domain\Withdrawal\WithdrawalAddressValidator;
use App\Models\CustomSetting;
use App\Models\RankBonus;
use App\Models\ReferralReward;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawalCurrency;
use App\Models\WithdrawalNetwork;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class  WithdrawalService {

    /**
     * Create a withdrawal request on the user's behalf, then notify the
     * user (request received, pending review) and admins (new request to
     * review). No balance is touched here — funds only leave the user's
     * account once an admin approves the request via complete().
     *
     * @throws \DomainException If the address doesn't look like it belongs
     *         to the selected currency/network (e.g. a BTC address with
     *         ETH selected). Re-checked here independently of any
     *         client-side form validation — see WithdrawalAddressValidator.
     */
    public static function create(User $user, array $data): Withdrawal {
        $currency = WithdrawalCurrency::find($data['withdrawal_currency_id']);
        $network = ! empty($data['withdrawal_network_id'])
            ? WithdrawalNetwork::find($data['withdrawal_network_id'])
            : null;

        if (! WithdrawalAddressValidator::isValid((string) $data['address'], $currency?->code, $network?->name)) {
            $label = WithdrawalAddressValidator::expectedLabel($currency?->code, $network?->name);

            throw new \DomainException("This doesn't look like a valid {$label} address. Double-check it matches the currency/network selected.");
        }

        return DB::transaction(function () use ($user, $data, $currency) {
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'address' => $data['address'],
                'asset' => 'main',
                'withdrawal_currency_id' => $data['withdrawal_currency_id'],
                'withdrawal_network_id' => $data['withdrawal_network_id'] ?? null,
                'meta' => [
                    'total_to_debit' => $data['amount'],
                    'fee' => $data['fee'] ?? 0,
                ],
                'reference' => generate_withdrawal_reference(),
            ]);

            $walletLabel = $currency?->name ?? 'Wallet';

            Mail::to($user->email)->send(new WithdrawalRequestMail(
                (float) $withdrawal->amount,
                $withdrawal->reference,
                $walletLabel,
                now()->format('l, d F Y • h:i A'),
                route('dashboard')
            ));

            AdminNotifier::notify(new AdminAlertMail(
                subjectLine: 'New Withdrawal Request',
                badge: 'WITHDRAWAL REQUEST',
                badgeColor: 'warning',
                heading: 'A user requested a withdrawal',
                intro: "{$user->email} submitted a withdrawal request awaiting review.",
                rows: [
                    'User' => $user->email,
                    'Reference' => $withdrawal->reference,
                    'Amount' => '$' . number_format($withdrawal->amount, 2),
                    'Wallet' => $walletLabel,
                    'Address' => $withdrawal->address,
                ],
                url: url('/admin/withdrawals'),
                ctaLabel: 'Review Withdrawal'
            ));

            return $withdrawal;
        });
    }

    public static function review(Withdrawal $withdrawal) {
       if ($withdrawal->status === WithdrawalStatus::COMPLETED) {
            return;
        }

        DB::transaction(function () use ($withdrawal) {

            $withdrawal->markReview();

            // send email
        });
    }

    public static function complete(Withdrawal $withdrawal) {
       if (in_array($withdrawal->status, [
            WithdrawalStatus::COMPLETED,
            WithdrawalStatus::FAILED,
            WithdrawalStatus::CANCELLED,
        ])) {
            return;
        }

        DB::transaction(function () use ($withdrawal) {
            $totalToDebit =  $withdrawal->amount;
            $feePercent = CustomSetting::get('withdrawal_fee') ?? 2;
            $feeAmount = $totalToDebit * ($feePercent * 0.01);

            WalletService::debit(
                $withdrawal->user,
                $totalToDebit,
                LedgerReference::WITHDRAWAL,
                $withdrawal->id,
                'Account withdrawal',
                LedgerAsset::MAIN
            );

            $withdrawal->markCompleted('-');

            if($withdrawal->status === WithdrawalStatus::COMPLETED) {
                Mail::to($withdrawal->user->email)->send(new WithdrawalCompletedMail(
                    $totalToDebit,
                    $withdrawal->reference,
                    $withdrawal->currency->name,
                    now(),
                    route('dashboard'),
                    $feeAmount,
                    $feePercent
                ));

                AdminNotifier::notify(new AdminAlertMail(
                    subjectLine: 'Withdrawal Approved',
                    badge: 'WITHDRAWAL APPROVED',
                    badgeColor: 'success',
                    heading: 'A withdrawal was completed',
                    intro: "{$withdrawal->user->email}'s withdrawal has been approved and processed.",
                    rows: [
                        'User' => $withdrawal->user->email,
                        'Reference' => $withdrawal->reference,
                        'Amount' => '$' . number_format($totalToDebit, 2),
                        'Fee' => '$' . number_format($feeAmount, 2),
                        'Wallet' => $withdrawal->currency->name,
                    ],
                    url: url('/admin/withdrawals'),
                    ctaLabel: 'View Withdrawal'
                ));
            }
        });
    }

    private static function availableRankBonus(int $userId): float {
        return RankBonus::where('user_id', $userId)
            ->sum(DB::raw('amount - withdrawn'));
    }

    private static function debitRankBonus(int $userId, float $amount): float {
        $bonuses = RankBonus::where('user_id', $userId)
            ->whereColumn('withdrawn', '<', 'amount')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();

        foreach ($bonuses as $bonus) {
            if ($amount <= 0) break;

            $available = $bonus->amount - $bonus->withdrawn;

            $deduct = min($available, $amount);
            $bonus->increment('withdrawn', $deduct);

            $amount -= $deduct;
        }

        return $amount;
    }

    private static function availableReferralBonus(int $userId): float {
        return ReferralReward::where('user_id', $userId)
            ->sum(DB::raw('amount - withdrawn'));
    }

    private static function debitReferralRewards(int $userId, float $amount): void {
        $rewards = ReferralReward::where('user_id', $userId)
            ->whereColumn('withdrawn', '<', 'amount')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();

        foreach ($rewards as $reward) {
            if ($amount <= 0) break;

            $available = $reward->amount - $reward->withdrawn;

            $deduct = min($available, $amount);
            $reward->increment('withdrawn', $deduct);

            $amount -= $deduct;
        }
    }


    public static function markAsProcessing(Withdrawal $withdrawal) {
        if ($withdrawal->status === WithdrawalStatus::COMPLETED || $withdrawal->status === WithdrawalStatus::FAILED) {
            return;
        }

        DB::transaction(function () use ($withdrawal) {

            $withdrawal->markProcessing();

            // send email
        });
    }

    /**
     * Reject a withdrawal request. No balance was ever debited for a
     * pending/processing withdrawal, so there's nothing to refund — this
     * just records the reason, flips the status to failed, and notifies
     * the user and admins.
     */
    public static function markAsFailed(Withdrawal $withdrawal, ?string $reason = null): void {
        if (in_array($withdrawal->status, [
            WithdrawalStatus::COMPLETED,
            WithdrawalStatus::FAILED,
            WithdrawalStatus::CANCELLED,
        ], true)) {
            return;
        }

        $reason = $reason !== null && trim($reason) !== ''
            ? trim($reason)
            : 'Your withdrawal request could not be approved.';

        DB::transaction(function () use ($withdrawal, $reason) {
            $withdrawal->markFailed($reason);

            $amount = (float) ($withdrawal->meta['total_to_debit'] ?? $withdrawal->amount);
            $walletLabel = $withdrawal->currency?->name ?? 'Wallet';

            Mail::to($withdrawal->user->email)->send(new WithdrawalRejectedMail(
                $amount,
                $withdrawal->reference,
                $walletLabel,
                now()->format('l, d F Y • h:i A'),
                $reason,
                route('dashboard')
            ));

            AdminNotifier::notify(new AdminAlertMail(
                subjectLine: 'Withdrawal Rejected',
                badge: 'WITHDRAWAL REJECTED',
                badgeColor: 'danger',
                heading: 'A withdrawal was rejected',
                intro: "{$withdrawal->user->email}'s withdrawal request was rejected.",
                rows: [
                    'User' => $withdrawal->user->email,
                    'Reference' => $withdrawal->reference,
                    'Amount' => '$' . number_format($amount, 2),
                    'Wallet' => $walletLabel,
                    'Reason' => $reason,
                ],
                url: url('/admin/withdrawals'),
                ctaLabel: 'View Withdrawal'
            ));
        });
    }
}
