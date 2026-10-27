<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = Notification::visibleTo($user)
            ->with(['users' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->paginate(20);

        $data = $notifications->getCollection()->map(fn ($n) => [
            'id' => $n->id,
            'title' => $n->title,
            'message' => $n->message,
            'is_critical' => (bool) $n->is_critical,
            'read' => (bool) $n->users->first()?->pivot?->read_at,
            'created_at' => $n->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'data' => $data,
            'unread_count' => $this->unreadCount($user),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    public function unread(Request $request)
    {
        return response()->json(['unread_count' => $this->unreadCount($request->user())]);
    }

    public function read(Request $request, $id)
    {
        $user = $request->user();
        $notification = Notification::visibleTo($user)->findOrFail($id);

        $user->notifications()->syncWithoutDetaching([
            $notification->id => ['read_at' => now(), 'seen_at' => now()],
        ]);

        return response()->json(['unread_count' => $this->unreadCount($user)]);
    }

    public function readAll(Request $request)
    {
        $user = $request->user();
        $ids = Notification::visibleTo($user)->pluck('id');

        $user->notifications()->syncWithoutDetaching(
            $ids->mapWithKeys(fn ($id) => [$id => ['read_at' => now(), 'seen_at' => now()]])->all()
        );

        return response()->json(['unread_count' => 0]);
    }

    private function unreadCount($user): int
    {
        return Notification::visibleTo($user)
            ->whereDoesntHave('users', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('notification_user.read_at');
            })
            ->count();
    }
}
