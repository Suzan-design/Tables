<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\Controller;
use App\Models\Action\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function myNotification()
    {
        $user_id = Auth::id();
        $notifications = Notification::where('user_id', $user_id)->get();

        $response = response()->json([
            'status' => true,
            'Notification' => $notifications
        ]);

        foreach ($notifications as $notification) {
            $notification->update(['type' => 'seen']);
        }

        return $response ;
    }

   


}
