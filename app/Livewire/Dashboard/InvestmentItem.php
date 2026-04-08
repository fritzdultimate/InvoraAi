<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\BotTermination;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class InvestmentItem extends Component {
    public $investment;
    public $confirmingTerminate = false;
    public $compoundAmount;
    public $showConfirm = false;
    public $type = 'success';
    public $title = '';
    public $message = '';
    public $confirmText;
    public $action;
    public $warning;

    public function mount($id) {
        $this->investment = BotInvestment::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();
    }

    public function compoundProfit() {
        $inv = $this->investment;

        if ($inv->isMatured()) return;

        $amount = (float) $this->compoundAmount;

        if ($amount <= 0) {
            $this->dispatch('error', message: 'Invalid amount');
            return;
        }

        if ($amount > $inv->total_profit) {
            $this->dispatch('error', message: 'Cannot exceed available profit');
            return;
        }

        DB::transaction(function () use ($inv, $amount) {

            // 🔥 Reduce profit
            $inv->total_profit = bcsub((string)$inv->total_profit, (string)$amount, 8);

            // 🔥 Increase capital
            $inv->amount = bcadd((string)$inv->amount, (string)$amount, 8);

            $inv->save();

            WalletService::debit(
                $inv->user,
                $amount,
                LedgerReference::REINVESTMENT,
                $inv->id,
                'reinvestment',
                LedgerAsset::PROFIT
            );

            WalletService::credit(
                $inv->user,
                $amount,
                LedgerReference::REINVESTMENT,
                $inv->id,
                'reinvestment',
                LedgerAsset::LOCKEDBALANCE
            );

            // Optional: track compounding history
            // DB::table('investment_compounds')->insert([
            //     'bot_investment_id' => $inv->id,
            //     'amount' => $amount,
            //     'created_at' => now(),
            // ]);
        });

        $this->compoundAmount = null;

        $this->dispatch('success', message: 'Profit compounded successfully.');

        $this->investment->refresh();
    }

    public function cancelConfirm() {
        $this->showConfirm = false;
    }

    public function confirmAction() {
        $action = $this->action;

        if (method_exists($this, $action)) {
            $this->$action();
        }

        $this->showConfirm = false;
    }


    public function prepareCompoundProfit() {

        $inv = $this->investment;

        if ($inv->isMatured()) return;

        $amount = (float) $this->compoundAmount;

        if ($amount <= 0) {
            $this->dispatch('error', message: 'Invalid amount');
            return;
        }

        if ($amount > $inv->total_profit) {
            $this->dispatch('error', message: 'Cannot exceed available profit');
            return;
        }

        $this->showConfirm = true;

        $this->type = 'success';
        $this->title = 'Reinvest Profit';
        $this->message = 'This amount will be added to your capital and continue earning returns.';
        $this->confirmText = 'Confirm Reinvestment';
        $this->action = 'compoundProfit';
    }

    public function terminateInvestment() {
        $inv = $this->investment;
        $termination = BotTermination::where([
            'bot_investment_id' => $inv->id
        ])
        ->whereNotNull('terminated_at')
        ->first();

        if($termination) return;

        if ($inv->isMatured()) return;

        DB::transaction(function () use($inv) {

            $penalty = $inv->bot->early_withdrawal_penalty_percent;

            $deduction = bcmul(
                (string)$inv->capital,
                bcdiv((string)$penalty, '100', 8),
                8
            );

            $returnAmount = bcsub((string)$inv->capital, $deduction, 8);

            $inv->update([
                'status' => 'termination_requested',
                // 'is_early_terminated' => true,
                // 'matures_at' => now()
            ]);

            // debit locked_balance & credit user

            $termination = BotTermination::create([
                'bot_investment_id' => $inv->id,
                'penalty_percent' => $penalty,
                'penalty_amount' => $deduction,
                'amount_returned' => $returnAmount,
                // 'terminated_at' => now()
            ]);

            // WalletService::debit(
            //     $inv->user,
            //     $deduction,
            //     LedgerReference::BOT_TERMINATION_FEE,
            //     $termination->id,
            //     'bot termination fee',
            //     LedgerAsset::LOCKEDBALANCE
            // );

            // WalletService::debit(
            //     $inv->user,
            //     $returnAmount,
            //     LedgerReference::BOT_TERMINATION,
            //     $inv->id,
            //     'bot termination',
            //     LedgerAsset::LOCKEDBALANCE
            // );

            // WalletService::credit(
            //     $inv->user,
            //     $returnAmount,
            //     LedgerReference::BOT_TERMINATION,
            //     $inv->id,
            //     'bot termination',
            //     LedgerAsset::MAIN
            // );

            session()->flash('success', 'Termination request sent.');
        });
    }

    public function confirmTerminate() {
        $this->confirmingTerminate = true;
    }

    public function terminateInvestmentConfirmed() {
        $this->terminateInvestment();
        $this->confirmingTerminate = false;
    }

    public function render() {
        return view('livewire.dashboard.investment-item'); 
    }
}
