<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\Controller;
use App\Models\Action\VenueReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueReviewController extends Controller
{
    public function store(Request $request)
    {
        $existingReview = VenueReview::where('user_id', Auth::guard('mobile')->id())
            ->where('venue_id', $request['venue_id'])
            ->first();

        if ($existingReview) {
            return response()->json(['status' => false,'message' => 'You have already reviewed this venue.']);
        }

        // Create the review
        $review = new VenueReview();
        $review->user_id = Auth::guard('mobile')->id();
        $review->venue_id = $request['venue_id'];
        $review->rate = $request['rate'];
        $review->comment = $request['comment'] ?? null;;
        $review->save();

        return response()->json(['status' => true,'message' => 'Review added successfully!'], 200);
    }

}
