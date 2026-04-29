<?php

namespace App\Services\Bot;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Enums\BotInvestmentStatus;
use App\Models\BotLicenseUpgrade;
use App\Models\BotTermination;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;

class BotInvestmentService {
    public static function create($user, Bot $bot, $license, float $amount) {
        if (!$license->isActive()) {
            throw new \Exception('License not active.');
        }

        if ($amount < $bot->min_amount || $amount > $bot->max_amount) {
            throw new \Exception('Amount outside allowed range.');
        }

        return DB::transaction(function () use ($user, $bot, $license, $amount) {

            // Deduct from wallet
            $user->decrement('balance', $amount);

            WalletService::debit(
                $user,
                $amount,
                LedgerReference::BOT_INVESTMENT,
                $user->id,
                'Bot investment'
            );

            return BotInvestment::create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'bot_license_id' => $license->id,
                'amount' => $amount,
                'started_at' => now(),
                'matures_at' => now()->addDays($bot->lock_days),
                'status' => BotInvestmentStatus::ACTIVE,
            ]);
        });
    }

    public static function terminate(BotTermination $termination) { 

        $investment = $termination->botInvestment;

        if ($investment->status !== BotInvestmentStatus::TERMINATIONREQUEST) {
            throw new \Exception('Investment cannot be terminated. Investment is ' . $investment->status->value);
        }

        return DB::transaction(function () use ($investment, $termination) {

            $bot = $investment->bot;

            $penaltyPercent = $bot->early_withdrawal_penalty_percent;

            $penalty = $investment->amount * ($penaltyPercent / 100);

            $finalReturn = $investment->amount - $penalty;

            WalletService::debit(
                $investment->user,
                $finalReturn,
                LedgerReference::BOT_TERMINATION,
                $investment->id,
                'Bot termination',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::debit(
                $investment->user,
                $penalty,
                LedgerReference::BOT_TERMINATION_FEE,
                $investment->id,
                'bot termination fee',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::credit(
                $investment->user,
                $finalReturn,
                LedgerReference::BOT_TERMINATION,
                $investment->id,
                'bot termination',
                LedgerAsset::MAIN
            );

            $investment->update([
                'status' => BotInvestmentStatus::TERMINATED,
                'is_early_terminated' => true,
            ]);

            $termination->update([
                'terminated_at' => now()
            ]);

            return $finalReturn;
        });
    }

    public static function adminTerminate(BotInvestment $investment) {

        if ($investment->status === BotInvestmentStatus::COMPLETED) {
            throw new \Exception('Investment cannot be terminated. Investment is ' . $investment->status->value);
        }

        return DB::transaction(function () use ($investment) {

            $bot = $investment->bot;

            $penaltyPercent = $bot->early_withdrawal_penalty_percent;

            $penalty = $investment->amount * ($penaltyPercent / 100);

            $finalReturn = $investment->amount - $penalty;

            WalletService::debit(
                $investment->user,
                $finalReturn,
                LedgerReference::BOT_TERMINATION,
                $investment->id,
                'Bot investment termination from admin',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::debit(
                $investment->user,
                $penalty,
                LedgerReference::BOT_TERMINATION_FEE,
                $investment->id,
                'bot investment termination from admin fee',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::credit(
                $investment->user,
                $finalReturn,
                LedgerReference::BOT_TERMINATION,
                $investment->id,
                'bot investment termination from admin',
                LedgerAsset::MAIN
            );

            $investment->update([
                'status' => BotInvestmentStatus::TERMINATED,
                'is_early_terminated' => true,
            ]);

            return $finalReturn;
        });
    }

    public static function refundToWallet($investment, $walletType, $amount) {
        WalletService::debit(
            $investment->user,
            $amount,
            LedgerReference::BOT_INVESTMENT_STOPPED,
            $investment->id,
            'Bot investment stopped from admin',
            LedgerAsset::LOCKEDBALANCE
        );

            WalletService::credit(
                $investment->user,
                $amount,
                LedgerReference::BOT_INVESTMENT_STOPPED,
                $investment->id,
                'bot investment stopped from admin',
                LedgerAsset::from($walletType)
            );

            $investment->update([
                'status' => BotInvestmentStatus::COMPLETED,
                'matures_at' => now(),
            ]);
    }

    public static function customAdjustment($investment, $walletType, $amount, $debitLocked) {
        if($debitLocked) {
            WalletService::debit(
                $investment->user,
                $amount,
                LedgerReference::BOT_INVESTMENT_STOPPED,
                $investment->id,
                'Bot investment stopped from admin',
                LedgerAsset::LOCKEDBALANCE
            );
        }

            WalletService::credit(
                $investment->user,
                $amount,
                LedgerReference::BOT_INVESTMENT_STOPPED,
                $investment->id,
                'bot investment stopped from admin',
                LedgerAsset::from($walletType)
            );

            $investment->update([
                'status' => BotInvestmentStatus::COMPLETED,
                'matures_at' => now(),
            ]);
    }

    public static function upgrade($license, $bot, $asset) {
        DB::transaction(function () use ($license, $bot, $asset) {

            $asset = $asset === 'deposit' ? LedgerAsset::DEPOSIT : LedgerAsset::MAIN;
            WalletService::debit(
                $license->user,
                $bot->price,
                LedgerReference::LICENSE_UPGRADE,
                $license->id,
                'license upgrade',
                $asset

            );

            // before the upgrade, stop all investments on former license
            $investments = BotInvestment::where('user_id', $license->user->id)
                ->where('bot_license_id', $license->id)
                ->where('matures_at', '>', now())
                ->where('status', 'active')
                ->get();

            foreach($investments as $inv) {
                // mark completed
                $inv->update([
                    'matures_at' => now(),
                    'status' => 'completed'
                ]);

                // debit investment capital from locked balance
                WalletService::debit(
                    $license->user,
                    $inv->capital,
                    LedgerReference::BOT_INVESTMENT_COMPLETED,
                    $inv->id,
                    'license upgrade - investment completed',
                    LedgerAsset::LOCKEDBALANCE

                );

                // credit investment capital to main balance
                WalletService::credit(
                    $license->user,
                    $inv->capital,
                    LedgerReference::BOT_INVESTMENT_COMPLETED,
                    $inv->id,
                    'license upgrade - investment completed',
                    LedgerAsset::MAIN

                );
            }

            BotLicenseUpgrade::create([
                'bot_license_id' => $license->id,
                'user_id' => $license->user->id,
                'from_bot_id' => $license->bot->id,
                'to_bot_id' => $bot->id,
                'price_paid' => $bot->price,
                'status' => 'upgraded'
            ]);

            $license->update([
                'bot_id' => $bot->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($bot->license_duration_days),
                'status' => 'active'
            ]);
        });
    }

}
