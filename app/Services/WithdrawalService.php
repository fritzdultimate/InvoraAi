<?php

namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Enums\WithdrawalStatus;
use App\Mail\WithdrawalCompletedMail;
use App\Models\CustomSetting;
use App\Models\RankBonus;
use App\Models\ReferralReward;
use App\Models\Withdrawal;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class  WithdrawalService {
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

    public static function markAsFailed(Withdrawal $withdrawal) {

    }
}
