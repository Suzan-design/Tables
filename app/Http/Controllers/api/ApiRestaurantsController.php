<?php

namespace App\Http\Controllers\Api;

use Hash;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Models\offer;
use App\Models\Table;
use App\Models\Cuisine;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\images_offers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\New_Reservation;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\ApiRestaurantRepositoryInterface;

class ApiRestaurantsController extends BaseController
{
    protected $ApiRestaurantRepository;

    public function __construct(ApiRestaurantRepositoryInterface $ApiRestaurantRepository)
    {
        $this->ApiRestaurantRepository = $ApiRestaurantRepository;
    }

    public function proposal_Restaurants(Request $request)
    {
        $data = $this->ApiRestaurantRepository->proposal_Restaurants($request);
        return response()->json($data);
    }
    public function details($id)
    {
        $data = $this->ApiRestaurantRepository->details($id);
        return $this->sendResponse($data, 'Restaurant_details');
    }
      public function details_guest($id)
    {
        $data = $this->ApiRestaurantRepository->details_guest($id);
        return $this->sendResponse($data, 'Restaurant_details');
    }
    

    public function followUnfollowRestaurant($id)
    {
        $message = $this->ApiRestaurantRepository->followUnfollowRestaurant($id);
        return response()->json($message);
    }
    public function follow($id) //id reservation
    {
        $resturant = Restaurant::where('id', $id)->first();
        // Get the current array of followed restaurants
        $user = Auth::user();
        $followed = $user->followed_restaurants;
        // Add the new restaurant ID
        $followed[] = $id;
        // Remove duplicates, if any
        $followed = array_unique($followed);
        // Save back to the user
        $user->followed_restaurants = $followed;
        $user->save();
    }
    public function unfollow($id) //id reservation
    {
        $resturant = Restaurant::where('id', $id)->first();
        $user = Auth::user();
        $followed = $user->followed_restaurants;
        // Remove the restaurant ID
        $followed = array_diff($followed, [$id]);
        // Save back to the user
        $user->followed_restaurants = $followed;
        $user->save();
    }

    public function list_rec_follow()
    {
        $data = $this->ApiRestaurantRepository->list_rec_follow();
        return $this->sendResponse($data, 'followings_recommends');
    }
    public function search(Request $request)
    {
        $result = $this->ApiRestaurantRepository->search($request);
        return $this->sendResponse($result, 'search_results');
    }
    public function advansearch(Request $request)
    {
        $result = $this->ApiRestaurantRepository->advansearch($request);
        return $this->sendResponse($result, 'search_results');
    }

    public function filtersearch(Request $request)
    {
        $data = $this->ApiRestaurantRepository->filtersearch($request);
        return $this->sendResponse($data, 'search_results');
    }

