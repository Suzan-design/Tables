<?php

namespace App\Http\Controllers\api\Action;

use App\Http\Controllers\Controller;
use App\Models\Action\ServiceProviderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderReviewController extends Controller
{
    public function store(Request $request)
    {
        $existingReview = ServiceProviderReview::where('user_id', Auth::guard('mobile')->id())
            ->where('service_provider_id', $request['service_provider_id'])
            ->first();

        if ($existingReview) {
            return response()->json(['status' => false, 'message' => 'You have already reviewed this service provider.']);
        }

        // Create the review
        $review = new ServiceProviderReview();
        $review->user_id = Auth::guard('mobile')->id();
        $review->service_provider_id = $request['service_provider_id'];
        $review->rate = $request['rate'];
        $review->comment = $request['comment'] ?? null;;
        $review->save();

        return response()->json(['status' => true , 'message' => 'Review added successfully!'], 200);
    }

}
