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

    public function mount($id) {
        $this->investment = BotInvestment::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();
    }

    public function terminateInvestment() {
        $inv = $this->investment;

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
                'status' => 'terminated',
                'is_early_terminated' => true,
                'matures_at' => now()
            ]);

            // debit locked_balance & credit user

            $termination = BotTermination::create([
                'bot_investment_id' => $inv->id,
                'penalty_percent' => $penalty,
                'penalty_amount' => $deduction,
                'amount_returned' => $returnAmount,
                'terminated_at' => now()
            ]);

            WalletService::debit(
                $inv->user,
                $deduction,
                LedgerReference::BOT_TERMINATION_FEE,
                $termination->id,
                'bot termination fee',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::debit(
                $inv->user,
                $returnAmount,
                LedgerReference::BOT_TERMINATION,
                $inv->id,
                'bot termination',
                LedgerAsset::LOCKEDBALANCE
            );

            WalletService::credit(
                $inv->user,
                $returnAmount,
                LedgerReference::BOT_TERMINATION,
                $inv->id,
                'bot termination',
                LedgerAsset::MAIN
            );

            session()->flash('success', 'Investment terminated');
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
