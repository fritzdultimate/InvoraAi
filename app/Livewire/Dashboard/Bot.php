<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotLicense;
use App\Models\BotLicenseUpgrade;
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

    protected function rules() {
        return [
            'asset' => ['required', 'in:main,deposit'],
        ];
    }

    public function mount() {
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
        sleep(10);

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

                    // if user tries to buy same or lower bot
                    if ($this->selectedBot->price <= $currentBot->price) {
                        $this->dispatch('error', message: 'You already have an active bot. Upgrade to a higher plan.');
                        return;
                    }

                    if ($this->selectedBot->price > $currentBot->price) {
                        WalletService::debit(
                            auth()->user(), 
                            $this->selectedBot->price, 
                            LedgerReference::LICENSE_UPGRADE, 
                            $this->selectedBot->id, 
                            null, 
                            $assetEnum
                        );

                        BotLicenseUpgrade::create([
                            'bot_license_id' => $activeLicense->id,
                            'user_id' => auth()->id(),
                            'from_bot_id' => $activeLicense->bot_id,
                            'to_bot_id' => $this->selectedBot->id,
                            'price_paid' => $this->selectedBot->price,
                            'status' => 'upgraded'
                        ]);

                        $activeLicense->update([
                            'bot_id' => $this->selectedBot->id,
                        ]);

                        $this->dispatch('success', message: 'Your trading bot has been upgraded to ' . $this->selectedBot->name);
                        return;
                    }
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
            $this->addError('general', $e->getMessage());
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
        $bots = \App\Models\Bot::where('is_active', true)->get();
        $licenses = BotLicense::where('user_id', auth()->id())
            ->latest()
            ->paginate($this->perPage);
        return view('livewire.dashboard.bot', [
            'bots' => $bots,
            'licenses' => $licenses
        ]);
    }
}
