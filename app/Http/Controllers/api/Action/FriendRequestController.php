<?php

namespace App\Http\Controllers\api\Action;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Action\FriendRequest;
use App\Models\Action\Notification;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function store($id)
    {
        FriendRequest::firstOrCreate([
            'sender_id' => Auth::guard('mobile')->id(),
            'receiver_id' => $id ,
            'status' => 'pending'
        ]);
        event(new NotificationEvent('New Friend Request' , Auth::user()->first_name .' '. Auth::user()->last_name . ' sent you a friend request' , $id));
        Notification::create([
            'title' =>'New Friend Request' ,
            'description' =>Auth::user()->first_name .' '. Auth::user()->last_name . ' sent you a friend request',
            'user_id' => $id
        ]);
        return response()->json([
            'status'=> true ,
            'message' => 'sent successfully'
        ]);
    }

    public function deny($id)
    {
        $friend_request = FriendRequest::where('receiver_id',Auth::guard('mobile')->id())->where('sender_id',$id)->first();

        if ($friend_request)
        {
            $friend_request->delete() ;
            return response()->json([
                'status' => true ,
                'message'=> 'removed successfully'
            ]);
        }
        return  response()->json([
            'status' => true,
            'message' => 'already Not exist'
        ]);

    }
    public function destroy($id)
    {
        $userId = Auth::id() ;
        $friend_request = FriendRequest::where(function($query) use ($userId , $id) {
                $query->where('sender_id', $id)
                    ->Where('receiver_id', $userId);
            })->orWhere(function($query) use ($userId , $id) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $id);
        })->first();

        if ($friend_request)
        {
            $friend_request->delete() ;
            return response()->json([
                'status' => true ,
                'message'=> 'removed successfully'
            ]);
        }
        return  response()->json([
            'status' => true,
            'message' => 'already Not exist'
        ]);
    }

    public function approve($id)
    {
        $friend_request = FriendRequest::where('receiver_id',Auth::guard('mobile')->id())->where('sender_id',$id)->first();

        if ($friend_request)
        {
            if($friend_request->receiver_id == Auth::id()) {
                $friend_request->update([
                    'status' => 'approve'
                ]);
                event(new NotificationEvent('New Friend' , Auth::user()->first_name .' '. Auth::user()->last_name . ' has accepted your friend request' , $id));
                Notification::create([
                    'title' =>'New Friend' ,
                    'description' =>Auth::user()->first_name .' '. Auth::user()->last_name . ' has accepted your friend request',
                    'user_id' => $id
                ]);
                return response()->json([
                    'status' => true ,
                    'message' => 'Approved'
                ]);
            }else{
                return response()->json([
                    'status'  =>true ,
                    'message' => 'denied'
                ]) ;
            }
        }
        return  response()->json([
            'status' => true,
            'message' => 'Not exist'
        ]);
    }

    public function my_friend()
    {
        $userId = Auth::id();

        $friendRequests = FriendRequest::with(['sender:id,first_name,last_name,image,phone_number,birth_date', 'receiver:id,first_name,last_name,image,phone_number,birth_date'])
            ->where('status', 'approve')
            ->where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })->get();

        $friends = $friendRequests->map(function ($friendRequest) use ($userId) {
            return $friendRequest->sender_id == $userId ? $friendRequest->receiver : $friendRequest->sender;
        });

        return response()->json([
            'status' => true,
            'friends' => $friends
        ]);
    }


    public function my_sent_request()
    {
        $sent_friend_request = FriendRequest::with(['receiver:id,first_name,last_name,image'])->where('sender_id',Auth::id())->where('status' , 'pending')->get() ;
        return response()->json([
            'status' => true ,
            'Sent_request' => $sent_friend_request
        ]) ;
    }

    public function my_receive_request()
    {
        $my_receive_request = FriendRequest::with('sender:id,first_name,last_name,image')->where('receiver_id',Auth::id())->where('status' , 'pending')->get() ;
        return response()->json([
            'status' => true ,
            'receive_request' => $my_receive_request
        ]);
    }
}
