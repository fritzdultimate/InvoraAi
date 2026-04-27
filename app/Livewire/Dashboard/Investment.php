<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\BotLicenseUpgrade;
use App\Services\Bot\BotInvestmentService;
use App\Services\DepositService;
use App\Services\ReferralBonusService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Investment extends Component
{
    public $amount;
    public $bot;
    public $license;
    public $showModal = false;
    public $selectedLicense;
    public $asset = 'main';
    public $search;

    public $upgradeMode = false;
    public $availableUpgradeBots;
    public $upgradedBotId;
    public $depositBalance = 0;

    // //////////////////////////
    public $showConfirm = false;
    public $title;
    public $message = '';
    public $warning;
    public $type = 'danger';
    public $confirmText = 'Confirm';
    public $icon = '⚠️';
    public $action;

    public function mount() {
        $this->licenses = BotLicense::with('bot')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $this->depositBalance = bcadd(
            (string) auth()->user()->deposit_balance, 
            (string) auth()->user()->deposit_bonus_balance, 
            8
        ); 
    }

    public function rules() {
        return [
            'amount' => ['required','numeric']
        ];
    }

    public function confirmAction() {
        $action = $this->action;

        if (method_exists($this, $action)) {
            $this->$action();
        }

        $this->showConfirm = false;
    }

    public function openModal($licenseId) {
        $this->upgradeMode = false;
        $this->upgradedBotId = null;

        $this->selectedLicense = BotLicense::with('bot')->findOrFail($licenseId);
        $this->amount = null;
        $this->showModal = true;
    }

    public function render() {
        $licenses = BotLicense::with('bot')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        $investments = BotInvestment::where([
            'user_id' => auth()->id()
        ])->latest()
            ->paginate();

        return view('livewire.dashboard.investment', [
            'licenses' => $licenses,
            'investments' => $investments
        ]);
    }

    public function cancelConfirm() {
        $this->showConfirm = false;
    }

    public function prepareInvesment() {
        if(!$this->selectedLicense) return;

        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $this->selectedLicense->bot->min_amount,
                // 'max:' . $this->selectedLicense->bot->max_amount,
            ],
        ]);


        $this->type = 'success';
        $this->title = 'Start Investment';
        $this->message = 'Your funds will be locked for the selected duration.';
        $this->confirmText = 'Proceed';
        $this->action = 'createInvestment';

        $this->showConfirm = true;
    }

    public function prepareUpgrade() {
        $this->validate([
            'upgradedBotId' => [
                'required',
                'exists:bots,id',
                function ($attribute, $value, $fail) {
                    $selectedBot = Bot::find($value);

                    if (!$selectedBot) {
                        return;
                    }

                    if ($selectedBot->price <= $this->selectedLicense->bot->price) {
                        $fail('You can only upgrade to a higher-tier bot.');
                    }
                }
            ],
        ], [
            'upgradedBotId.required' => 'Please select a bot to upgrade.',
            'upgradedBotId.exists' => 'The selected bot is invalid.'
        ]);

        $selectedUpgradeBot = Bot::where('id', $this->upgradedBotId)->first();
        if(!$this->selectedLicense || !$selectedUpgradeBot) return;

        $this->type = 'success';
        $this->title = 'Upgrade License';
        $this->message = 'Current bot will be upgraded to selected bot.';
        $this->confirmText = 'Proceed';
        $this->action = 'upgradeLicense';

        $this->showConfirm = true;
    }

    public function createInvestment() {
        if(auth()->user()->suspended_at) {
            $this->dispatch('error', message: 'Your account has been suspended.');
            return;
        }
        if(!$this->selectedLicense) return;

        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $this->selectedLicense->bot->min_amount,
                // 'max:' . $this->selectedLicense->bot->max_amount,
            ],
        ]);

        try {
            if($this->asset === 'deposit') {
                DepositService::debitForInvestment(auth()->user(), $this->amount);
            } else {
                WalletService::debit(
                    auth()->user(), 
                    $this->amount, 
                    LedgerReference::BOT_INVESTMENT, 
                    $this->selectedLicense->id, 
                    null, 
                    LedgerAsset::MAIN
                );      
            }  

            $investment = BotInvestment::create([
                'user_id' => auth()->id(),
                'bot_id' => $this->selectedLicense->bot->id,
                'bot_license_id' => $this->selectedLicense->id,
                'amount' => $this->amount,
                'capital' => $this->amount,
                'started_at' => now(),
                'locked_until' => now()->addDays($this->selectedLicense->bot->lock_days),
                'matures_at' => now()->addDays($this->selectedLicense->bot->lock_days),
                'next_cycle_at' => now()->addHours($this->selectedLicense->bot->payout_interval_hours),
                'meta' => [
                    'daily_return_percent' => $this->selectedLicense->bot->daily_return_percent,
                    'payout_interval_hours' => $this->selectedLicense->bot->payout_interval_hours,
                ]
            ]);

            WalletService::credit(
                auth()->user(), 
                $this->amount, 
                LedgerReference::BOT_INVESTMENT, 
                $investment->id,
                'locked investment', 
                LedgerAsset::LOCKEDBALANCE
            );

            ReferralBonusService::distribute(auth()->user(), $investment);

            $this->showModal = false;
            $this->dispatch('toast', payload: [
                'message' => 'Investment deployed successfully!'
            ]);
        } catch(\Exception $e) {
            $this->addError('amount', $e->getMessage());
        }

        
    }

    public function viewInvestment($id) {
        return redirect()->route('investments.item', ['id' => $id]);
    }

    public function openUpgradeModal($licenseId) {
        $this->selectedLicense = BotLicense::with('bot')->findOrFail($licenseId);

        $this->upgradeMode = true;
        $this->availableUpgradeBots = Bot::where('price', '>', $this->selectedLicense->bot->price)
            ->whereDoesntHave('licenses', function ($q) {
                $q->where('user_id', $this->selectedLicense->user_id);
            })
            ->orderBy('price')
            ->get();
        $this->showModal = true;
    }

    public function upgradeLicense() {
        if(auth()->user()->suspended_at) {
            $this->dispatch('error', message: 'Your account has been suspended.');
            return;
        }
        $this->validate([
            'upgradedBotId' => [
                'required',
                'exists:bots,id',
                function ($attribute, $value, $fail) {
                    $selectedBot = Bot::find($value);

                    if (!$selectedBot) {
                        return;
                    }

                    if ($selectedBot->price <= $this->selectedLicense->bot->price) {
                        $fail('You can only upgrade to a higher-tier bot.');
                    }
                }
            ],
        ], [
            'upgradedBotId.required' => 'Please select a bot to upgrade.',
            'upgradedBotId.exists' => 'The selected bot is invalid.'
        ]);

        try{
            $selectedUpgradeBot = Bot::where('id', $this->upgradedBotId)->first();
            if(!$this->selectedLicense || !$selectedUpgradeBot) return;

            BotInvestmentService::upgrade($this->selectedLicense, $selectedUpgradeBot, $this->asset);

            $this->showModal = false;
            $this->upgradeMode = false;

            // $this->dispatch('toast', payload: [
            //     'message' => 'Bot upgraded successfully!'
            // ]);
            $this->dispatch('success', message: 'Bot upgraded successfully!');
        } catch(\Throwable $e) {
            $this->dispatch('error', message: $e->getMessage() ?: 'Upgrade failed. Please try again.');
        }
    }
}
