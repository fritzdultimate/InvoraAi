<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\WalletLedger;
use App\Services\Wallet\BalanceConversionService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

// all balances in one place, and converting profit or referral bonus into the main balance
#[Layout('components.layouts.app')]
class Wallet extends Component
{
    use WithPagination;

    // shown on the page only; every conversion re-reads the real balances on the server
    #[Locked]
    public array $balances = [];

    public function convert($from, $amount)
    {
        $key = 'wallet-convert:' . auth()->id();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->dispatch('error', message: 'Too many conversions. Please wait a minute.');
            return false;
        }
        RateLimiter::hit($key, 60);

        try {
            $converted = BalanceConversionService::convert(auth()->user(), (string) $from, $this->toNumber($amount));
        } catch (\DomainException $e) {
            $this->dispatch('error', message: $e->getMessage());
            return false;
        }

        $label = $from === 'profit' ? 'profit' : 'referral bonus';
        $this->dispatch('success', message: '$' . number_format($converted, 2) . " {$label} moved to your main balance");
        $this->resetPage();

        return true;
    }

    private function toNumber($value): float
    {
        $value = str_replace([',', ' ', '$'], '', (string) $value);

        return is_numeric($value) ? (float) $value : 0.0;
    }

    public function render()
    {
        $user = auth()->user()->fresh();

        $this->balances = [
            'main' => round((float) $user->main_balance, 2),
            'profit' => round((float) $user->profit_balance, 2),
            'referral' => round((float) $user->referral_balance, 2),
        ];

        $conversions = WalletLedger::where('user_id', $user->id)
            ->where('asset', LedgerAsset::MAIN->value)
            ->whereIn('reference_type', [LedgerReference::PROFITTRANSFER->value, LedgerReference::REFERRALBONUSTRANSFER->value])
            ->where('credit', '>', 0);

        $totals = (clone $conversions)->toBase()->selectRaw('reference_type, sum(credit) as total, count(*) as times')->groupBy('reference_type')->get()->keyBy('reference_type');

        return view('livewire.dashboard.wallet', [
            'user' => $user,
            'minimum' => BalanceConversionService::minimum(),
            'history' => (clone $conversions)->latest('id')->paginate(10),
            'fromProfit' => (float) ($totals[LedgerReference::PROFITTRANSFER->value]->total ?? 0),
            'fromReferral' => (float) ($totals[LedgerReference::REFERRALBONUSTRANSFER->value]->total ?? 0),
            'times' => (int) $totals->sum('times'),
        ]);
    }
}
