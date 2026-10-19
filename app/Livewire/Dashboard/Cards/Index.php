<?php

namespace App\Livewire\Dashboard\Cards;

use App\Enums\CardForm;
use App\Enums\LedgerAsset;
use App\Models\CardOrder;
use App\Models\CardProduct;
use App\Services\Card\CardOrderException;
use App\Services\Card\CardOrderService;
use App\Services\Card\CardProgram;
use App\Services\Wallet\WalletService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public string $network = 'all';

    // order sheet
    public ?int $productId = null;
    public int $step = 1;
    public string $name_on_card = '';
    public string $phone = '';
    public string $address_line1 = '';
    public string $address_line2 = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';
    public string $balance = '';
    public bool $agree = false;

    public function mount(): void
    {
        $this->name_on_card = strtoupper(substr(auth()->user()->name ?? '', 0, 26));
        $this->country = auth()->user()->kyc?->country ?? '';
        $this->balance = CardProgram::allowedBalances()[0]->value ?? '';
    }

    #[Computed]
    public function product(): ?CardProduct
    {
        return $this->productId ? CardProduct::find($this->productId) : null;
    }

    // steps shown in the sheet; virtual cards skip delivery
    #[Computed]
    public function steps(): array
    {
        $steps = ['details' => 'Card'];

        if ($this->product?->form === CardForm::PHYSICAL) {
            $steps['delivery'] = 'Delivery';
        }

        return $steps + ['payment' => 'Payment', 'review' => 'Review'];
    }

    public function stepKey(): string
    {
        return array_keys($this->steps)[$this->step - 1] ?? 'details';
    }

    public function choose(int $productId, CardOrderService $service): void
    {
        $product = CardProduct::available()->find($productId);

        if (! $product) {
            $this->dispatch('error', message: 'This card is not available.');
            return;
        }

        if ($reason = $service->blockReason(auth()->user(), $product)) {
            $this->dispatch('error', message: $reason);
            return;
        }

        $this->resetValidation();
        $this->productId = $product->id;
        $this->step = 1;
        $this->agree = false;
        unset($this->product, $this->steps);
    }

    public function close(): void
    {
        $this->productId = null;
        $this->step = 1;
    }

    public function next(): void
    {
        $this->validate($this->rulesFor($this->stepKey()));

        if ($this->step < count($this->steps)) {
            $this->step++;
        }
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function place(CardOrderService $service)
    {
        $product = $this->product;

        if (! $product) {
            return;
        }

        $rules = $this->rulesFor('details') + $this->rulesFor('payment') + ['agree' => ['accepted']];

        if ($product->form === CardForm::PHYSICAL) {
            $rules += $this->rulesFor('delivery');
        }

        $this->validate($rules, ['agree.accepted' => 'Please confirm the pre-order terms.']);

        try {
            $order = $service->placeOrder(auth()->user(), $product, LedgerAsset::from($this->balance), [
                'name_on_card' => $this->name_on_card,
                'phone' => $this->phone,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
            ]);
        } catch (CardOrderException $e) {
            $this->dispatch('error', message: $e->getMessage());
            return;
        }

        session()->flash('success', 'Pre-order placed. We will email you as your card moves along.');

        return $this->redirectRoute('cards.order', $order);
    }

    protected function rulesFor(string $step): array
    {
        return match ($step) {
            'details' => ['name_on_card' => ['required', 'string', 'min:3', 'max:26', 'regex:/^[A-Za-z .\'-]+$/']],
            'delivery' => [
                'phone' => ['required', 'string', 'max:30'],
                'address_line1' => ['required', 'string', 'max:255'],
                'address_line2' => ['nullable', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'state' => ['required', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'country' => ['required', 'string', 'max:100'],
            ],
            'payment' => ['balance' => ['required', 'in:'.implode(',', array_map(fn ($a) => $a->value, CardProgram::allowedBalances()))]],
            default => [],
        };
    }

    protected function validationAttributes(): array
    {
        return ['name_on_card' => 'name on card', 'address_line1' => 'address'];
    }

    public function render(CardOrderService $service)
    {
        $user = auth()->user();

        $products = CardProduct::available()
            ->when($this->network !== 'all', fn ($q) => $q->where('network', $this->network))
            ->get();

        return view('livewire.dashboard.cards.index', [
            'products' => $products,
            'orders' => CardOrder::where('user_id', $user->id)->latest()->get(),
            'blocked' => $service->blockReason($user),
            'notice' => CardProgram::notice(),
            'balances' => collect(CardProgram::allowedBalances())->mapWithKeys(fn ($asset) => [
                $asset->value => ['label' => $asset->label(), 'amount' => (float) WalletService::getBalance($user, $asset->value)],
            ]),
        ]);
    }
}
