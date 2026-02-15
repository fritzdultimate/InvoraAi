<?php

namespace App\Services\Bot;

use App\Enums\LedgerReference;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Enums\BotInvestmentStatus;
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

    public function terminate(BotInvestment $investment) {

    if ($investment->status !== BotInvestmentStatus::ACTIVE) {
        throw new \Exception('Investment not active.');
    }

    return DB::transaction(function () use ($investment) {

        $bot = $investment->bot;

        $penaltyPercent = $bot->early_withdrawal_penalty_percent;

        $penalty = $investment->amount * ($penaltyPercent / 100);

        $finalReturn = $investment->amount - $penalty;

        $investment->user->increment('balance', $finalReturn);

        WalletService::debit(
            $investment->user,
            $finalReturn,
            LedgerReference::BOT_TERMINATION,
            $investment->id,
            'Bot termination'
        );

        $investment->update([
            'status' => BotInvestmentStatus::TERMINATED,
            'is_early_terminated' => true,
        ]);

        BotTermination::create([
            'bot_investment_id' => $investment->id,
            'penalty_percent' => $penaltyPercent,
            'penalty_amount' => $penalty,
            'amount_returned' => $finalReturn,
            'terminated_at' => now()
        ]);

        return $finalReturn;
    });
}

}
