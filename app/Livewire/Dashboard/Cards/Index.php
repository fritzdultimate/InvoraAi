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
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Opening the sheet, switching tabs and moving between steps all happen in the
 * browser (Alpine). Only place() talks to the server, and it re-checks everything.
 */
#[Layout('components.layouts.app')]
class Index extends Component
{
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
        abort_unless(CardProgram::isEnabled(), 404);

        $this->name_on_card = strtoupper(substr(auth()->user()->name ?? '', 0, 26));
        $this->country = auth()->user()->kyc?->country ?? '';
        $this->balance = CardProgram::allowedBalances()[0]->value ?? '';
    }

    public function place(int $productId, string $form, CardOrderService $service)
    {
        $product = CardProduct::available()->find($productId);
        $type = CardForm::tryFrom($form);

        if (! $product || ! $type || ! $product->offers($type)) {
            $this->dispatch('error', message: 'This card is not available.');
            return;
        }

        try {
            $this->validate($this->rules($type), ['agree.accepted' => 'Please confirm the pre-order terms.']);
        } catch (ValidationException $e) {
            // send the errors to the sheet so it can jump back to the right step
            $this->dispatch('ic-invalid', errors: collect($e->errors())->map(fn ($m) => $m[0])->all());
            return;
        }

        try {
            $order = $service->placeOrder(auth()->user(), $product, LedgerAsset::from($this->balance), [
                'form' => $type->value,
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

    protected function rules(CardForm $type): array
    {
        $rules = [
            'name_on_card' => ['required', 'string', 'min:3', 'max:26', 'regex:/^[A-Za-z .\'-]+$/'],
            'balance' => ['required', 'in:'.implode(',', array_map(fn ($a) => $a->value, CardProgram::allowedBalances()))],
            'agree' => ['accepted'],
        ];

        if ($type === CardForm::PHYSICAL) {
            $rules += [
                'phone' => ['required', 'string', 'max:30'],
                'address_line1' => ['required', 'string', 'max:255'],
                'address_line2' => ['nullable', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'state' => ['required', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'country' => ['required', 'string', 'max:100'],
            ];
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        return ['name_on_card' => 'name on card', 'address_line1' => 'address'];
    }

    public function render(CardOrderService $service)
    {
        $user = auth()->user();
        $products = CardProduct::available()->get();

        return view('livewire.dashboard.cards.index', [
            'products' => $products,
            // why each card can't be ordered right now (null = it can)
            'reasons' => $products->mapWithKeys(fn ($p) => [$p->id => $service->blockReason($user, $p)]),
            'orders' => CardOrder::where('user_id', $user->id)->latest()->get(),
            'blocked' => $service->blockReason($user),
            'notice' => CardProgram::notice(),
            'balances' => collect(CardProgram::allowedBalances())->mapWithKeys(fn ($asset) => [
                $asset->value => ['label' => $asset->label(), 'amount' => (float) WalletService::getBalance($user, $asset->value)],
            ]),
        ]);
    }
}
