<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return new JsonResponse(
                $request->user()
                    ->notifications()
                    ->latest()
                    ->take(5)
                    ->get()
            );
        }

        return view('notifications.index', [
            'notifications' => $request->user()
                ->notifications()
                ->paginate(15)
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return new JsonResponse(['message' => 'All notifications marked as read']);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return new JsonResponse(['message' => 'Notification marked as read']);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return new JsonResponse(['message' => 'Notification deleted']);
    }
} 