<?php

namespace App\Services\Admin;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Deposit;
use App\Models\ReferralBonus;
use App\Models\User;
use App\Services\ReferralBonusService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

// deposits an admin creates for a user; they count as real deposits (team volume, referral bonus)
class AdminDepositService
{
    public const STATUSES = [
        'finished' => 'Finished',
        'partially_paid' => 'Partially paid',
        'pending' => 'Pending (approve later)',
    ];

    /**
     * $data: amount, actually_paid, currency, status, note, date, credit_wallet, distribute_bonus
     *
     * @throws \DomainException
     */
    public static function create(User $user, array $data): Deposit
    {
        $status = DepositStatus::tryFrom($data['status'] ?? '');
        if (! $status || ! array_key_exists($status->value, self::STATUSES)) {
            throw new \DomainException('Choose a valid status.');
        }

        $amount = round((float) ($data['amount'] ?? 0), 2);
        $settled = $status !== DepositStatus::PENDING;
        $paid = $settled ? round((float) ($data['actually_paid'] ?? $amount), 2) : 0;

        if ($amount <= 0) {
            throw new \DomainException('Amount must be greater than zero.');
        }
        if ($settled && $paid <= 0) {
            throw new \DomainException('Actually paid must be greater than zero.');
        }

        $date = ! empty($data['date']) ? \Carbon\Carbon::parse($data['date']) : now();

        $deposit = DB::transaction(function () use ($user, $data, $status, $amount, $paid, $settled, $date) {
            $deposit = Deposit::create([
                'user_id' => $user->id,
                'reference' => generate_deposit_reference(),
                'currency' => strtolower($data['currency'] ?? 'usdt'),
                'amount' => $amount,
                'actually_paid' => $paid,
                'status' => $status,
                'source' => 'admin',
                'created_by' => auth()->id(),
                'narration' => $data['note'] ?? null,
                'received_at' => $settled ? $date : null,
                'confirmed_at' => $settled ? $date : null,
                'created_at' => $date,
                'updated_at' => now(),
            ]);

            if ($settled && ! empty($data['credit_wallet'])) {
                WalletService::credit(
                    $user,
                    $paid,
                    LedgerReference::DEPOSIT,
                    $deposit->id,
                    'Deposit added by admin',
                    LedgerAsset::DEPOSIT
                );
            }

            return $deposit;
        });

        AdminLogger::record('deposit_created', $deposit, "Created a {$status->value} deposit of \${$paid} for {$user->email}", [], [
            'amount' => $amount,
            'actually_paid' => $paid,
            'credited_wallet' => ! empty($data['credit_wallet']),
        ]);

        if ($settled && ! empty($data['distribute_bonus'])) {
            self::distributeReferralBonus($deposit);
        }

        return $deposit;
    }

    public static function canDistribute(Deposit $deposit): bool
    {
        return in_array($deposit->status, [DepositStatus::FINISHED, DepositStatus::PARTIALLYPAID], true)
            && (float) $deposit->actually_paid > 0
            && $deposit->user_id
            && ! ReferralBonus::where('deposit_id', $deposit->id)->exists();
    }

    /**
     * Pays the upline for this deposit (levels already paid are skipped) and returns the bonuses created now.
     *
     * @throws \DomainException
     */
    public static function distributeReferralBonus(Deposit $deposit): Collection
    {
        if (! in_array($deposit->status, [DepositStatus::FINISHED, DepositStatus::PARTIALLYPAID], true) || (float) $deposit->actually_paid <= 0) {
            throw new \DomainException('Only a finished deposit with an amount paid can give referral bonus.');
        }

        $before = ReferralBonus::where('deposit_id', $deposit->id)->pluck('id');

        ReferralBonusService::distributeForDeposit($deposit, (float) $deposit->actually_paid);

        $created = ReferralBonus::with('user')
            ->where('deposit_id', $deposit->id)
            ->whereNotIn('id', $before)
            ->orderBy('level')
            ->get();

        AdminLogger::record('referral_bonus_distributed', $deposit, "Distributed referral bonus for deposit {$deposit->reference}", [], [
            'bonuses' => $created->count(),
            'total' => (float) $created->sum('amount'),
        ]);

        return $created;
    }
}
