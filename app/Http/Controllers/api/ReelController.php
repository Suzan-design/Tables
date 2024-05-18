<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Action\ReelComment;
use App\Models\Action\ReelLike;
use Illuminate\Http\Request;
use App\Models\Common\Reel;

class ReelController extends Controller
{

    public function index()
    {
        $userId = auth()->id() ;

        $reels = Reel::with('event', 'venue', 'user')
            ->withCount(['likes', 'comments'])
            ->paginate(4)
            ->through(function ($reel) use ($userId) {
                $reel->liked_by_user = $reel->likes()->where('user_id', $userId)->exists();
                return $reel;
            });

        return response()->json([
            'status' => true,
            'reels' => $reels
        ]);
    }

    public function addLike($reelId)
    {
        $userId = auth()->id();
        $like = ReelLike::where('user_id', $userId)->where('reel_id', $reelId)->first();

        if ($like) {
            $like->delete() ;
            return response()->json([
                'status' => true ,
                'message' =>'like deleted successfully'
                ],200) ;
        }else{
            // Add a new like
            ReelLike::create([
                'user_id' => $userId,
                'reel_id' => $reelId
            ]);

            return response()->json([
                'status' => true ,
                'message' => 'Like added successfully'], 200);
        }
    }

    public function addComment(Request $request, $reelId)
    {
        $data = $request->validate([
            'body' => 'required|string'
        ]);

        $comment = ReelComment::create([
            'body' => $data['body'],
            'user_id' => auth()->id(),
            'reel_id' => $reelId
        ]);

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 200);
    }
}
