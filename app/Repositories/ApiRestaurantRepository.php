<?php

namespace App\Repositories;

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
use App\Models\times;
use App\Models\Invitation;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\images_of_offers;
use Illuminate\Support\Facades\DB;
use App\Services\InvitationService;
use Illuminate\Support\Facades\Auth;
use App\Models\restaurnats_categories;
use App\Notifications\New_Reservation;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\ApiRestaurantRepositoryInterface;

class ApiRestaurantRepository implements ApiRestaurantRepositoryInterface
{
    protected $invitationService;
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function proposal_Restaurants($request)
    {
        $categories = restaurnats_categories::whereHas('Restaurants')->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'ar_name' => $category->ar_name,
                    'icon' => $category->icon,
                ];
            });
         $user = Customer::find(Auth::guard('customer-api')->id());
         if($user){
        $followedRestaurantsIds = $user->followed_restaurants;
        
       }else{
       $followedRestaurantsIds = null;
       }
        if($followedRestaurantsIds){
        // Filter out any null values that may have been added by the array_map
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
                $isFollowed = false;
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => $restaurant->rating,
                    'is_available' => $restaurant->availability,
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
        $followedRestaurantsCuisines = Restaurant::where('status','active')->whereIn('id', $followedRestaurantsIds)
            ->pluck('cuisine_id')
            ->unique();
    
        $followedRestaurantsCategories = Restaurant::where('status','active')->whereIn('id', $followedRestaurantsIds)
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
                $isFollowedRes = in_array($restaurant->id, $followedRestaurantsIds);
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'deposite' => $restaurant->deposit,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'category' => $restaurant->category->name,
                    'ar_category' => $restaurant->category->ar_name,
                    'rating_number' => $restaurant->rating,
                    'is_available' => $restaurant->availability,
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

        $featuredRestaurants = Restaurant::where('status','active')->with(['cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->where('isFeatured',true)
            ->get()
            ->map(function ($restaurant) {
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'Deposite_value' => $restaurant->Deposite_value,
                    'Deposite_desc' => $restaurant->Deposite_desc,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                     'rating_number' =>  round($restaurant->rating, 1),
                    'images' => $restaurant->images->pluck('filename'),
                    'isFeatured'=> $restaurant->isFeatured,
                ];
            })->take('10');
        $basedOnYourTasteRestaurants = Restaurant::where('status','active')->with(['cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->get()
            ->map(function ($restaurant) {
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'Deposite_value' => $restaurant->Deposite_value,
                    'Deposite_desc' => $restaurant->Deposite_desc,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'location_latitude' => $restaurant->location->latitude,
                    'location_longitude' => $restaurant->location->longitude,
                    'location_state' => $restaurant->location->state,
                    'location_text' => $restaurant->location->text,
                    'ar_location_text' => $restaurant->location->ar_text,
                    'rating_number' =>round($restaurant->rating, 1),
                    'images' => $restaurant->images->pluck('filename'),
                ];
            })->take('10');

        $new_opening = DB::table('images_of_offers')
            ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
            ->select('offers.id', 'images_of_offers.filename')
            ->where('status','active')
            ->where('offers.type', 'new_opening')
            ->where('images_of_offers.type', 'cover')
            ->get()->take('10');
            
        $offers = DB::table('images_of_offers')
            ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
            ->select('offers.id', 'images_of_offers.filename')
            ->where('status','active')
            ->where('offers.type', 'offer')
            ->where('images_of_offers.type', 'cover')
            ->get()->take('10');

        if(!$user){
        $data = [
            'forYouRestaurants' => [],
            'featuredRestaurants' => $featuredRestaurants,
            'basedOnYourTasteRestaurants' => $basedOnYourTasteRestaurants,
            'offers' => $offers,
            'new_opening' => $new_opening,
            'categories' => $categories,

        ];
        }else{
        $data = [
            'forYouRestaurants' => $forYouRestaurants,
            'featuredRestaurants' => $featuredRestaurants,
            'basedOnYourTasteRestaurants' => $basedOnYourTasteRestaurants,
            'offers' => $offers,
            'new_opening' => $new_opening,
            'categories' => $categories,

        ];
        }

        return response()->json($data);
    }
    

 public function details_guest($id)
    {
        $restaurant = Restaurant::where('status','active')->with([
            'images' => function ($query) {
                $query->select('id', 'filename', 'type', 'imageable_id')
                    ->whereIn('type', ['craousal', 'gallery']);
            },
            'location' => function ($query) {
                $query->select('id', 'latitude', 'longitude', 'state', 'text', 'ar_text', 'Restaurant_id');
            },
            'reviews' => function ($query) {
                $query->with('customer:id,firstname,lastname,profilePicture');
            },
            'cuisine',
            'menu' => function ($query) {
                $query->with(['type' => function ($typeQuery) {
                    $typeQuery->has('menuitems');
                }]);
            },
            'category'
        ])
            ->withCount('reviews as count_reviews')
            ->select(
                'id',
                'time_start',
                'cuisine_id',
                'time_end',
                'name',
                'description',
                'ar_description',
                'category_id',
                'phone_number',
                'Deposite_value',
                'Deposite_desc',
                'ar_Deposite_desc',
                'rating',
                'services',
                'ar_services',
                'website',
                'instagram',
                'refund_policy',
                'change_policy',
                'cancellition_policy',
                'ar_refund_policy',
                'ar_change_policy',
                'ar_cancellition_policy'
            )
            ->where('id', $id)
            ->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }
              $types = $restaurant->menu->groupBy('type.name')->map(function ($typeGroup, $typeName) {
        return [
            'name' => $typeName,
            'symbol' => $typeGroup->first()->type->symbol,
            'items' => $typeGroup->flatMap(function ($menu) {
                return $menu->type->menuitems->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'ar_name' => $item->ar_name,
                        'price' => $item->price,
                        'icon' => $item->icon
                    ];
                });
            })->unique('name')->values() // ????? ??????? ???? ???? ?????? ????? ????????
        ];
    });

        $formattedRestaurant = [
            'images' => $restaurant->images->map(function ($image) {
                return [
                    'filename' => $image->filename,
                    'type' => $image->type
                ];
            }),
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'location' => $restaurant->location,
            'phone' => $restaurant->phone_number,
            'time_start' => $restaurant->time_start,
            'time_end' => $restaurant->time_end,
            'cuisine_name' => $restaurant->category->name,
            'category_name' => $restaurant->cuisine->name,
            'ar_cuisine_name' => $restaurant->category->ar_name,
            'ar_category_name' => $restaurant->cuisine->ar_name,
            'description' => $restaurant->description,
            'ar_description' => $restaurant->ar_description,
            'services' => json_decode($restaurant->services),
            'ar_services' => json_decode($restaurant->ar_services),
            'website' => $restaurant->website,
            'insta' => $restaurant->instagram,
            'Deposite_value' => $restaurant->Deposite_value,
            'Deposite_desc' => $restaurant->Deposite_desc,
            'refund_policy' => $restaurant->refund_policy,
            'change_policy' => $restaurant->change_policy,
            'cancellition_policy' => $restaurant->cancellition_policy,
            'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
            'ar_refund_policy' => $restaurant->ar_refund_policy,
            'ar_change_policy' => $restaurant->ar_change_policy,
            'ar_cancellition_policy' => $restaurant->ar_cancellition_policy,
        'menu' => $types->values(),
            'reviews' => $restaurant->reviews->map(function ($review) {
                return [
                    'image' => $review->customer->profilePicture,
                    'name' => $review->customer->firstname . ' ' . $review->customer->lastname,
                    'rating' => $review->rating,
                    'comment' => $review->comment
                ];
            })
        ];
    
    

        $formattedRestaurant['isFollowed'] = False;

        return response()->json(['restaurant_details' => $formattedRestaurant]);
    }
    



    public function details($id)
    {
    $currentDay = strtolower(Carbon::now()->format('D'));
    $times = times::where('Restaurant_id',$id)->first();
    if($times){
    $openTime = Carbon::createFromTimestamp(strtotime($times->{$currentDay . '_from'}))->format('H');
    $closeTime = Carbon::createFromTimestamp(strtotime($times->{$currentDay . '_to'}))->format('H');
    }else{
    $openTime = null;
    $closeTime = null;
    }

        $restaurant = Restaurant::where('status','active')->with([
            'images' => function ($query) {
                $query->select('id', 'filename', 'type', 'imageable_id')
                    ->whereIn('type', ['craousal', 'gallery']);
            },
            'location' => function ($query) {
                $query->select('id', 'latitude', 'longitude', 'state', 'text', 'ar_text', 'Restaurant_id');
            },
            'reviews' => function ($query) {
                $query->with('customer:id,firstname,lastname,profilePicture');
            },
            'cuisine',
            'menu' => function ($query) {
                $query->with(['type' => function ($typeQuery) {
                    $typeQuery->has('menuitems');
                }]);
            },
            'category'
        ])
            ->withCount('reviews as count_reviews')
            ->select(
                'id',
                'time_start',
                'cuisine_id',
                'time_end',
                'name',
                'description',
                'ar_description',
                'category_id',
                'phone_number',
                'deposit',
                'rating',
                'services',
                'ar_services',
                'website',
                'instagram',
                'Deposite_value',
                'Deposite_desc',
                'refund_policy',
                'change_policy',
                'cancellition_policy',
                'ar_Deposite_desc',
                'ar_refund_policy',
                'ar_change_policy',
                'ar_cancellition_policy'
            )
            ->where('id', $id)
            ->first();
            
            
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }
           $types = $restaurant->menu->groupBy('type.name','type.ar_name')->map(function ($typeGroup, $typeName) {
        return [
            'name' => $typeName,
            'symbol' => $typeGroup->first()->type->symbol,
            'ar_name' => $typeGroup->first()->type->ar_name,
            'items' => $typeGroup->flatMap(function ($menu) {
                return $menu->type->menuitems->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'ar_name' => $item->ar_name,
                        'price' => $item->price,
                        'icon' => $item->icon
                    ];
                });
            })->unique('name')->values() // ????? ??????? ???? ???? ?????? ????? ????????
        ];
    });

        $formattedRestaurant = [
            'images' => $restaurant->images->map(function ($image) {
                return [
                    'filename' => $image->filename,
                    'type' => $image->type
                ];
            }),
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'location' => $restaurant->location,
            'phone' => $restaurant->phone_number,
            'time_start' => $restaurant->time_start,
            'time_end' => $restaurant->time_end,
            'cuisine_name' => $restaurant->category->name,
            'category_name' => $restaurant->cuisine->name,
            'ar_cuisine_name' => $restaurant->category->ar_name,
            'ar_category_name' => $restaurant->cuisine->ar_name,
            'description' => $restaurant->description,
            'ar_description' => $restaurant->ar_description,
            'services' => json_decode($restaurant->services),
            'ar_services' => json_decode($restaurant->ar_services),
            'website' => $restaurant->website,
            'insta' => $restaurant->instagram,
            'Deposite_value' => $restaurant->Deposite_value,
            'Deposite_desc' => $restaurant->Deposite_desc,
            'refund_policy' => $restaurant->refund_policy,
            'change_policy' => $restaurant->change_policy,
            'cancellition_policy' => $restaurant->cancellition_policy,
            'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
            'ar_refund_policy' => $restaurant->ar_refund_policy,
            'ar_change_policy' => $restaurant->ar_change_policy,
            'ar_cancellition_policy' => $restaurant->ar_cancellition_policy,
           'menu' => $types->values(),
            'reviews' => $restaurant->reviews->map(function ($review) {
                return [
                    'image' => $review->customer->profilePicture,
                    'name' => $review->customer->firstname . ' ' . $review->customer->lastname,
                    'rating' => $review->rating,
                    'comment' => $review->comment
                ];
            })
        ];
        $user = Customer::find(Auth::guard('customer-api')->id());
        $followedRestaurants = $user->followed_restaurants ?? [];

        $isFollowed = in_array($id, $followedRestaurants);

        $formattedRestaurant['isFollowed'] = $isFollowed;
        $formattedRestaurant['time_start'] = $openTime;
        $formattedRestaurant['time_end'] = $closeTime;

        return response()->json(['restaurant_details' => $formattedRestaurant]);
    }
    public function followUnfollowRestaurant($restaurantId)
    {
        $user = Customer::find(Auth::guard('customer-api')->id());

        $followedRestaurants = $user->followed_restaurants ?? [];

        if (in_array($restaurantId, $followedRestaurants)) {
            // Unfollow
            $followedRestaurants = array_values(array_diff($followedRestaurants, [$restaurantId]));
        } else {
            // Follow
            $followedRestaurants[] = $restaurantId;
        }

        $user->followed_restaurants = $followedRestaurants;
        $user->save();

        return response()->json(['message' => 'Follow/Unfollow operation successful']);
    }



    public function list_rec_follow()
{
    $customer = Customer::find(Auth::guard('customer-api')->id());
    $followedRestaurantIds = $customer->followed_restaurants ?? [];
    $followings = [];
    if (count($followedRestaurantIds) > 0) {
        $followings = Restaurant::where('status','active')->whereIn('id', $followedRestaurantIds)
            ->with(['images' => function ($query) {
                $query->select('id', 'imageable_id', 'filename')->where('type', 'logo');
            }])->get()->map(function ($restaurant) use ($followedRestaurantIds) {
            $isFollowed = in_array($restaurant->id, $followedRestaurantIds);
            $imageFilename = $restaurant->images->first() ? $restaurant->images->first()->filename : null;
            return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'rating_number' => round($restaurant->rating,1),
                'images' => $imageFilename, // ????? ??? ????? ??????
            ];
        });
    }

    return ['followings' => $followings];
}

    public function search($request)
    {
        $word = $request->input('word');
        if (empty($word)) {
            return 'Please enter a search term';
        }
        $Restaurants = Restaurant::where('status','active')->where('name', 'LIKE', '%' . $word . '%')
            ->orWhereHas('cuisine', function ($query) use ($word) {
                $query->where('name', 'LIKE', '%' . $word . '%')->orWhere('ar_name', 'LIKE', '%' . $word . '%');
            })
            ->orWhereHas('location', function ($query) use ($word) {
                $query->where('state', 'LIKE', '%' . $word . '%');
            })
            ->select('id', 'name','description','ar_description', 'rating')->with(['images' => function ($query) {
                $query->select('imageable_id', 'filename')->where('type', 'logo');
            }])->get();
        $userFollowedRestaurantIds = auth()->user()->followed_restaurants ?? [];
        $followedRestaurants = [];
        $otherRestaurants = [];
        foreach ($Restaurants as $Restaurant) {
            if (in_array($Restaurant->id, $userFollowedRestaurantIds)) {
                $followedRestaurants[] = $Restaurant;
            } else {
                $otherRestaurants[] = $Restaurant;
            }
        }
        $result = [
            'following' => $followedRestaurants,
            'others' => $otherRestaurants,
        ];

        return $result;
    }




