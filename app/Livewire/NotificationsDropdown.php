<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationsDropdown extends Component {

    public $notifications = [];
    public $limit = 20;
    public $unseenCount = 0;
    public $selectedNotification = null;
    public $showModal = false;
    public $dropdownOpen = false;

    public function mount() {
        $this->loadNotifications();
    }

    public function open($id) {
        $notification = Notification::findOrFail($id);

        // mark as read
        Auth::user()->notifications()->syncWithoutDetaching([
            $id => ['read_at' => now()]
        ]);

        $this->selectedNotification = $notification;
        $this->showModal = true;

        $this->loadNotifications();
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function loadNotifications() {
        $userId = Auth::id();

        $this->notifications = Notification::with(['users' => function ($q) use($userId) {
            $q->where('user_id', $userId);
        }])
        ->latest()
        ->take($this->limit)
        ->get();

        $this->unseenCount = Notification::whereHas('users', function($q) use($userId){
            $q->where('user_id', $userId)->whereNull('seen_at');
        })->count();
    }

    public function loadMore() {
        $this->limit += 5;

        $this->notifications = Notification::with(['users' => function ($q) {
            $q->where('user_id', Auth::id());
        }])
        ->latest()
        ->take($this->limit)
        ->get();
    }

    public function toggleDropdown() {
        if ($this->unseenCount > 0) {
            $this->markAllAsSeen();
        }
    }

    public function markAllAsSeen() {
        $user = Auth::user();
        $user->notifications()->update(['seen_at' => now()]);

        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications-dropdown', [
            'notifications' => $this->notifications
        ]);
    }
}
