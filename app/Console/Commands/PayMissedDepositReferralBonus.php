<?php

namespace App\Console\Commands;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Deposit;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\ReferralLevel;
use App\Models\User;
use App\Models\WalletLedger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// One-off. Pays the upline referral bonus for a deposit that was skipped because the depositor is a leader
// with "distribute referral bonus" switched off. Creates the same pending bonuses the normal deposit flow
// would have made (same level percents and lock days). Shows what it would do unless --apply is given.
class PayMissedDepositReferralBonus extends Command
{
    protected $signature = 'referrals:pay-missed-deposit
        {user : Id of the user who made the deposit}
        {--deposit= : Deposit id. Defaults to the user\'s latest credited deposit}
        {--apply : Create the bonuses. Without it, only list them.}';

    protected $description = 'Pay upline referral bonuses for a deposit that was skipped';

    public function handle(): int
    {
        $depositor = User::find($this->argument('user'));
        if (! $depositor) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $credits = WalletLedger::where('user_id', $depositor->id)
            ->where('reference_type', LedgerReference::DEPOSIT->value)
            ->where('asset', LedgerAsset::DEPOSIT->value)
            ->where('credit', '>', 0)
            ->where('description', 'not like', 'made by admin%'); // manual admin top-ups are not deposits

        if ($this->option('deposit')) {
            $credits->where('reference_id', $this->option('deposit'));
        }

        $last = (clone $credits)->latest('id')->first();
        if (! $last) {
            $this->error('No credited deposit found for this user.');
            return self::FAILURE;
        }

        $deposit = Deposit::where('user_id', $depositor->id)->find($last->reference_id);
        if (! $deposit) {
            $this->error("Deposit #{$last->reference_id} not found.");
            return self::FAILURE;
        }

        // what was really credited (a partial payment can be credited in more than one row)
        $amount = (string) (clone $credits)->where('reference_id', $deposit->id)->sum('credit');

        $tree = Referral::where('user_id', $depositor->id)->first();
        $levels = ReferralLevel::where('is_active', true)->orderBy('level')->get();

        $this->info("Depositor: #{$depositor->id} {$depositor->email}");
        $this->info("Deposit: #{$deposit->id} {$deposit->reference} credited \${$amount} on {$last->created_at}");

        if (! $tree || $levels->isEmpty()) {
            $this->warn('No referral tree or no active referral levels. Nothing to pay.');
            return self::SUCCESS;
        }

        $rows = [];
        $total = '0';

        foreach ($levels as $config) {
            if ($config->level > 10) break;

            $referrer = User::find($tree->{"level_{$config->level}_id"} ?? null);
            if (! $referrer || $referrer->id === $depositor->id) continue;

            $bonus = bcmul($amount, bcdiv((string) $config->percent, '100', 8), 8);
            if (bccomp($bonus, '0', 8) <= 0) continue;

            $exists = ReferralBonus::where('deposit_id', $deposit->id)->where('level', $config->level)->exists();

            $rows[] = [
                'level' => $config->level,
                'referrer' => $referrer,
                'percent' => $config->percent,
                'bonus' => $bonus,
                'lock_days' => $config->lock_days,
                'exists' => $exists,
            ];

            if (! $exists) {
                $total = bcadd($total, $bonus, 8);
            }
        }

        $this->table(
            ['Level', 'Upline', 'Percent', 'Bonus', 'Unlocks', 'Status'],
            collect($rows)->map(fn ($r) => [
                $r['level'],
                "#{$r['referrer']->id} {$r['referrer']->email}",
                $r['percent'] . '%',
                '$' . number_format((float) $r['bonus'], 2),
                now()->addDays($r['lock_days'])->format('M j, Y'),
                $r['exists'] ? 'already paid, skip' : 'to pay',
            ])
        );

        $this->info('Total to pay: $' . number_format((float) $total, 2));

        if (! $this->option('apply')) {
            $this->warn('Dry run. Nothing was created. Run again with --apply to pay.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($rows, $depositor, $deposit, $amount) {
            foreach ($rows as $r) {
                if ($r['exists']) continue;

                ReferralBonus::create([
                    'user_id' => $r['referrer']->id,
                    'referred_by_id' => $r['referrer']->id,
                    'from_user_id' => $depositor->id,
                    'deposit_id' => $deposit->id,
                    'level' => $r['level'],
                    'percent' => $r['percent'],
                    'amount' => $r['bonus'],
                    'status' => 'pending',
                    'claimable_at' => now()->addDays($r['lock_days']),
                    'calculated_for' => now()->startOfDay(),
                    'meta' => [
                        'deposit_reference' => $deposit->reference,
                        'deposit_amount' => $amount,
                        'paid_manually' => true,
                    ],
                ]);
            }
        });

        $this->info('Bonuses created. They unlock and credit through the normal referral claim cron.');

        return self::SUCCESS;
    }
}
