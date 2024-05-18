<?php

namespace App\Http\Controllers\api;

use App\Models\Common\Interest;
use App\Models\Event\EventsCategory;
use App\Models\User\MobileUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventCategoryUserController extends Controller
{

    public function index()
    {
        $interest = EventsCategory::select('id','icon' , 'title' , 'title_ar')->get() ;
        return response()->json([
            'status' => true ,
            'interest' =>  $interest
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('mobile')->user();

        // Assuming the request contains an array of 'event_category_ids'
        $eventCategoryIds = $request->interest;

        // Sync the user's event categories
        $user->eventCategories()->sync($eventCategoryIds);

        return response()->json(['status' => true, 'message' => 'Event categories updated successfully.']);
    }

    public function user_favorite_categories()
    {
        $user = MobileUser::with('eventCategories')->find(Auth::guard('mobile')->id());
        return response()->json(['status' => true,
            'categories' => $user->eventCategories]);
    }

}
