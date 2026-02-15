<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotLicense;
use App\Services\Wallet\WalletService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Bot extends Component {
    public $selectedBot;
    public $asset = 'main';
    public $showSuccess = false;
    public $createdLicenseId = null;

    protected function rules() {
        return [
            'asset' => ['required', 'in:main,deposit'],
        ];
    }

    public function mount() {
    
    }

    public function subscribeToBot() {
        $this->validate();
        if (!$this->selectedBot) {
            $this->addError('general', 'Invalid bot selected.');
            return;
        }
        sleep(2);

        try{
            $assetEnum = $this->asset === 'deposit'
                ? LedgerAsset::DEPOSIT
                : LedgerAsset::MAIN;

            WalletService::debit(
                auth()->user(), 
                $this->selectedBot->price, 
                LedgerReference::LICENSE_PURCHASE, 
                $this->selectedBot->id, 
                null, 
                $assetEnum
            );

            $license = BotLicense::create([
                'user_id' => auth()->id(),
                'bot_id' => $this->selectedBot->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($this->selectedBot->license_duration_days),
                'meta' => [
                    'price' => $this->selectedBot->price,
                    'asset_used' => $this->asset,
                ]
            ]);

            $this->createdLicenseId = $license->id;
            $this->showSuccess = true;
            
        } catch(\Exception $e) {
            $this->addError('general', $e->getMessage());
        }

        

        
    }

    public function selectBot($id) {
        $this->selectedBot = \App\Models\Bot::where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    public function resetBot() {
        $this->selectedBot = null;
    }

    public function render() {
        $bots = \App\Models\Bot::where('is_active', true)->get();
        $licenses = BotLicense::where('user_id', auth()->id())->latest()->get();
        return view('livewire.dashboard.bot', [
            'bots' => $bots,
            'licenses' => $licenses
        ]);
    }
}
