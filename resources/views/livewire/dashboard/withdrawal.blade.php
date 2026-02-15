<div x-data="{deletingId: @entangle('deletingId'), selectedWallet: @entangle('networks')}">
    <!-- Sidebar start -->
    <div class="col-span-12 xl:col-span-4">
        <div class="card border-0 rounded-2xl">
            <div class="card-header">
                <div class="flex items-center flex-wrap gap-2 justify-between">
                    <h6 class="font-bold text-lg mb-0">Withdraw Money</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="relative z-[1] py-8 text-center px-4">
                        <img src="{{ asset('assets/images/home-eleven/bg/bg-orange-gradient.png') }}" alt=""
                            class="absolute top-0 start-0 w-full h-full -z-[1]">
                        <h3 class="text-white">${{ number_format(auth()->user()->balance, 2) }}</h3>
                        <span class="text-white">Main Balance</span>
                    </div>
                </div>


                <div class="py-4 px-6">

                    <form wire:submit.prevent="makeWithdrawal">
                        <div class="flex flex-col gap-2">
                            <a 
                                data-modal-target="default-modal" 
                                data-modal-toggle="default-modal"
                                href="javascript:void(0)"
                                class="flex items-center justify-between gap-2 p-4 border border-neutral-200 dark:border-neutral-600 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-600"
                            >

                                <span class="flex items-center gap-4">
                                    @if (!empty($selectedWallet))
                                        <img src="{{ asset('assets/images/currency/' . strtolower($selectedWallet['code']) . '.png') }}"
                                            alt="" class="shrink-0 me-3 overflow-hidden">
                                    @endif

                                    <span
                                        class="text-base mb-0 font-medium text-neutral-600 dark:text-neutral-200 block">
                                        {{ $selectedWallet['name'] ?? 'Select Currency' }}
                                    </span>
                                </span>
                                <span class="text-secondary-light text-base"><i
                                        class="ri-arrow-right-s-line"></i></span>

                            </a>
                            
                            @if( !empty($networks) )
                                <div class="">
                                    <label for="Amount"
                                        class="block font-normal text-neutral-600 dark:text-white mb-0.5 text-xs">
                                        Network
                                    </label>
                                    <select wire:model="network"
                                        class="form-select bg-white dark:bg-neutral-700 form-select-sm w-auto">
                                        @forelse($networks as $net)
                                            <option value="{{ $net['id'] }}">{{ $net['name'] }}</option>

                                        @empty
                                            <option>...</option>
                                        @endforelse
                                    </select>
                                </div>
                            @endif
                            @error('selectedWallet')
                                <p class="text-sm text-danger-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="">
                            <span x-text="() => console.log(selectedWallet)"></span>
                            <label for="Amount" class="block font-semibold text-neutral-600 dark:text-white mb-2">
                                Amount
                            </label>
                            <div class="flex gap-4">
                                <input wire:model="amount" type="text" id="Amount" class="form-control form-control-lg"
                                    placeholder="Ex: $200">
                            </div>
                            @error('amount')
                                <p class="text-sm text-danger-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block font-semibold text-neutral-600 dark:text-white mb-2">
                                Recipient address
                            </label>
                            <input wire:model="address" type="text" class="form-control form-control-lg" id="address"
                                placeholder="Enter address">

                            @error('address')
                                <p class="text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary-600 flex-shrink-0 flex items-center gap-2 px-8"
                                type="submit">
                                <div wire:target="makeWithdrawal" wire:loading.remove>
                                    Withdraw
                                    <i class="ri-send-plane-fill"></i>
                                </div>

                                <x-btn-loader wire:target="makeWithdrawal" wire:loading />

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Sidebar end -->

    <!-- Withdrawal History -->
    <div class="card border-0 rounded-2xl mt-6">
        <div class="card-header">
            <div class="flex items-center flex-wrap gap-2 justify-between">
                <h6 class="font-bold text-lg mb-0">Withdrawal History</h6>

            </div>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <div class="w-[100px]">
                    @if(!$withdrawals->count())
                        <p class="w-full">No Withdrawal history at the moment.</p>
                    @else
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Reference</th>
                                    <th scope="col" class="text-center">Amount</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdrawals as $withdrawal)
                                    <tr class="text-xs md:text-sm">
                                        <td class="text-center">{{ $withdrawal->reference }}</td>
                                        <td class="text-center font-semibold">${{ number_format($withdrawal->amount, 2) }}</td>
                                        <td class="text-center">{{ $withdrawal->created_at->format('d M Y - h:i A') }}</td>
                                        <td class="text-center">
                                            @php

                                            @endphp
                                            <span
                                                class="bg-{{ $withdrawal->status->color() }}-focus text-{{ $withdrawal->status->color() }}-main dark:text-{{ $withdrawal->status->color() }}-500 px-6 py-1 rounded-[50rem] font-medium text-sm capitalize">
                                                {{ $withdrawal->status->value }}
                                            </span>
                                        </td>
                                        <td class="flex gap-2">
                                            <a href="{{ route('withdrawal.page', ['withdrawal' => $withdrawal->id]) }}"
                                                class="w-8 h-8 flex justify-center items-center bg-success-600/10 hover:bg-success-600 hover:text-white duration-300 active:scale-75 bg-hover-success-600 text-base rounded-full">
                                                <i
                                                    class="ri-arrow-right-line text-success-600 text-hover-white hover:text-white"></i>
                                            </a>
                                            <button @click="deletingId = {{ $withdrawal->id }}" command="show-modal" commandfor="dialog" type="button"
                                                class="w-8 h-8 d-inline-flex justify-center items-center bg-danger-600/10 hover:bg-danger-600 hover:text-white duration-300 active:scale-75 text-danger-600 bg-hover-danger-600 text-hover-white text-base rounded-full">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>

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

    <!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
    <el-dialog>
        <dialog id="dialog" aria-labelledby="dialog-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    data-slot="icon" aria-hidden="true" class="size-6 text-red-600">
                                    <path
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 id="dialog-title" class="text-base font-semibold" style="color: black">
                                    Delete Withdrawal <span x-text="'d ' + deletingId + ' d'"></span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm" style="color: #292929">
                                        Are you sure you want to delete this withdrawal?
                                        The withdrawal will be permanently removed. This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button commandfor="dialog" wire:click="deleteWithdrawal" type="button"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">
                            <span wire:loading.remove wire:target="deleteWithdrawal">Delete</span>
                            <x-btn-loader wire:loading wire:target="deleteWithdrawal" />
                        </button>
                        <button type="button" command="close" commandfor="dialog"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>


</div>

@push('scripts')

    <script>
        if (window.Livewire) {
            console.log('ddd')
            Livewire.on('delete-deposit', ({ payload }) => {
                Swal.fire({
                    title: 'Delete Withdrawal',
                    text: 'This action cannot be reversed',
                    icon: 'danger',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(result);
                        // Livewire.emit(event.detail.method, ...(event.detail.params || []));
                    }
                });
            });
        }
    </script>

@endpush