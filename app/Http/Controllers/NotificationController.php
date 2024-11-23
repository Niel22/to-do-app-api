<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function count()
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Message retrieved successfully',
            'count' => Notification::count()
        ], 200);
        
    }
    
    public function show(Notification $notification)
    {
        return $notification;
    }
    
    public function read( Notification $notification)
    {
        $notification->update([
            'isRead' => !$notification->isRead
        ]);

        return response()->json([
            'success' => 200,
            'message' => "Notification " . ($notification->isRead ? "marked as read" : "marked as unread"),
            'notification' => $notification
        ], 201);
    }
    
    public function destroy()
    {
        $notifications = Notification::all();

        if ($notifications->count() > 0) {
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return response()->json([
                'success' => 200,
                'message' => "Notifications deleted",
                'notification' => $notifications
            ], 200);
        }

        return response()->json([
            'success' => 400,
            'message' => "No notifications to delete",
            'notification' => $notifications
        ], 400);


    }
}
