<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\Controller;
use App\Models\Action\EventFollow;
use App\Models\Action\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventFollowController extends Controller
{
    public function store($id)
    {
        EventFollow::firstOrCreate([
            'user_id' => Auth::guard('mobile')->id(),
            'event_id' => $id
        ]);

        return response()->json([
            'status'=> true ,
            'message' => 'followed successfully'
        ]);
    }

    public function destroy($id)
    {
        $follow = EventFollow::where('user_id',Auth::guard('mobile')->id())->where('event_id',$id) ;

        if ($follow)
        {
            $follow->delete() ;
            return response()->json([
                'status' => true ,
                'message'=> 'removed successfully'
            ]);
        }
        return  response()->json([
            'status' => true,
            'message' => 'already removed'
        ]);

    }
    public function following_event()
    {
        $follow = EventFollow::with('event.venue')->where('user_id',Auth::guard('mobile')->id())->get() ; 

        return response()->json([
            'status' => true ,
            'events' =>$follow
        ]);
    }

}
