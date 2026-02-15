<?php

namespace App\Domain\Withdrawal;

use App\Enums\WithdrawalStatus;
use App\Models\ReferralReward;
use App\Models\Stake;
use App\Models\User;
use App\Models\Withdrawal;
use DomainException;

class WithdrawalRules {
    public static function canCreate(User $user, $amount, $asset = 'balance'): void {
        self::noPendingWithdrawal($user);
        // self::checkBalance($user, $amount, $asset);
        self::minimumAmount($amount);
        self::maximumAmount($amount);
        // self::kycRequired($user);
        self::cooldownCheck($user);
    }

    protected static function kycRequired($user) {
        if($user->kyc_status !== 'approved') {
            throw new DomainException(
                "For security and compliance reasons, you must complete KYC verification before proceeding."
            );
        }
    }

    protected static function cooldownCheck($user): void {
        $lastWithdrawal = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->first();

        if (! $lastWithdrawal) {
            return;
        }

        if ($lastWithdrawal->created_at->diffInMinutes(now()) < 30) {
            throw new DomainException(
                'Please wait 30 minutes before making another withdrawal.'
            );
        }
    }

    /**
     * Minimum withdrawal rule
     */
    protected static function minimumAmount(string $amount): void {
        $min = config('withdrawal.minimum', '50');

        if (bccomp($amount, $min, 8) === -1) {
            throw new DomainException(
                "Minimum withdrawal amount is $" . number_format($min, 2)
            );
        }
    }

    protected static function existingReferralRewardWithdrawal(): void {
        $exist = Withdrawal::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where('asset', 'referral_rewards')
            ->exists();

        if ($exist) {
            throw new DomainException(
                "Please wait for your initial pending referral bonus withdrawal to be resolved."
            );
        }
    }

    protected static function userOnCompounding(User $user): void {
        $compounded = $user->stakes()
            ->where('status', 'active')
            ->where('compounding', true)
            ->exists();
        
        if($compounded) {
            throw new DomainException(
                "Account has active compounding stakes. Withdrawals are not allowed."
            );
        }
    }

    /**
     * Maximum withdrawal rule (per request)
     */
    protected static function maximumAmount(string $amount): void {
        $max = config('withdrawal.maximum', '100000');

        if (bccomp($amount, $max, 8) === 1) {
            throw new DomainException(
                'Maximum withdrawal amount per request is $' . number_format($max, 2)
            );
        }
    }

    protected static function noPendingWithdrawal(User $user): void {
        $hasPending = Withdrawal::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                WithdrawalStatus::PENDING,
                WithdrawalStatus::PROCESSING,
            ])
            ->exists();

        if ($hasPending) {
            throw new DomainException(
                'You already have a pending withdrawal. Please wait until it is processed.'
            );
        }
    }

    protected static function checkBalance(User $user, $amount, $asset = 'balance'): void {
        // dd($asset);
        if($asset === 'referral_rewards') {

            $totalAvailable = ReferralReward::where('user_id', auth()->id())
                ->get()
                ->sum(fn ($reward) => $reward->amount - ($reward->withdrawn ?? 0));

            if ($amount > $totalAvailable) {
                throw new DomainException(
                    'Insufficient referral balance'
                );
            }
        } else {

            if (bccomp($user->balance, $amount, 8) === -1) {
                throw new DomainException(
                    'Insufficient account balance to complete this withdrawal.'
                );
            }
        }
    }
}
