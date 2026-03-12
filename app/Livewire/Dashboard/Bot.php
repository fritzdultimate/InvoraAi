<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotLicense;
use App\Models\BotLicenseUpgrade;
use App\Models\BotProfitCycle;
use App\Services\Bot\BotInvestmentService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Bot extends Component {
    public $selectedBot;
    public $asset = 'main';
    public $showSuccess = false;
    public $createdLicenseId = null;
    public $perPage = 10;
    public $activeLicense;
    public $upgrading = false;
    public $totalPlatformProfits;

    protected function rules() {
        return [
            'asset' => ['required', 'in:main,deposit'],
        ];
    }

    public function mount() {
        $this->totalPlatformProfits = BotProfitCycle::sum('profit_amount');
        $this->activeLicense = BotLicense::where('user_id', auth()->id())
            ->where('expires_at', '>', now())
            ->lockForUpdate()
            ->first();
    }

    public function subscribeToBot() {
        $this->validate();
        if (!$this->selectedBot) {
            $this->addError('general', 'Invalid bot selected.');
            return;
        }
        // sleep(10);

        try{
            $assetEnum = $this->asset === 'deposit'
                ? LedgerAsset::DEPOSIT
                : LedgerAsset::MAIN;


            DB::transaction(function() use($assetEnum) {

                $activeLicense = BotLicense::where('user_id', auth()->id())
                    ->where('expires_at', '>', now())
                    ->lockForUpdate()
                    ->first();


                if ($activeLicense) {

                    $currentBot = $activeLicense->bot;
                    if ($this->selectedBot->price <= $currentBot->price) {
                        $this->dispatch('error', message: 'You already have an active bot. Upgrade to a higher plan.');
                        return;
                    }
                    
                    BotInvestmentService::upgrade($this->activeLicense, $this->selectedBot, $this->asset);
                    
                    $this->dispatch('success', message: 'Bot upgraded successfully!');
                    return;
                }

                WalletService::debit(
                    auth()->user(), 
                    $this->selectedBot->price, 
                    LedgerReference::LICENSE_PURCHASE, 
                    $this->selectedBot->id, 
                    null, 
                    $assetEnum
                );

                $license = BotLicense::updateOrCreate(
                    ['user_id' => auth()->id()],
                    [
                        'bot_id' => $this->selectedBot->id,
                        'starts_at' => now(),
                        'expires_at' => now()->addDays($this->selectedBot->license_duration_days),
                        'meta' => [
                            'price' => $this->selectedBot->price,
                            'asset_used' => $this->asset,
                        ]
                    ]
                );

                $this->createdLicenseId = $license->id;
                $this->showSuccess = true;

            });
            
        } catch(\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
            // $this->addError('general', $e->getMessage());
        }

        

        
    }

    public function selectBot($id) {
        $this->selectedBot = \App\Models\Bot::where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    public function prepareUpgrade($id) {
        $this->selectedBot = \App\Models\Bot::where('id', $id)
            ->where('is_active', true)
            ->first();

        $this->upgrading = true;
    }

    public function resetBot() {
        $this->selectedBot = null;
    }

    public function render() {
        $bots = \App\Models\Bot::withSum('profitCycles as total_profit', 'profit_amount')->where('is_active', true)->get();

        $licenses = BotLicense::where('user_id', auth()->id())
            ->latest()
            ->paginate($this->perPage);
        return view('livewire.dashboard.bot', [
            'bots' => $bots,
            'licenses' => $licenses
        ]);
    }
}
