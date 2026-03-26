<?php

namespace App\Services;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\DepositApprovedMail;
use App\Models\CustomSetting;
use App\Models\Deposit;
use App\Services\Wallet\WalletService;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class DepositService {
    public static function depositBonus($deposit) {
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

        return $deposit->bonus;
    }

    public static function markAsFinished(Deposit $deposit) {
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
}