<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = auth()->user()->notifications()
            ->unread()
            ->count();

        return view('student.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read');
    }

    public function getUnreadCount()
    {
        $count = auth()->user()->notifications()
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    public function recentJson()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => \Illuminate\Support\Str::limit($n->message, 60),
                    'is_read' => $n->is_read,
                    'action_url' => route('student.notifications.mark-as-read', $n->id),
                    'time_ago' => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }
}