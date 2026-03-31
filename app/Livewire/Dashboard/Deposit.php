<?php

namespace App\Livewire\Dashboard;

use App\Enums\DepositStatus;
use App\Services\DepositService;
use App\Services\NowPaymentsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class  Deposit extends Component {
    use WithPagination;
    public $nowPaymentWallets;
    public $amount;
    public $narration;
    public $network;
    public $selectedWallet;
    public $depositId;
    public $invoice;
    public $showDepositModal = false;
    public $deposit;
    public $deletingId;
    public $perPage = 0;

    // //////////////////////////
    public $showConfirm = false;
    public $title;
    public $text;
    public $warning;
    public $type = 'danger';
    public $confirmText = 'Confirm';
    public $icon = '⚠️';
    public $action;

    protected $rules = [
        'amount' => 'required|numeric|min:50',
        'selectedWallet' => 'required',
        'narration' => 'nullable|string|max:120',
    ];

    protected $messages = [
        'amount.min' => "Amount must be at least $50.",
        'selectedWallet.required' => "Please choose a currency."
    ];

    /**
     * Mount the component.
     */

    public function mount(NowPaymentsService $np) {
        $this->nowPaymentWallets = $np->getCurrencies();
    }

    public function selectWallet($wallet) {
        $this->selectedWallet = $wallet;

        if (!empty($wallet['networks'])) {
            $this->network = $wallet['networks'][0]['raw'];
        }
    }

    public function makeDeposit() {
        $deposit = DB::transaction(function () {
            $this->amount = str_replace(',', '', $this->amount);
            $this->validate();
            $this->network ??= strtolower($this->selectedWallet['currency']);

            $networks = collect($this->selectedWallet['networks'] ?? [])
                ->pluck('raw')
                ->toArray();

            if (! in_array($this->network, $networks, true)) {
                throw ValidationException::withMessages([
                    'selectedWallet' => 'Invalid network selected for this wallet.',
                ]);
            }

            $deposit = \App\Models\Deposit::create([
                'user_id' => auth()->id(),
                'currency' => strtolower($this->network),
                'amount' => $this->amount,
                'status' => DepositStatus::WAITING,
                'narration' => $this->narration,
                'reference' => generate_deposit_reference()
            ]);

            $invoice = NowPaymentsService::createInvoice($deposit);
            $deposit->nowpayments_invoice_id = $invoice['payment_id'] ?? null;
            $deposit->meta = $invoice;
            $deposit->address = $invoice['pay_address'];
            $deposit->save();


            $this->deposit = $deposit;
            $this->invoice = $invoice;

            session()->put('pay_address', $invoice['pay_address']);

            $this->depositId = $deposit->id;
            $this->dispatch('address-created', invoice: $this->invoice, depositId: $this->depositId);
            $this->dispatch('toast', payload: [
                'message' => 'Your deposit wallet is on the way! Processing time is subject to network conditions.',
                'timeout' => 10000,
            ]);
            $this->showDepositModal = true;

            return $deposit;
        });

        return redirect()->route('deposit.page', ['deposit' => $deposit->id]);
    }

    public function cancelConfirm() {
        $this->showConfirm = false;
    }

    public function prepareDeposit() {
        $this->amount = str_replace(',', '', $this->amount);
        $this->validate();

        $this->showConfirm = true;
        $this->type = 'warning';
        $this->title = 'Confirm Deposit';
        $this->text = 'Make sure you are sending the correct asset.';
        $this->warning = 'Sending wrong network/asset will result in permanent loss.';
        $this->confirmText = 'I Confirm';
        $this->action = 'makeDeposit';
    }

    public function confirmAction() {
        $action = $this->action;

        if (method_exists($this, $action)) {
            $this->$action();
        }

        $this->showConfirm = false;
    }

    public function resumeDeposit($id) {
        $deposit = \App\Models\Deposit::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', DepositStatus::WAITING)
            ->firstOrFail();

        if ($deposit->expires_at->isPast()) {
            $deposit->update(['status' => DepositStatus::EXPIRED]);
            return;
        }

        $this->invoice = $deposit->meta;
        $this->network = $deposit->currency;
        $this->depositId = $deposit->id;
        $this->showDepositModal = true;
    }

    public function deleteDeposit() {
        if(!$this->deletingId) return;
        \App\Models\Deposit::where([
            'user_id' => auth()->id(),
            'id' => $this->deletingId
        ])->delete();

        $this->deletingId = null;
        return redirect()->route('deposit');
    }

    public function handleDelete($id) {
        $this->deletingId = $id;
    }


    public function render(): \Illuminate\View\View {
        $deposits = \App\Models\Deposit::where('user_id', auth()->id())
            ->latest()
            ->latest()
            ->paginate($this->perPage);
            
        return view('livewire.dashboard.deposit', [
            'deposits' => $deposits
        ]);
    }
}