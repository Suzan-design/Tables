<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\api\Controller;
use App\Http\Requests\Action\ReviewRequest;
use App\Models\Action\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request)
    {
        $existingReview = Review::where('user_id', Auth::guard('mobile')->id())
            ->where('event_id', $request['event_id'])
            ->first();

        if ($existingReview) {
            return response()->json(['status' => false , 'message' => 'You have already reviewed this event.']);
        }

        // Create the review
        $review = new Review();
        $review->user_id = Auth::guard('mobile')->id();
        $review->event_id = $request['event_id'];
        $review->rate = $request['rate'];
        $review->comment = $request['comment'] ?? null;;
        $review->save();

        return response()->json(['status' => true,'message' => 'Review added successfully!'], 200);
    }

}
