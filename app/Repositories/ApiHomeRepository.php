<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\Interfaces\ApiHomeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Notifications\New_Reservation;
use Carbon\Carbon;
use App\Models\images_offers;
use App\Models\Cuisine;
use App\Models\offer;
use App\Models\Menu;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Reviews;
use App\Models\Customer;

class ApiHomeRepository implements ApiHomeRepositoryInterface
{
    public function map_res($request)
    {
    $userLat = $request->latitude;
    $userLong = $request->longitude;

    $user = Customer::find(Auth::guard('customer-api')->id());
    $followedRestaurants = $user->followed_restaurants ?? [];

    return Restaurant::where('status','active')->with(['cuisine','Location', 'images' => function ($query) {
            $query->where('type', 'cover');
        }])
        ->get()
        ->take(10)
        ->map(function ($restaurant) use ($followedRestaurants, $userLat, $userLong) {
            $isFollowed = in_array($restaurant->id,$followedRestaurants);
            $distance = $this->calculateDistance($userLat, $userLong, $restaurant->location->latitude, $restaurant->location->longitude);
            return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'deposite' => $restaurant->deposit,
                'cuisine_name' => $restaurant->cuisine->name,
                'category' => $restaurant->category->name,
                'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                'ar_category' => $restaurant->category->ar_name,
                'rating_number' =>round($restaurant->rating, 1),
                'is_available' => $restaurant->availability == 'available' ? true : false,
                'location_latitude' => $restaurant->location->latitude,
                'location_longitude' => $restaurant->location->longitude,
                'location_state' => $restaurant->location->state,
                'location_text' => $restaurant->location->text,
                'ar_location_text' => $restaurant->location->ar_text,
                'images' => $restaurant->images->pluck('filename'),
                'isFollowed' => $isFollowed,
                'distance'=>$distance,
            ];
        });
    }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;

return round($miles * 1.609344, 2); // Convert to kilometers
}
    public function details_offer($id)
    {
        return offer::where('id', $id)
            ->with(['images' => function ($query) {
                $query->select('id', 'imageable_id', 'filename', 'type');
            }])->with(['restaurant' => function ($query) {
                $query->select('name');
            }])->first();
    }
    public function details_category($id)
    {
            return Restaurant::where('status','active')->where('category_id', $id)
                ->with(['location', 'cuisine', 'images' => function ($query) {
                    $query->where('type', 'cover');
                }])
                ->get()
                ->map(function ($restaurant) {
                    return [
                        'id' => $restaurant->id,
                        'name' => $restaurant->name,
                        'deposite' => $restaurant->deposit,
                        'cuisine_name' => $restaurant->cuisine->name,
                        'category' => $restaurant->category->name,
                        'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                        'ar_category' => $restaurant->category->ar_name,
                        'rating_number' => round($restaurant->rating, 1),
                        'availability' => $restaurant->availability == 'available' ? true : false,
                        'location_latitude' => $restaurant->location->latitude,
                        'location_longitude' => $restaurant->location->longitude,
                        'location_state' => $restaurant->location->state,
                        'location_text' => $restaurant->location->text,
                        'ar_location_text' => $restaurant->location->ar_text,
                        'images' => $restaurant->images->pluck('filename')
                    ];
                });
    }

}