    public function review(Request $request, $id)
    {
        $rules = [
            'rating' => 'required|numeric',
            'comment' => 'nullable|string|max:100',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
            ], 203);
        }
        $this->ApiRestaurantRepository->review($request, $id);
    }
    public function reviews($id)
    {
        $reviews = $this->ApiRestaurantRepository->reviews($id);
        return $reviews;
    }
    public function nearest_Restaurants()
    {
        $responseData = $this->ApiRestaurantRepository->nearest_Restaurants();
        return $this->sendResponse($responseData, 'nearest_Restaurants');
    }
    public function cuisine_Restaurants($id)
    {
        $cuisine_Restaurants = $this->ApiRestaurantRepository->cuisine_Restaurants($id);
        return $this->sendResponse($cuisine_Restaurants, 'cuisine_Restaurants');
    }
    public function forYouRestaurants()
    {
        $user = Customer::find(Auth::guard('customer-api')->id());
        $followedRestaurantsIds = $user->followed_restaurants;
        
       
        
        // Filter out any null values that may have been added by the array_map
        if($followedRestaurantsIds){
        $followedRestaurantsIds = array_filter($followedRestaurantsIds, function ($id) {
            return $id !== null;
        });
        }
    
    $relationships = ['cuisine', 'location', 'images' => function ($query) {
        $query->where('type', 'cover');
    }];
        if (empty($followedRestaurantsIds)) {
            // If no followed restaurants, get all restaurants
            $forYouRestaurants = Restaurant::where('status','active')->with($relationships)->get()
            ->map(function ($restaurant) {
                if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                $isFollowed = false;
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => round($restaurant->rating, 1),
                    'is_available' => $available,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'images' => $restaurant->images->pluck('filename'),
                    'isFollowed' => $isFollowed,
                ];
            });
        } else {
        // Get the unique cuisine and category ids from followed restaurants
        $followedRestaurantsCuisines = Restaurant::whereIn('id', $followedRestaurantsIds)
            ->pluck('cuisine_id')
            ->unique();
    
        $followedRestaurantsCategories = Restaurant::whereIn('id', $followedRestaurantsIds)
            ->pluck('category_id')
            ->unique();
    
        // Now, query for restaurants that have the same cuisine or category
        $forYouRestaurants = Restaurant::where('status','active')->with($relationships)
            ->where(function($query) use ($followedRestaurantsCuisines, $followedRestaurantsCategories) {
                $query->whereIn('cuisine_id', $followedRestaurantsCuisines)
                      ->orWhereIn('category_id', $followedRestaurantsCategories);
            })
            ->get()
            ->map(function ($restaurant) use ($followedRestaurantsIds) {
            if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                $isFollowedRes = in_array($restaurant->id, $followedRestaurantsIds);
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'category' => $restaurant->category->name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => round($restaurant->rating, 1),
                    'is_available' => $available,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'images' => $restaurant->images->pluck('filename'),
                    'isFollowed' => $isFollowedRes,
                ];
            }); 
    }
        $responseData = [
            'section_title' => 'For You',
            'restaurants' => $forYouRestaurants
        ];
    
        return $this->sendResponse($responseData, 'forYouRestaurants');
    }

    public function new_opening()
    {

        $offers = Offer::where('type', 'new_opening')
            ->with(['images' => function ($query) {
                $query->select('id', 'imageable_id', 'filename', 'type');
            },'restaurant' => function ($query) {
                $query->where('status','active');
            }])->get();
        $allOffersData = [];

        foreach ($offers as $offer) {
            $pathMain = null;
            $filteredImages = [];
            foreach ($offer->images as $image) {
                if ($image->type === 'cover') {
                    $pathMain = $image->filename;
                } elseif ($image->type === 'gallery') {
                    $filteredImages[] = $image;
                } 
            }
            $modifiedOffer = [
                'id' => $offer->id,
                'Restaurant_id' => $offer->Restaurant_id,
                'Restaurant_name' => $offer->restaurant->name,
                'price_old' => $offer->price_old,
                'price_new' => $offer->price_new,
                'description' => $offer->description,
                'ar_description' => $offer->ar_description,
                'name' => $offer->name,
                'ar_name' => $offer->ar_name,
                'type' => $offer->type,
                'start_date' => $offer->start_date,
                'status' => $offer->status,
                'featured' => $offer->featured,
                'path_main' => $pathMain,
                'created_at' => $offer->created_at,
                'updated_at' => $offer->updated_at,
                'images' => $filteredImages,
            ];
            $allOffersData[] = $modifiedOffer;
        }
        $responseData = [
            'section_title' => 'new_opening',
            'new_opening' => $allOffersData
        ]; 
        return $this->sendResponse($responseData, 'new_opening');
    }
    public function featured()
    {
        $user = Customer::find(Auth::guard('customer-api')->id());
        $followedRestaurants = $user->followed_restaurants ?? [];
        $featured = Restaurant::where('status','active')->where('isFeatured',true)->with(['cuisine','Location', 'images' => function ($query) {
                $query->where('type', 'cover');
            }, 'location'])
            ->get()
            ->map(function ($restaurant) use ($followedRestaurants) {
            if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                $isFollowed = in_array($restaurant->id, $followedRestaurants);
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'category' => $restaurant->category->name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => round($restaurant->rating, 1),
                    'is_available' => $available,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'images' => $restaurant->images->pluck('filename'),
                    'isFollowed' => $isFollowed,
                ];
            });
        $responseData = [
            'section_title' => 'featured',
            'restaurants' => $featured
        ];
        return $this->sendResponse($responseData, 'featured');
    }
    public function offers()
    {
        $offers = Offer::where('type', 'offer')
            ->with(['images' => function ($query) {
                $query->select('id', 'imageable_id', 'filename', 'type');
            },'restaurant' => function ($query) {
                $query->where('status','active');
            }])->get();
        $allOffersData = [];
        foreach ($offers as $offer) {
            $pathMain = null;
            $filteredImages = [];
            foreach ($offer->images as $image) {
                if ($image->type === 'cover') {
                    $pathMain = $image->filename;
                } elseif ($image->type === 'gallery') {
                    $filteredImages[] = $image;
                }
            }
            $modifiedOffer = [
                'id' => $offer->id,
                'Restaurant_id' => $offer->Restaurant_id,
                'Restaurant_name' => $offer->restaurant->name,
                'price_old' => $offer->price_old,
                'price_new' => $offer->price_new,
                'description' => $offer->description,
                'ar_description' => $offer->ar_description,
                'name' => $offer->name,
                'ar_name' => $offer->ar_name,
                'type' => $offer->type,
                'start_date' => $offer->start_date,
                'status' => $offer->status,
                'featured' => $offer->featured,
                'path_main' => $pathMain,
                'created_at' => $offer->created_at,
                'updated_at' => $offer->updated_at,
                'images' => $filteredImages,
            ];

            $allOffersData[] = $modifiedOffer;
        }
        $responseData = [
            'section_title' => 'offers',
            'offers' => $allOffersData
        ];
        return $this->sendResponse($responseData, 'all_offers');
    }


    public function taste()
    {
        $user = Customer::find(Auth::guard('customer-api')->id());
        $followedRestaurants = $user->followed_restaurants ?? [];
        $taste = Restaurant::where('status','active')->with(['cuisine','Location' , 'images' => function ($query) {
                $query->where('type', 'cover');
            }, 'location'])
            ->get()
            ->map(function ($restaurant) use ($followedRestaurants) {
            if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                $isFollowed = in_array($restaurant->id, $followedRestaurants);
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => round($restaurant->rating, 1),
                    'is_available' => $available,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'images' => $restaurant->images->pluck('filename'),
                    'isFollowed' => $isFollowed,
                ];
            });
        $responseData = [
            'section_title' => 'taste',
            'restaurants' => $taste
        ];
        return $this->sendResponse($responseData, 'taste');
    }
    public function get_all_restaurants()
    {
        $restaurants = Restaurant::where('status','active')->with(['cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->get()
            ->map(function ($restaurant) {
            if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'Deposite_value' => $restaurant->Deposite_value,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'is_available' => $available,
                    'isFollowed' => False,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'rating_number' => round($restaurant->rating, 1),
                    'images' => $restaurant->images->pluck('filename'),
                ];
            });
        return $this->sendResponse($restaurants, 'all_restaurants');
    }
    public function restaurants_token()
    {
        $user = Customer::find(Auth::guard('customer-api')->id());
        $followedRestaurants = $user->followed_restaurants ?? [];
        $restaurants = Restaurant::where('status','active')->with(['cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->get()
            ->map(function ($restaurant) use ($followedRestaurants) {
            if($restaurant->availability == 'available'){ $available = true;}
                else {$available = false;}
                $isFollowed = in_array($restaurant->id, $followedRestaurants);
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'is_available' => $available,
                    'isFollowed' => $isFollowed,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'rating_number' => round($restaurant->rating, 1),
                    'images' => $restaurant->images->pluck('filename'),

                ];
            });
        return $this->sendResponse($restaurants, 'all_restaurants');
    }
    public function cuisines()
    {
    $followedRestaurants = [];
    $cuisines = Cuisine::whereHas('restaurants')->get()->map(function ($cuisine) {
    return [
        'id' => $cuisine->id,
        'name' => $cuisine->name,
        'ar_name' => $cuisine->ar_name, 
    ];
});

    return $this->sendResponse($cuisines, 'Cuisines');
    }
public function types_tables()
{
    // Fetch all tables from the database
    $tables = Table::select('id', 'size', 'location', 'type')->get();

    $organizedData = [];

    // Organize the tables by size, location, and type
    foreach ($tables as $table) {
        // Using size as the first key
        if (!isset($organizedData[$table->size])) {
            $organizedData[$table->size] = [];
        }

        // Using location as the second key
        if (!isset($organizedData[$table->size][$table->location])) {
            $organizedData[$table->size][$table->location] = [];
        }

        // Adding type to the array if it's not already there
        if (!in_array($table->type, $organizedData[$table->size][$table->location])) {
            $organizedData[$table->size][$table->location][] = $table->type;
        }
    }

    // Optionally, reformat the array to have clearer keys, similar to the formatting in available_tables
    $formattedData = [];
    foreach ($organizedData as $size => $locations) {
        $locationData = [];
        foreach ($locations as $location => $types) {
            $locationData[] = [
                'location' => $location,
                'types' => $types
            ];
        }
        $formattedData[] = [
            'size' => $size,
            'locations' => $locationData
        ];
    }

    return $this->sendResponse($formattedData, 'allTablesStructured');
}


    
    
    
}
