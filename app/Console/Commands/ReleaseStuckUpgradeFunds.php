<?php

namespace App\Console\Commands;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\WalletLedger;
use App\Services\Wallet\WalletService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// One-off clean-up. License upgrades used to return only an investment's starting capital to the main balance,
// so profit the user had reinvested stayed in locked balance after the investment closed. This finds those
// investments and moves the difference to the main balance. Shows what it would do unless --apply is given.
class ReleaseStuckUpgradeFunds extends Command
{
    protected $signature = 'bots:release-stuck-upgrade-funds {--apply : Move the funds. Without it, only list them.}';

    protected $description = 'Return reinvested funds left in locked balance by old license upgrades';

    const NOTE = 'license upgrade - reinvested funds released';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $rows = [];
        $total = '0';

        // Investments closed by an upgrade, from the ledger row the upgrade wrote.
        $closed = WalletLedger::where('reference_type', LedgerReference::BOT_INVESTMENT_COMPLETED)
            ->where('description', 'license upgrade - investment completed')
            ->where('asset', LedgerAsset::LOCKEDBALANCE)
            ->where('debit', '>', 0)
            ->get();

        foreach ($closed as $row) {
            $investment = BotInvestment::with('user')->find($row->reference_id);

            if (! $investment || ! $investment->user) {
                continue;
            }

            $stuck = bcsub((string) $investment->amount, (string) $row->debit, 8);

            $alreadyReleased = WalletLedger::where('reference_type', LedgerReference::BOT_INVESTMENT_COMPLETED)
                ->where('reference_id', $investment->id)
                ->where('description', self::NOTE)
                ->exists();

            if (bccomp($stuck, '0', 8) <= 0 || $alreadyReleased) {
                continue;
            }

            $locked = (string) WalletService::getBalance($investment->user, LedgerAsset::LOCKEDBALANCE->value);
            $status = bccomp($locked, $stuck, 8) >= 0 ? 'ready' : 'locked balance too low';

            if ($apply && $status === 'ready') {
                DB::transaction(function () use ($investment, $stuck) {
                    WalletService::debit($investment->user, (float) $stuck, LedgerReference::BOT_INVESTMENT_COMPLETED, $investment->id, self::NOTE, LedgerAsset::LOCKEDBALANCE);
                    WalletService::credit($investment->user, (float) $stuck, LedgerReference::BOT_INVESTMENT_COMPLETED, $investment->id, self::NOTE, LedgerAsset::MAIN);
                });
                $status = 'released';
                $total = bcadd($total, $stuck, 8);
            }

            $rows[] = [$investment->code, $investment->user->email, $investment->amount, $row->debit, $stuck, $status];
        }

        if (! $rows) {
            $this->info('Nothing is stuck. Every upgraded investment returned its full amount.');

            return self::SUCCESS;
        }

        $this->table(['Investment', 'User', 'Amount', 'Returned', 'Stuck', 'Status'], $rows);

        $apply
            ? $this->info("Released {$total} in total to users' main balances.")
            : $this->comment('Nothing was moved. Run again with --apply to release the funds marked ready.');

        return self::SUCCESS;
    }
}
