<div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-6">
        <div class="card border-0">
            <div class="card-body p-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-4">
                        <div class="trail-bg h-full text-center flex flex-col justify-between items-center p-4 rounded-lg bg-cover bg-no-repeat bg-center"
                            style="background-image: url('assets/images/home-nine/trail-bg.png');">
                            <h6 class="text-white text-xl">Purchase Licence</h6>
                            <div class="">
                                <p class="text-white mb-2">
                                    Let's start trading for you.
                                </p>
                                <a href="{{ route('bot') }}"
                                    class="btn bg-white py-2 rounded-[50rem] w-full bg-gradient-to-r from-[#CBFFF9]! to-[#FFEEB1] text-sm justify-center dark:text-neutral-900 hover:scale-[1.06]">
                                    Get License
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-8">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                            <div class="col-span-12 sm:col-span-6">
                                <div class="rounded-lg h-full text-center p-5 bg-purple-light">
                                    <span
                                        class="w-[44px] h-[44px] rounded-lg inline-flex justify-center items-center text-xl mb-3 bg-purple-200 dark:bg-purple-600/20 border border-purple-400 text-purple-600">
                                        <i class="ri-wallet-3-fill"></i>
                                    </span>
                                    <span class="text-neutral-700 block">Active Capital</span>
                                    <h6 class="mb-0 mt-1">$0.00</h6>
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6">
                                <div class="rounded-lg h-full text-center p-5 bg-success-100 dark:bg-success-600/10">
                                    <span
                                        class="w-[44px] h-[44px] rounded-lg inline-flex justify-center items-center text-xl mb-3 bg-success-200 dark:bg-success-600/20 border border-success-400 text-success-600">
                                        <i class="ri-line-chart-fill"></i>
                                    </span>
                                    <span class="text-neutral-700 block">Trades Executed</span>
                                    <h6 class="mb-0 mt-1">0</h6>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="rounded-lg h-full text-center p-5 bg-info-focus">
                                    <span
                                        class="w-[44px] h-[44px] rounded-lg inline-flex justify-center items-center text-xl mb-3 bg-info-200 dark:bg-info-600/20 border border-info-400 text-info-600">
                                        <i class="ri-robot-2-fill"></i>
                                    </span>
                                    <span class="text-neutral-700 block">Available Bots</span>
                                    <h6 class="mb-0 mt-1">3</h6>
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="rounded-lg h-full text-center p-5 bg-danger-100 dark:bg-danger-600/10">
                                    <span
                                        class="w-[44px] h-[44px] rounded-lg inline-flex justify-center items-center text-xl mb-3 bg-danger-200 dark:bg-danger-600/20 border border-danger-400 text-danger-600">
                                        <i class="ri-flashlight-fill"></i>
                                    </span>
                                    <span class="text-neutral-700 block">Bot Status</span>
                                    <h6 class="mb-0 mt-1 text-warning-600">Not Active</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-dashboard.deposit-withdrawal-chart />

    <x-dashboard.recent-transaction :transactions="$transactions" />
</div>