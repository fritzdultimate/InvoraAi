<div class="">

    <!-- 🔔 Button -->
    <button 
        data-dropdown-toggle="dropdownNotification"
        wire:click="toggleDropdown"
        class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center"
        type="button">
        <iconify-icon icon="iconoir:bell" class="text-neutral-900 dark:text-white text-xl"></iconify-icon>

        <!-- Unseen indicator -->
        @if($unseenCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                {{ $unseenCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div  
        wire:ignore
        id="dropdownNotification"
        class="z-10 hidden bg-white dark:bg-neutral-700 rounded-2xl overflow-hidden shadow-lg max-w-[394px] w-full"
    >

        <!-- Header -->
        <div
            class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 m-4 flex items-center justify-between gap-2">
            <h6 class="text-lg text-neutral-900 font-semibold mb-0">Notification</h6>
            <span
                class="w-10 h-10 bg-white dark:bg-neutral-600 text-primary-600 dark:text-white font-bold flex justify-center items-center rounded-full">
                {{ $notifications->count() }}
            </span>
        </div>

        <!-- List -->
        <div class="scroll-sm !border-t-0">
            <div class="max-h-[400px] overflow-y-auto flex flex-col-reverse" wire:ignore.self id="notifications-list">

                @foreach ($notifications as $notification)
                    @php
                        $pivot = $notification->users->first()?->pivot;
                        $isRead = $pivot?->read_at;
                    @endphp

                    <a href="javascript:void(0)"
                        wire:click="open({{ $notification->id }})"
                        class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 justify-between gap-1"
                        style="{{ !$isRead ? 'background: rgba(99,102,241,0.08);' : '' }}">

                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 relative w-11 h-11 bg-success-200 dark:bg-success-600/25 text-success-600 flex justify-center items-center rounded-full">
                                <iconify-icon icon="bitcoin-icons:verify-outline"
                                    class="text-2xl"></iconify-icon>
                            </div>

                            <div>
                                <h6 class="text-sm fw-semibold mb-1">{{ $notification->title }}</h6>
                                <p class="mb-0 text-sm line-clamp-1">{{ $notification->message }}</p>
                            </div>
                        </div>

                        <div class="shrink-0">
                            <span class="text-sm text-neutral-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                    </a>
                @endforeach

            </div>

            <!-- Load More -->
            @if (\App\Models\Notification::count() > $notifications->count())
                <div class="text-center py-3 px-4">
                    <a href="javascript:void(0)"
                        wire:click="loadMore"
                    >
                        <span wire:loading.remove class="text-primary-600 dark:text-primary-600 font-semibold hover:underline text-center">
                            See more
                        </span>
                        <span wire:loading class="spinner text-primary-600"></span>
                    </a>
                </div>
            @endif

        </div>
    </div>

    @if($showModal && $selectedNotification)
        <div class="notif-modal-overlay" wire:key="modal-{{ $selectedNotification->id }}">
            <div class="notif-modal-container">
                <!-- Header -->
                <div class="notif-modal-header">
                    <h3>{{ $selectedNotification->title }}</h3>
                    <button wire:click="close" class="notif-modal-close">
                        <iconify-icon icon="radix-icons:cross-1"></iconify-icon>
                    </button>
                </div>

                <!-- Body -->
                <div class="notif-modal-body">
                    <p>{{ $selectedNotification->message }}</p>
                </div>

                <!-- Footer -->
                <div class="notif-modal-footer">
                    <button wire:click="close" class="notif-modal-btn">Close</button>
                </div>
            </div>

            <style>
                /* Overlay */
                .notif-modal-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    animation: fadeIn 0.25s ease-in-out;
                }

                /* Modal */
                .notif-modal-container {
                    background: #ffffff;
                    border-radius: 16px;
                    max-width: 480px;
                    width: 90%;
                    padding: 24px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                    animation: slideIn 0.3s ease-out;
                }

                /* Header */
                .notif-modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .notif-modal-header h3 {
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: #111827;
                }

                .notif-modal-close {
                    background: none;
                    border: none;
                    color: #6B7280;
                    font-size: 1.25rem;
                    cursor: pointer;
                    transition: color 0.2s ease, transform 0.2s ease;
                }

                .notif-modal-close:hover {
                    color: #111827;
                    transform: scale(1.1);
                }

                /* Body */
                .notif-modal-body p {
                    font-size: 1rem;
                    color: #374151;
                    line-height: 1.5;
                }

                /* Footer */
                .notif-modal-footer {
                    display: flex;
                    justify-content: flex-end;
                }

                .notif-modal-btn {
                    background: #4F46E5;
                    color: white;
                    padding: 0.6rem 1.5rem;
                    border-radius: 12px;
                    font-weight: 500;
                    box-shadow: 0 6px 18px rgba(79,70,229,0.3);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .notif-modal-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 24px rgba(79,70,229,0.35);
                }

                /* Animations */
                @keyframes fadeIn {
                    from {opacity: 0;}
                    to {opacity: 1;}
                }

                @keyframes slideIn {
                    from {opacity: 0; transform: translateY(-15px);}
                    to {opacity: 1; transform: translateY(0);}
                }

                /* Responsive */
                @media (max-width: 480px) {
                    .notif-modal-container {
                        padding: 18px;
                    }
                    .notif-modal-btn {
                        width: 100%;
                        text-align: center;
                    }
                }
            </style>
        </div>
    @endif

</div>