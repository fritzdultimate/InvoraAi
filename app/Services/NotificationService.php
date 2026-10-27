<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService {
    /**
     * Get all active critical notifications for user
     */
    public static function getCriticalForUser(User $user) {
        return Notification::critical()
            ->visibleTo($user)
            ->whereDoesntHave('users', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereNotNull('dismissed_at');
            })
            ->latest()
            ->get();
    }

    public static function getForUser(User $user) {
        return Notification::visibleTo($user)
            ->with(['users' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->get();
    }

    /**
     * Mark notifications as read
     */
    public static function markAsRead(User $user, $notifications) {
        foreach ($notifications as $notification) {
            $user->notifications()->syncWithoutDetaching([
                $notification->id => [
                    'read_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Dismiss notifications
     */
    public static function dismiss(User $user, $notifications) {
        foreach ($notifications as $notification) {
            $user->notifications()->syncWithoutDetaching([
                $notification->id => [
                    'read_at' => now(),
                    'dismissed_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Create notification
     */
    public static function create(array $data) {
        return Notification::create([
            'user_id' => $data['user_id'] ?? null,
            'title' => $data['title'],
            'message' => $data['message'],
            'is_critical' => $data['is_critical'] ?? false,
            'meta' => $data['meta'] ?? null
        ]);
    }

    public static function createForUser(User $user, array $data) {
        $notification = self::create(array_merge($data, ['user_id' => $user->id]));

        $user->notifications()->attach($notification->id);

        return $notification;
    }
}