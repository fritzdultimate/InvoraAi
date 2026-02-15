<?php

namespace App\Services\Wallet;

use App\Enums\LedgerAsset;
use App\Models\User;
use App\Models\WalletLedger;
use App\Enums\LedgerReference;
use Illuminate\Support\Facades\DB;

class WalletService {
    public static function credit(
        User $user,
        float $amount,
        LedgerReference $referenceType,
        ?int $referenceId = null,
        ?string $description = null,
        LedgerAsset $asset
    ) {
        return DB::transaction(function () use (
            $user,
            $amount,
            $referenceType,
            $referenceId,
            $description,
            $asset
        ) {

            $currentBalance = self::getBalance($user, $asset->value);

            $newBalance = $currentBalance + $amount;

            return WalletLedger::create([
                'user_id' => $user->id,
                'credit' => $amount,
                'debit' => 0,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType->value,
                'reference_id' => $referenceId,
                'description' => $description,
                'asset' => $asset->value
            ]);
        });
    }

    public static function debit(
        User $user,
        float $amount,
        LedgerReference $referenceType,
        ?int $referenceId = null,
        ?string $description = null,
        LedgerAsset $asset,
    ) {
        return DB::transaction(function () use (
            $user,
            $amount,
            $referenceType,
            $referenceId,
            $description,
            $asset
        ) {

            $currentBalance = self::getBalance($user, $asset->value);

            if ($currentBalance < $amount) {
                throw new \Exception('Insufficient balance.');
            }

            $newBalance = $currentBalance - $amount;

            return WalletLedger::create([
                'user_id' => $user->id,
                'credit' => 0,
                'debit' => $amount,
                'balance_after' => $newBalance,
                'reference_type' => $referenceType->value,
                'reference_id' => $referenceId,
                'description' => $description,
                'asset' => $asset->value
            ]);
        });
    }

    public static function getBalance(User $user, string $asset): float {
        // $ledger = WalletLedger::where([
        //     'user_id' => $user->id,
        //     'asset' => $asset
        // ])->first();
        // if(!$ledger) {

        // }
        return (float) WalletLedger::where('user_id', $user->id)
            ->where('asset', $asset)
            ->latest()
            ->value('balance_after') ?? 0;
    }
}