public function advansearch($request)
{
    $user = Customer::find(Auth::guard('customer-api')->id());
    $followedRestaurants = $user->followed_restaurants ?? [];
    $userLatitude = $request->latitude;
    $userLongitude = $request->longitude;
    $maxDistance = $request->max_distance; // ??????? ?????? ????????????

    // ????? ???? ?????
    $timeRange = [$request->time_start, $request->time_end];
$restaurants = Restaurant::query() ; 

    if ($request->has('cuisines'))
    // ??? ??????? ?? ????? ??? ????????? ????????
    $restaurants = Restaurant::join('restaurantlocations', 'restaurants.id', '=', 'restaurantlocations.restaurant_id')
    ->where('status','active')->whereHas('cuisine', function ($query) use ($request) {
        $cuisines = $request->input('cuisines');
        // Check if cuisines data is a JSON string and decode it
        if (is_string($cuisines)) {
            $cuisines = json_decode($cuisines, true) ?: [];
        }
        $query->whereIn('name', $cuisines)->orWhereIn('ar_name', $cuisines);
    })
    ->whereBetween('Deposite_value', [$request->deposit_min, $request->deposit_max]);
    
    // Conditionally add reservation date criteria if provided
    if (!Carbon::parse($request->input('reservation_date'))->isToday()) {
        $restaurants = $restaurants->whereHas('reservations', function ($query) use ($request) {
            $query->where('reservation_date', Carbon::parse($request->date))
                  ->where('status', 'scheduled');
        });
    }
    
    // Further refine the search to filter by time if start and end times are provided
    if ( !($request->input('time_start')==null || $request->input('time_end') ==null)) {
        $timeRange = [$request->input('time_start'), $request->input('time_end')];
        $restaurants = $restaurants->whereHas('reservations', function ($query) use ($timeRange) {
            $query->whereBetween('reservation_time', $timeRange)
                  ->where('status', 'scheduled');
        });
    }
    
    // Execute the query to get the results
    $restaurants = $restaurants->get();
    

        if ($restaurants != null){
        $restaurants = $restaurants->map(function ($restaurant) use ($userLatitude, $userLongitude, $maxDistance, $followedRestaurants) {
        
    $distance = $this->calculateDistance($userLatitude, $userLongitude, $restaurant->location->latitude, $restaurant->location->longitude);

    $isWithinDistance = $distance <= $maxDistance;
    $isFollowed = in_array($restaurant->id, $followedRestaurants);

    if ($isWithinDistance) {
        return [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'Deposite_value' => $restaurant->Deposite_value,
            'Deposite_desc' => $restaurant->Deposite_desc,
            'cuisine_name' => $restaurant->cuisine->name,
            'category' => $restaurant->category->name,
            'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
            'ar_cuisine_name' => $restaurant->cuisine->ar_name,
            'ar_category' => $restaurant->category->ar_name,
            'location_text' => $restaurant->location->text,
            'ar_location_text' => $restaurant->location->ar_text,
            'rating_number' => round($restaurant->rating, 1),
            'is_available' => $restaurant->availability == 'available',
            'distance' => $distance,
            'location_latitude' => $restaurant->location->latitude,
            'location_longitude' => $restaurant->location->longitude,
            'location_state' => $restaurant->location->state,
            'images' => $restaurant->images->pluck('filename'),
            'isFollowed' => $isFollowed,
        ];
    }
})->filter()->values();


        }

    return $restaurants;
}





    public function filtersearch($request)
{
    $user = Customer::find(Auth::guard('customer-api')->id());
    $followedRestaurants = $user->followed_restaurants ?? [];
    $longitude = $request->input('longitude');
    $latitude = $request->input('latitude');
    
    // ??????? ????? ????? ?? ?????
    $priceStart = $request->input('price_start');
    $priceEnd = $request->input('price_end');
    $distance = $request->input('distance');
    $nameCuisines = (array) $request->input('name_cuisine', []);
      //section_id : -1 home page
      //section_id : 0 forYou
      //section_id : 1 newOpening 
      //section_id : 2 featured 
      //section_id : 3 offers 
      //section_id : 4 taste 
      //section_id : 5 follwing 
 $query = Restaurant::query();
if($request->section_id=='-1'){ 
  $query = Restaurant::query();
}

elseif($request->section_id=='0'){
$query =Restaurant::where('status','active')->with(['cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->query();
  }
elseif($request->section_id=='1'){
$query = DB::table('images_of_offers')
            ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
            ->select('offers.id', 'images_of_offers.filename')
            ->where('offers.type', 'new_opening')
            ->where('images_of_offers.type', 'cover')
            ->query();
            }
elseif($request->section_id=='2'){
  $query=Restaurant::with(['Cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->query();
  }
elseif($request->section_id=='3'){

  DB::table('images_of_offers')
            ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
            ->select('offers.id', 'images_of_offers.filename')
            ->where('offers.type', 'offer')
            ->where('images_of_offers.type', 'cover')
            ->query();
  }
elseif($request->section_id=='4'){
$query=Restaurant::with(['Cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->query();
           
  }
elseif($request->section_id=='5'){  
  $query =Restaurant::with(['Cuisine', 'images' => function ($query) {
            $query->where('type', 'cover');
        }, 'location'])
            ->query();
            
  }
    if (!empty($nameCuisines)) {
        $query->whereHas('Cuisine', function ($query) use ($nameCuisines) {
            $query->whereIn('name', $nameCuisines)->orWhereIn('ar_name', $nameCuisines);
            
        });
    }else{
    return [
        'restaurants' => [],
    ];
    }

    // ????? ????? ????? ???
    if ($priceStart !== null && $priceEnd !== null) {
        $query->where('deposit', '>=', $priceStart)
              ->where('deposit', '<=', $priceEnd);
        
    }

    $restaurants = $query->with(['Cuisine', 'images' => function ($query) {
        $query->where('type', 'cover');
    }, 'location'])->get();
    
    
    $filteredRestaurants = $restaurants->filter(function ($restaurant) use ($distance, $latitude, $longitude) {
        if ($distance && $latitude && $longitude) {
            $restaurantDistance = $this->calculateDistance($latitude, $longitude, $restaurant->location->latitude, $restaurant->location->longitude);
            return $restaurantDistance <= $distance;
        }
        return true;
    });
    

    $mappedRestaurants = $filteredRestaurants->map(function ($restaurant) use ($followedRestaurants) {
        $isFollowed = in_array($restaurant->id, $followedRestaurants);
        return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'Deposite_value' => $restaurant->Deposite_value,
                'Deposite_desc' => $restaurant->Deposite_desc,
                'cuisine_name' => $restaurant->cuisine->name,
                'category' => $restaurant->category->name,
                'ar_Deposite_desc' => $restaurant->ar_Deposite_desc,
                'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                'ar_category' => $restaurant->category->ar_name,
                'location_text' => $restaurant->location->text,
                'ar_location_text' => $restaurant->location->ar_text,
                'rating_number' => round($restaurant->rating, 1),
                'is_available' => $restaurant->availability == 'available' ? true : false,
                'location_latitude' => $restaurant->location->latitude,
                'location_longitude' => $restaurant->location->longitude,
                'location_state' => $restaurant->location->state,
                'images' => $restaurant->images->pluck('filename'),
                'isFollowed' => $isFollowed,
        ];
    });

    return [
        'restaurants' => $mappedRestaurants,
    ];
}

     
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1.609344); // Convert to meters
    }
    public function review($request, $id)
    {
        $user = Customer::find(Auth::guard('customer-api')->id())->first();
        Reviews::create([
            'customer_id' => Auth::guard('customer-api')->id(),
            'Restaurant_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment ?? 'no comment',
        ]);
        $Restaurant = Restaurant::where('status','active')->where('id', $id)->first();
        $averageRating = Reviews::where('Restaurant_id', $id)->avg('rating');
        $Restaurant->update([
            'rating' => $averageRating,
        ]);
        $user->update([
            'numberOfReviews' => $user->numberOfReviews += 1,
        ]);
        $invitation = Invitation::where([
            'target' => $user->numberOfReviews,
            'type' => 'reviews',
        ])->first();
        if ($invitation) {
            $this->invitationService->generate_promocode_invitation($invitation, $user);
        }
        return $Restaurant;
    }
    public function reviews($id)
    {
        $reviews = Reviews::where('restaurant_id', $id)->get();
        $reviewCount = $reviews->count();

        return [
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
        ];
    }
    // public function nearest_Restaurants()
    // {
    //     $lat = 'fdsfdsfsd';
    //     $lng = 'rwerwewfs';
    //     $currentLocation = [
    //         'latitude' => '3242.4234',
    //         'longitude' => '43242.664554',
    //     ];
    //    $distance="42.4234";
    //     // $nearest_Restaurants = Restaurant::withinDistance($currentLocation['latitude'], $currentLocation['longitude'], $distance)
    //     //     ->with(['images' => function ($query) {
    //     //         $query->where('type', 'cover');
    //     //     }])
    //     //     ->get();
    //       $nearest_Restaurants = Restaurant::get();
    //       //with(['images' => function ($query) {
    //     //         $query->where('type', 'cover');
    //     //     }])
    //     //     ->get();

    //     foreach ($nearest_Restaurants as $cuisine) {
    //         $RestaurantData = [
    //             'id' => $cuisine->id,
    //             'name' => $cuisine->name,
    //             'Restaurants' => []
    //         ];

    //         foreach ($cuisine->Restaurants as $Restaurant) {
    //             $mainImages = [];
    //             foreach ($Restaurant->images as $image) {
    //                 if ($image->type == 'cover') {
    //                     $mainImages[] = $image->filename;
    //                 }
    //             }
    //             $RestaurantData['Restaurants'][] = [
    //                 'id' => $Restaurant->id,
    //                 'name' => $Restaurant->name,
    //                 'deposite' => $Restaurant->deposit,
    //                 'cuisine_name' => $cuisine->name,
    //                 'category' => $Restaurant->category,
    //                 'location_text' => $Restaurant->location->text,
    //                 'rating_number' => $Restaurant->rating,
    //                 'availability' => $Restaurant->availability,
    //                 'distance' => $Restaurant->distance,
    //                 'images' => $mainImages,
    //             ];
    //         }
    //         // $responseData = [];
    //         return $responseData[] = $RestaurantData;
    //       //  $responseData['data'][] = $RestaurantData;
    //     }
    //     return $responseData;
    // }
    public function nearest_Restaurants()
    {
        $lat = '1';
        $nearest_restaurants = Cuisine::with(['restaurants' => function ($query) use ($lat) {
            $query->where('status', 'active'); // Filter by status
            $query->with(['images' => function ($query) {
                $query->where('type', 'cover');
            }]);
            $query->with('Location');
        }])->get();
        
        // Filter out categories with no active restaurants
        $categories = restaurnats_categories::whereHas('Restaurants', function ($query) {
            $query->where('status', 'active');
        })->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'ar_name' => $category->ar_name,
                'icon' => $category->icon,
            ]; 
        });
        
      if($nearest_restaurants != []){
      
        foreach ($nearest_restaurants as $cuisine) {
            $resturantData = [
                'id' => $cuisine->id,
                'name' => $cuisine->name,
                'ar_name' => $cuisine->ar_name,
                'restaurants' => []
            ];
            
            foreach ($cuisine->restaurants as $resturant) {
                $mainImages = [];
                foreach ($resturant->images as $image) {
                    if ($image->type == 'cover') {
                        $mainImages[] = $image->filename;
                    }
                }
                $user = Customer::find(Auth::guard('customer-api')->id());
                $followedRestaurants = $user->followed_restaurants ?? [];
                $isFollowed = in_array($resturant->id, $followedRestaurants);




                $resturantData['restaurants'][] = [
                    'id' => $resturant->id,
                    'name' => $resturant->name,
                    'Deposite_value' => $resturant->Deposite_value,
                    'Deposite_desc' => $resturant->Deposite_desc,
                    'cuisine_name' => $cuisine->name,
                    'category' => $resturant->category->name,
                    'ar_Deposite_desc' => $resturant->ar_Deposite_desc,
                    'ar_cuisine_name' => $cuisine->ar_name,
                    'ar_category' => $resturant->category->ar_name,
                    'location_latitude' => $resturant->location->latitude,
                    'location_longitude' => $resturant->location->longitude,
                    'location_state' => $resturant->location->state,
                    'location_text' => $resturant->location->text,
                    'ar_location_text' => $resturant->location->ar_text,
                    'rating_number' => round($resturant->rating, 1),
                  'is_available' => $resturant->availability == 'available' ? true : false,

                    'images' => $mainImages,
                    'isFollowed' => $isFollowed,
                ];
            }
            $responseData['data'][] = $resturantData;
            
        }
        
        return [
            'categories' => $categories,
            'responseData' => $responseData,
        ];
        }
        else {
        return [
            'categories' => $categories,
            'responseData' => [],
        ];
        }
    }
    public function cuisine_Restaurants($id)
    {
        return Restaurant::where('status','active')->where('cuisine_id', $id)
            ->join('restaurantlocations', 'restaurants.id', '=', 'restaurantlocations.restaurant_id')
            ->with(['cuisine', 'images' => function ($query) {
                $query->where('type', 'cover');
            }, 'location'])
            ->get()
            ->map(function ($restaurant) {
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'Deposite_value' => $resturant->Deposite_value,
                    'Deposite_desc' => $resturant->Deposite_desc,
                    'cuisine_name' => $restaurant->cuisine->name,
                    'category' => $restaurant->category->name,
                    'ar_Deposite_desc' => $resturant->ar_Deposite_desc,
                    'ar_cuisine_name' => $restaurant->cuisine->ar_name,
                    'ar_category' => $restaurant->category->ar_name,
                    'location_text' => $restaurant->location->text,
                    'rating_number' =>round($restaurant->rating, 1),
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
