<?php

namespace App\Services\Bot;

use App\Enums\BotInvestmentStatus;
use App\Enums\BotLicenseStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BotLicenseService {
    public function purchase($user, Bot $bot): BotLicense {
        return DB::transaction(function () use ($user, $bot) {

            return BotLicense::create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($bot->license_duration_days),
                'status' => BotLicenseStatus::ACTIVE,
            ]);
        });
    }

    // A license that has run out stops every investment running under it: no more profit,
    // and each investment's funds go back from locked balance to the main balance.
    public function expireLicenses(): int {
        $expired = 0;

        BotLicense::where('status', BotLicenseStatus::ACTIVE)
            ->where('expires_at', '<=', now())
            ->chunkById(100, function ($licenses) use (&$expired) {
                foreach ($licenses as $license) {
                    // One user's problem (say, a short locked balance) must not stop everyone else's payouts.
                    try {
                        DB::transaction(function () use ($license) {
                            $investments = BotInvestment::with('user')
                                ->where('bot_license_id', $license->id)
                                ->whereIn('status', [BotInvestmentStatus::ACTIVE, BotInvestmentStatus::TERMINATIONREQUEST])
                                ->lockForUpdate()
                                ->get();

                            foreach ($investments as $investment) {
                                $this->returnFunds($investment);
                            }

                            $license->update(['status' => BotLicenseStatus::EXPIRED]);
                        });

                        $expired++;
                    } catch (\Throwable $e) {
                        Log::error("Could not expire bot license {$license->id}: {$e->getMessage()}");
                    }
                }
            });

        return $expired;
    }

    private function returnFunds(BotInvestment $investment): void {
        WalletService::debit(
            $investment->user,
            $investment->amount,
            LedgerReference::BOT_INVESTMENT_STOPPED,
            $investment->id,
            'license expired - investment stopped',
            LedgerAsset::LOCKEDBALANCE
        );

        WalletService::credit(
            $investment->user,
            $investment->amount,
            LedgerReference::BOT_INVESTMENT_STOPPED,
            $investment->id,
            'license expired - investment stopped',
            LedgerAsset::MAIN
        );

        $investment->update([
            'status' => BotInvestmentStatus::COMPLETED,
            'matures_at' => now(),
        ]);
    }
}
