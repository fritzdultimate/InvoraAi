<div class="invora-container" x-data="{deletingId: @entangle('deletingId'), selectedWallet: @entangle('networks')}">
    <div class="invora-deposit-page">

        

        <div class="invora-balance-card">

            <div class="invora-balance-top">
                <div>
                    <div class="invora-balance-title">Withdrawable Balance</div>

                    <div class="invora-balance-amount">
                        ${{ number_format(auth()->user()->main_balance, 2) }}
                    </div>

                    <div class="invora-balance-meta">
                        Funds ready for withdrawal
                    </div>
                </div>
            </div>

        </div>


        <!-- DEPOSIT FORM -->
        <div class="invora-carda invora-deposit-card">

            <div class="invora-card-header">
                <h3>Secure Withdrawal</h3>
                <!-- <span style="font-size: 13px; color: var(--secondary)" class="text-gray-600 text-secondary">Secure crypto funding</span> -->
            </div>

            <form wire:submit.prevent="makeWithdrawal" class="invora-form-pro mt-4">

                <!-- WALLET -->
                <div class="invora-field">
                    <label>Currency</label>

                    <a 
                        class="invora-select-box" 
                        data-modal-toggle="default-modal" 
                        data-modal-target="default-modal" 
                        
                        data-modal-target="default-modal" 
                        data-modal-toggle="default-modal" 
                        href="javascript:void(0)"
                    >
                        @if ($selectedWallet)
                            <div class="invora-select-content">
                                <img src="{{ asset('assets/images/currency/' . strtolower($selectedWallet['code']) . '.png') }}">
                                <span>{{ $selectedWallet['name'] }}</span>
                            </div>
                        @else
                            <span class="invora-placeholder">Select wallet</span>
                        @endif

                        <i class="ri-arrow-down-s-line"></i>
                    </a>

                    @error('selectedWallet')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NETWORK -->
                @if(!empty($networks))
                    <div class="invora-field">
                        <label>Network</label>

                        <div class="invora-select-wrapper">
                            <select wire:model="network">
                                @foreach($networks as $network)
                                    <option value="{{ $network['id'] }}">{{ $network['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <!-- AMOUNT -->
                <div class="invora-field">
                    <label>Amount</label>

                    <div class="invora-input-pro">
                        <span class="prefix">$</span>
                        <input 
                            wire:model.defer="amount" 
                            type="text" 
                            placeholder="0.00"
                            inputmode="decimal"
                            id="amountInput"
                            class="deposit-amount"
                        >
                    </div>

                    <div class="invora-hint">
                        Minimum deposit: $50
                    </div>

                    @error('amount')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div class="invora-field">
                    <label>Recipient Address</label>

                    <div class="invora-input-pro">
                        <input 
                            wire:model.defer="address" 
                            type="text" 
                            placeholder="0x...., etc."
                        >
                    </div>

                    @error('address')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-center">
                    @error('general')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- BUTTON -->
                <button class="invora-btn-pro">

                    <span wire:loading.remove wire:target="makeWithdrawal">
                        Withdraw →
                    </span>

                    <span wire:loading wire:target="makeWithdrawal">
                        Processing...
                    </span>

                </button>

            </form>

        </div>


    </div>

    <!-- Withdrawal History -->
    <x-dashboard.transaction-history :type="'withdrawal'" :deposits="$withdrawals" />

    <!-- Modal Select Currecny Start -->
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between px-6 py-3 border-b rounded-t dark:border-gray-600">
                    <h5 class="modal-title text-xl" id="exampleModalEditLabel">Select Wallet</h5>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="flex flex-col gap-2 p-6">
                    @forelse ($currencies as $currency)
                        <a href="javascript:void(0)" wire:click="selectWallet({{  $currency->id }})" data-modal-hide="default-modal"
                            class="flex items-center justify-between gap-2 p-4 border border-neutral-200 dark:border-neutral-600 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-600">
                            <span class="flex items-center gap-4">
                                <img src="{{ asset('assets/images/currency/' . strtolower($currency->code) . '.png') }}"
                                    alt="" class="shrink-0 me-3 overflow-hidden">
                                <span class="text-base mb-0 font-medium text-neutral-600 dark:text-neutral-200 block">
                                    {{ $currency->name }}
                                </span>
                            </span>
                            <span class="text-secondary-light text-base"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                    @empty
                        <p>No wallet available now.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- Modal Select Currecny End -->


</div>
