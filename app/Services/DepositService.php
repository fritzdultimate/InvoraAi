<?php

namespace App\Services;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\DepositApprovedMail;
use App\Models\CustomSetting;
use App\Models\Deposit;
use App\Models\User;
use App\Services\Wallet\WalletService;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class DepositService {
    public static function depositBonus($deposit) {
        if ($deposit->bonus > 0) {
            return $deposit->bonus;
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

        NotificationService::createForUser(auth()->user(), [
            'title' => 'Deposit Bonus Received 🎉',
            'message' => 'You have received your deposit bonus! Check your balance to see the updated amount.',
        ]);

        return $deposit->bonus;
    }

    public static function markAsFinished(Deposit $deposit) {
        if ($deposit->status === DepositStatus::FINISHED) {
            throw new Halt('Deposit already processed.');
        }
        if($deposit->status === DepositStatus::FINISHED || $deposit->status === DepositStatus::CANCELLED || $deposit->status === DepositStatus::FAILED || $deposit->status === DepositStatus::EXPIRED) {
            throw new Halt('This deposit cannot be approved.');
        }

        DB::transaction(function() use($deposit) {
            $deposit->update([
                'status' => DepositStatus::FINISHED,
                'actually_paid' => $deposit->amount
            ]);

            WalletService::credit(
                $deposit->user,
                $deposit->amount,
                LedgerReference::DEPOSIT,
                $deposit->id,
                'Admin confirms deposit',
                LedgerAsset::DEPOSIT
            );

            $bonus = self::depositBonus($deposit);

            Mail::to($deposit->user->email)->send(new DepositApprovedMail(
                $deposit->amount,
                $deposit->reference,
                $deposit->currency,
                now()->format('l, d F Y • h:i A'),
                'https://invora.ai/dashboard',
                $bonus
            ));
        });
    }

    public static function debitForInvestment(User $user, float $amount): void {
        DB::transaction(function () use ($user, $amount) {

            $remaining = $amount;

            
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

        });
    }
}