<?php

namespace App\Livewire\Dashboard\Cards;

use App\Enums\CardOrderStatus;
use App\Models\CardOrder;
use App\Services\Card\CardOrderException;
use App\Services\Card\CardOrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public CardOrder $order;

    public function mount(CardOrder $order): void
    {
        abort_unless($order->user_id === auth()->id(), 404);

        $this->order = $order;
    }

    public function cancel(CardOrderService $service): void
    {
        try {
            $this->order = $service->cancelByUser($this->order, auth()->user());
            session()->flash('success', 'Pre-order cancelled and the fee returned to your balance.');
        } catch (CardOrderException $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function render(CardOrderService $service)
    {
        $this->order->refresh();

        return view('livewire.dashboard.cards.show', [
            'journey' => $this->order->status->isClosed()
                ? [CardOrderStatus::PENDING, $this->order->status]
                : CardOrderStatus::journey($this->order->form),
            'canCancel' => $service->canUserCancel($this->order),
        ]);
    }
}
