<?php

namespace App\Livewire\Dashboard;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class DepositPage extends Component {
    use WithFileUploads;
    public $deposit; // Injected deposit
    public $network;
    public $invoice;
    public $showModal = true;
    public $remainingSeconds;

    // Receipt upload
    public $receipt;
    public $showReceiptModal = false;

    public function mount($deposit) {
        $this->deposit = Deposit::findOrFail($deposit);
        $this->invoice = $this->deposit->meta ?? null;
        $this->network = $this->deposit->currency ?? 'BTC';

        $expiresAt = $this->deposit->created_at->addMinutes(20);

        $this->remainingSeconds = floor(now()->diffInSeconds($expiresAt, false));
        // dd($this->remainingSeconds);
    }

    public function checkDepositStatus() {
        $deposit = $this->deposit->fresh();

        if ($deposit->status === DepositStatus::FINISHED) {
            $this->dispatch('toast', [
                'message' => 'Deposit confirmed!',
                'type' => 'success'
            ]);
            $this->showModal = false;
        } else {
            $this->dispatch('toast', [
                'message' => 'Deposit not yet received.',
                'type' => 'warning'
            ]);
        }
    }

    public function openReceiptModal() {
        $this->showReceiptModal = true;
    }

    public function closeReceiptModal() {
        $this->showReceiptModal = false;
        $this->receipt = null;
    }

    public function uploadReceipt() {
        $this->validate([
            'receipt' => 'required|image|max:5120', // 5MB max
        ], [
            'receipt.required' => 'Please select a receipt image to upload.',
            'receipt.image' => 'The file must be an image (jpg, jpeg, png).',
            'receipt.max' => 'The image must not exceed 5MB.',
        ]);

        try {
            DB::transaction(function() {
                 // Store receipt
                $path = $this->receipt->store('receipts', 'public');
                
                // Update deposit with receipt info
                $this->deposit->update([
                    'receipt_path' => $path,
                    'receipt_uploaded_at' => now(),
                    'meta' => array_merge($this->deposit->meta ?? [], [
                        'receipt_submitted' => true,
                        'receipt_submitted_at' => now()->toIso8601String(),
                    ])
                ]);

                $this->dispatch('success', message: 'Receipt uploaded successfully! Admin will review it shortly.');

                $this->closeReceiptModal();
                $this->deposit = $this->deposit->fresh();
            });

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Upload failed. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.deposit-page');
    }
}
