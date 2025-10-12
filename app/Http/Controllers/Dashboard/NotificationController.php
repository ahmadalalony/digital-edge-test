<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $this->dashboardService->getUserNotifications($user->id, 50);

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $this->dashboardService->getUserUnreadCount($user->id),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = $this->dashboardService->findUserNotification($user->id, $id);

        if ($notification) {
            $notification->markAsRead();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $this->dashboardService->markAllUserNotificationsRead($user->id);

        return response()->json(['success' => true]);
    }

    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = $this->dashboardService->getUserUnreadCount($user->id);

        return response()->json(['count' => $count]);
    }
}
