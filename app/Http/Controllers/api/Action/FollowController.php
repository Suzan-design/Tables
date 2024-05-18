<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\api\Controller;
use App\Models\Action\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store($id)
    {
        Follow::firstOrCreate([
            'user_id' => Auth::guard('mobile')->id(),
            'organizer_id' => $id
        ]);

        return response()->json([
            'status'=> true ,
            'message' => 'followed successfully'
        ]);
    }

    public function destroy($id)
    {
        $follow = Follow::where('user_id',Auth::guard('mobile')->id())->where('organizer_id',$id)->first() ;

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
}
