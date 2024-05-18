<?php

namespace App\Http\Controllers\Api;

use Hash;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Events\NotificationEvent;
use App\Models\offer;
use App\Models\Table;
use App\Models\Cuisine;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\images_offers;
use App\Models\res_prompcodes;
use App\Models\Promocode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\New_Reservation;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\ApiReservationsRepositoryInterface;

class ApiReservationsController extends BaseController
{
    protected $ApiReservationsRepository;

    public function __construct(ApiReservationsRepositoryInterface $ApiReservationsRepository)
    {
        $this->ApiReservationsRepository = $ApiReservationsRepository;
    }

    public function reversation(Request $request, $res_id) //id reservation
    {
        $rules = [
            'payment_method' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
            ], 403);
        }
 
        $reservation = $this->ApiReservationsRepository->reversation($request, $res_id);


        if (!$reservation) {
            return response()->json([
                'status' => false,
                'reservation_details' => 'no reservation'
            ],405);
        } elseif (!empty($reservation['promocode']) && $reservation['promocode'] != null) {
                // Assume $user is already authenticated and available
                $user = Auth::user();

                // Decode the JSON-encoded promocodes or set to an empty array if null
                 if (is_string($user->promocodes)) {
                      $userPromoIDs = json_decode($user->promocodes, true);
                      // If json_decode returns null or not an array, initialize to empty array
                      if (!is_array($userPromoIDs)) {
                          $userPromoIDs = (array) $user->promocodes;
                      }
                  } else {
                      // If it's not a string, directly use it if it's an array, otherwise default to empty array
                      $userPromoIDs = is_array($user->promocodes) ? $user->promocodes : (array) $user->promocodes;
                  }
                  
                  // Fetch promocodes from the database based on the IDs
                  $userPromocodes = Promocode::whereIn('id', $userPromoIDs)
                      ->pluck('code')  // Retrieve only the 'code' column
                      ->all();

                // Check if the reservation promocode exists in the user's promocodes
                if (in_array($reservation['promocode'], $userPromocodes)) {
                    // Retrieve the restaurant, ensuring it exists
                    $restaurant = Restaurant::findOrFail($reservation['Restaurant_id']);

                    // Query to check if the promo code is valid for this restaurant
                    $validPromo = \DB::table('restaurantpromoCodes')
                        ->where('restaurant_id', $restaurant->id)
                        ->whereExists(function ($query) use ($reservation) {
                            $query->select(\DB::raw(1))
                                  ->from('promotionalcodes')
                                  ->where('code', $reservation['promocode'])
                                  ->whereColumn('id', 'restaurantpromoCodes.promocode_id');
                        })
                        ->first();

                    if ($validPromo) {
                        // Fetch the corresponding promotional code details
                        $promo = Promocode::where('code', $reservation['promocode'])->first();

                        if ($promo) {
                            // Calculate the discounted value
                            $discountAmount = ($restaurant->Deposite_value * $promo->discount) / 100;

                            // Calculate the discounted value by subtracting the discount amount from the deposit
                            $discountedValue = $restaurant->Deposite_value - $discountAmount;

                            // Return response with discount applied
                            return response()->json([
                                'status' => true,
                                'reservation_details' => $reservation,  // Ensure this variable is correctly defined and populated
                                'discounted_value' => $discountedValue
                            ]);
                        }else{
                            return response()->json([
                        'status' => false,
                        'message' => 'Promo code is not valid or failed to apply discount.'
                    ],406);
                        }
                    }
                }


            }
            

            return response()->json([
                'status' => true,
                'message' => 'Invalid or inapplicable promo code',
                'reservation_details' => $reservation
            ]);
        
    }

    public function my_reservations()
    {
    $today = Carbon::today();
        $my_reservations = Reservation::where('customer_id', Auth::guard('customer-api')->id())
            ->with(['Restaurant' => function ($query) {
                $query->select('id', 'name', 'time_start', 'time_end', 'Deposite_value', 'Deposite_desc', 'rating')
                    ->with('location', 'images');
            }])->with('table')->get();
        $upcoming = $my_reservations->filter(function ($reservation) use ($today) {
        //dd(['id' => $reservation->id, 'date' => $reservation->reservation_date, 'status'=> $reservation->status, 'today'=>$today->toDateString()]);
    $reservationDate = Carbon::parse($reservation->reservation_date);
    return ($reservationDate->isToday() || $reservationDate->isAfter($today)) && ($reservation->status == 'next' || $reservation->status == 'rejected');
})->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'id_Restaurant' => optional($reservation->Restaurant)->id,
                'speacial_request' => $reservation->speacial_request,
                'reservation_time' => $reservation->reservation_time,
                'reservation_time_end' => $reservation->reservation_time_end,
                'reservation_date' => $reservation->reservation_date,
                'party_size' => $reservation->party_size,
                'status' => $reservation->status,
                'name_Restaurant' => optional($reservation->Restaurant)->name,
                'location' => optional(optional($reservation->Restaurant)->location)->text,
                'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
            ];
        })->values();
        $history = $my_reservations->filter(function ($reservation) use ($today) {
        
    $reservationDate = Carbon::parse($reservation->reservation_date);
    return $reservationDate->isBefore($today) || $reservation->status == 'cancelled';
})->map(function ($reservation) {

            return [
                'id' => $reservation->id,
                'speacial_request' => $reservation->speacial_request,
                'reservation_time' => $reservation->reservation_time,
                'reservation_time_end' => $reservation->reservation_time_end,
                'reservation_date' => $reservation->reservation_date,
                'party_size' => $reservation->party_size,
                'status' => $reservation->status,
                'name_Restaurant' => optional($reservation->Restaurant)->name,
                'location' => optional(optional($reservation->Restaurant)->location)->text,
                'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
            ];
        })->values();
        $pending = $my_reservations->filter(function ($reservation) use ($today) {
        
    $reservationDate = Carbon::parse($reservation->reservation_date);
     return ($reservationDate->isToday() || $reservationDate->isAfter($today)) && $reservation->status == 'pending';
})->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'id_Restaurant' => optional($reservation->Restaurant)->id,
                'speacial_request' => $reservation->speacial_request,
                'reservation_time' => $reservation->reservation_time,
                'reservation_time_end' => $reservation->reservation_time_end,
                'reservation_date' => $reservation->reservation_date,
                'party_size' => $reservation->party_size,
                'status' => $reservation->status,
                'name_Restaurant' => optional($reservation->Restaurant)->name,
                'location' => optional(optional($reservation->Restaurant)->location)->text,
                'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
            ];
        })->values();
        
        return $this->sendResponse(['history' => $history, 'upcoming' => $upcoming, 'pending' => $pending], 'promocodes and invitations');
        
    }
    public function reversation_details($id)
    {
        $reservation = $this->ApiReservationsRepository->reversation_details($id);

        return response()->json([
                'status' => true ,
                'reservation_details'  => $reservation
            ]);
    }
    public function reversation_cancel($id)
    {
        $status = $this->ApiReservationsRepository->reversation_cancel($id);
        
        if(!$status){
        return [
                'status' => false,
                'message' => 'Reservation not found',
            ]; 
        }
        return [
                'status' => true,
                'message' => 'Reservation cancelled successfully with refunder',
            ]; 
    }
    public function reversation_reject($id)
    {
        $status = $this->ApiReservationsRepository->reversation_reject($id);
        
        if(!$status){
        return [
                'status' => false,
                'message' => 'Reservation not found',
            ]; 
        }
        return [
                'status' => true,
                'message' => 'Reservation rejected successfully with refunder',
            ]; 
    }

    public function available_reservations(Request $request, $id)
    {
        $reservations = $this->ApiReservationsRepository->available_reservations($request->date, $id);

        if ($reservations->isNotEmpty()) {
            return $this->sendResponse($reservations, 'reservation_details');
        } else {
            return response()->json(['message' => 'not found'], 404);
        }
    }

    public function available_capacity(Request $request, $id)
    {
        $times = $this->ApiReservationsRepository->available_reservations($request, $id);
        return $this->sendResponse($times, 'available_capacity');
    }
    public function available_times_res(Request $request, $id)
    {
        $rules = [
            'date' => 'required|date|after_or_equal:' . date('Y-m-d'),
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
            ], 422);
        }
        $times = $this->ApiReservationsRepository->available_times_res($request, $id);
        return $this->sendResponse($times, 'available_times_res');
    }



    public function available_tables(Request $request, $id)
    {
        $tables = Table::where('Restaurant_id',$id)->get();
        // Your existing validation and logic...
        
        $organizedData = [];

        foreach ($tables as $table) {
            if ($table) {
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
        }

        // Reformatting the array to have clearer keys
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

        return $this->sendResponse($formattedData, 'availableTables');
    }

 public function restaurant_info($id, Request $request)
     {

         $restaurant = Restaurant::findOrFail($id); // Find the restaurant or fail

         // Now, attempt to get the first logo image for the restaurant
         $restaurantLogo = $restaurant->images()
             ->where('type', 'logo')
             ->first(); // This will get the first image that matches the type 'logo'

         $restaurantLogoFilename = $restaurantLogo ? $restaurantLogo->filename : 'default-logo.jpg';

         $userId = Auth::id(); // Get authenticated user's ID
         $Date =$request->query('reservation_date');
         
         $promoCodes = res_prompcodes::where('restaurant_id', $id)
             ->whereHas('promocode', function ($query) use ($Date, $userId) {
                 $query->where('start_date', '<=', $Date)
                     ->where('end_date', '>=', $Date)
                     ->where('status', 'active')
                     ->where(function ($q) use ($userId) {
                         $q->whereJsonContains('users_ids', $userId)
                             ->orWhereNull('users_ids');
                     });
             })
             ->with(['promocode:id,code,discount'])
             ->get()
             ->map(function ($restaurantPromoCode) {
                 return $restaurantPromoCode->promocode;
             });


         return response()->json([
             'restaurant_logo' => $restaurantLogoFilename,
         'restaurant_taxes' => $restaurant->taxes,
             'promoCodes' => $promoCodes,
         ]);

     }



    public function promocodes_available($id)
    {
        $userId = Auth::id(); // Get authenticated user's ID
        $currentDate = Carbon::today()->toDateString(); // Current date
        $promoCodes = res_prompcodes::where('restaurant_id', $id)
            ->whereHas('promocode', function ($query) use ($currentDate, $userId) {
                $query->where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->where('status', 'active')
                    ->where(function ($q) use ($userId) {
                        $q->whereJsonContains('users_ids', $userId)
                            ->orWhereNull('users_ids');
                    });
            })
           ->with(['promocode:id,code,discount'])
            ->get()
            ->map(function ($restaurantPromoCode) {
                return $restaurantPromoCode->promocode;
            });

        return $this->sendResponse($promoCodes, 'promoCodes');
    }
    public function reservation_details($id)
{
$reservation = Reservation::findOrFail($id);
if($reservation->status == 'next'){
    $reservation = Reservation::where('id', $id)
        ->with(['restaurant' => function ($query) {
            $query->select('id', 'name', 'deposit','taxes')
                ->with(['location' => function ($query) {
                    $query->select('id', 'restaurant_id', 'text');
                }]);
        }, 'table' => function ($query) {
            $query->select('id', 'restaurant_id', 'type', 'location', 'size');
        }])->first();
}else{
$reservation = Reservation::where('id', $id)
        ->with(['restaurant' => function ($query) {
            $query->select('id', 'name', 'deposit','taxes')
                ->with(['location' => function ($query) {
                    $query->select('id', 'restaurant_id', 'text');
                }]);
        }])->first();
}
    if (!$reservation) {
        return $this->sendError('Reservation not found.', 404);
    }
     $reservationEndTime = Carbon::parse($reservation->reservation_time_end)->format('H:i');
        $reservation->reservation_time_end = $reservationEndTime;
    
    // Check if user information is null and fetch from Auth (assuming user is logged in)
    if (is_null($reservation->first_name) || is_null($reservation->phone_number) || is_null($reservation->last_name)) {
        $user = Auth::user(); // Assuming you have user authentication and token setup
        $reservation->first_name = $user->firstname;
        $reservation->last_name = $user->lastname;
        $reservation->phone_number = $user->phone;
    }
$restaurantLogo = $reservation->restaurant->images->first() ? $reservation->restaurant->images->first()->filename : 'default-logo.jpg';
if($reservation->status == 'next'){
    $response = [
        'restaurant_name'=>$reservation->restaurant->name,
        'restaurant_taxes' => $reservation->restaurant->taxes,
        'restaurant_logo' => $restaurantLogo,
        'reservation_date' => $reservation->reservation_date,
        'reservation_time' => $reservation->reservation_time,
        'reservation_time_end' => $reservationEndTime,
        'party_size' => $reservation->party_size,
        'table_type' => $reservation->table->type,
        'table_location' => $reservation->table->location,
        'table_size' => $reservation->table->size,
        'first_name' => $reservation->first_name,
        'last_name' => $reservation->last_name,
        'phone_number' => $reservation->phone_number,
        'comment' => $reservation->comment,
        'special_request' => $reservation->special_request,
        'payment_method' => $reservation->payment_method,
        'promocode' => $reservation->promocode,
        'deposit' => $reservation->restaurant->deposit,
    ];
    }else{
    $response = [
        'restaurant_name'=>$reservation->restaurant->name,
        'restaurant_taxes' => $reservation->restaurant->taxes,
        'restaurant_logo' => $restaurantLogo,
        'reservation_date' => $reservation->reservation_date,
        'reservation_time' => $reservation->reservation_time,
        'reservation_time_end' => $reservationEndTime,
        'party_size' => $reservation->party_size,
        'table_type' => null,
        'table_location' => null,
        'table_size' => null,
        'first_name' => $reservation->first_name,
        'last_name' => $reservation->last_name,
        'phone_number' => $reservation->phone_number,
        'comment' => $reservation->comment,
        'special_request' => $reservation->special_request,
        'payment_method' => $reservation->payment_method,
        'promocode' => $reservation->promocode,
        'deposit' => $reservation->restaurant->deposit,
    ];
    }

    return $this->sendResponse($response, 'reservation_details');
}
    public function reservation_delete($id)
    {
     Reservation::where('id',$id)->update(['status'=>'deleted']);
         return $this->sendResponse(true, 'deleted');
    
    
    }
    
}
