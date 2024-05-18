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
use App\Models\images_offers;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\InvitationService;
use App\Notifications\New_Reservation;
use App\Http\Controllers\BaseController;
use App\Repositories\Interfaces\ApiReservationsRepositoryInterface;
class ApiReservationsRepository implements ApiReservationsRepositoryInterface
{
    protected $invitationService;
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function reversation($request, $res_id)
    {
        //   $id:res comment  payment  promocode  size location  type  adult  children  date  time
        //   first_name  last_name  phone_number
        
        $reservation = Reservation::where([
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'Restaurant_id' => $res_id,
            'status' => 'scheduled',
        ])->orwhere([
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'Restaurant_id' => $res_id,
            'status' => 'cancelled'])
            ->orwhere([
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'Restaurant_id' => $res_id,
            'status' => 'rejected',
            ])
            ->first();
            
        if($reservation != null){
        $res = Restaurant::where('id', $res_id)->first();
        $staff = User::where('id', $res->user_id)->first();
        $user = Auth::user();
        $time = new \DateTime($request->time);

        // Add 30 minutes to the time
        $time->modify("+{$request->duration} minutes");
        
        
        // Format the new time as 'H:i:s'
        $endTime = $time->format('H:i:s');
        $reservationTime = $reservation->reservation_time instanceof Carbon
            ? $reservation->reservation_time
            : new Carbon($reservation->reservation_time);
        $reservationTimeEnd = $reservationTime->addHours($reservation->duration);
        $reservation->update([
            'customer_id' => Auth::guard('customer-api')->id(),
            'speacial_request' => $request->comment ?? '-',
            'reservation_time_end' => $reservationTimeEnd,
            'payment_method' => $request->payment_method,
            'promocode' => $request->promocode ?? '-',
            'party_size' => $request->children + $request->adult,
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'reservation_time_end' => $endTime,
            'duration' => $request->duration,
            'phone_number' => $request->customer_phone ?? $user->phone_number,
            
        ]);
        
       
        //$conflictingReservations->each->delete();
        $user->update([
            'numberOfReservations'=>$user->numberOfReservations+=1,
        ]);
        $invitation=Invitation::where([
            'target'=>$user->numberOfReservations,
            'type'=>'reservations',
        ])->first();
        if($invitation)
        {
            $this->invitationService->generate_promocode_invitation($invitation, $user);
        }
        
        return  $reservation->only(
            'id',
            'customer_id',
            'table_id',
            'Restaurant_id',
            'reservation_time',
            'reservation_time_end',
            'duration',
            'reservation_date',
            'payment_method',
            'status',
            'promocode'
        );
        }else{
        return false;
        }
    }
    
    public function my_reservations()
    {
        $my_reservations = Reservation::where('customer_id', Auth::guard('customer-api')->id())
            ->with(['Restaurant' => function ($query) {
                $query->select('id', 'name', 'time_start', 'time_end', 'deposit', 'rating')
                    ->with('location', 'images');
            }])->with('table')->get();
        $upcoming = $my_reservations->where('status', 'next')->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'id_Restaurant' => optional($reservation->Restaurant)->id,
                # 'customer_id' => $reservation->customer_id,
                # 'table_id' => $reservation->table_id,
                # 'Restaurant_id' => $reservation->Restaurant_id,
                'speacial_request' => $reservation->speacial_request,
                #  'actual_price' => $reservation->actual_price,
                'reservation_time' => $reservation->reservation_time,
                'reservation_time_end' => $reservation->reservation_time_end,
                'reservation_date' => $reservation->reservation_date,
                'party_size' => $reservation->party_size,
                'status' => $reservation->status,

                'name_Restaurant' => optional($reservation->Restaurant)->name,
                'location' => optional(optional($reservation->Restaurant)->location)->text,
                'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
            ];
        });
        $history = $my_reservations->where('status', 'cancelled')->map(function ($reservation) {
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
        });
        return ['upcoming' => $upcoming, 'history' => $history];
    }
    
    public function reversation_details($id)
    {
          $reservation = Reservation::where('id', $id)
       ->with([
           'restaurant' => function ($query) {
               $query->select('id', 'name')
                      ->with(['images' => function ($query) {
                         $query->where('type', 'logo');
                     }]);
           },
           'user' => function ($query) {
               $query->select('id', 'firstname', 'lastname', 'phone');
           }
       ])
       ->first();

    if (!$reservation) {
       return response()->json(['message' => 'Reservation not found'], 404);
   }

   $restaurantLogo = $reservation->restaurant->images->first() ? $reservation->restaurant->images->first()->filename : 'default-logo.jpg';

   return response()->json([
        'restaurant_name' => $reservation->restaurant->name,
       'restaurant_taxes' =>$reservation->restaurant->taxes,
       'restaurant_logo' => $restaurantLogo,
       'image' => $reservation->restaurant->images->first()->filename ?? 'default-logo.jpg', // This line seems redundant now.
       'reservation' => [
           'date' => $reservation->reservation_date,
            'time_start' => $reservation->reservation_time,
           'time_end' => $reservation->reservation_time_end,
           'party_size' => $reservation->party_size
       ],
       'table' => $reservation->table->only(['size', 'location', 'type']),
       'customer' => $reservation->user->only(['firstname', 'lastname', 'phone']),
       'comments' => $reservation->speacial_request,
       'payment_method' => $reservation->payment_method,
       'price' => $reservation->actual_price,
    ]);

    }

    public function reversation_cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if (!$reservation) {
            return false;
        }
        $restaurant = $reservation->Restaurant;
        if (!$restaurant) {
            return false;
        }
       
        
        return true;
    }
     
    public function reversation_reject($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if (!$reservation) {
            return false;
        }
        $restaurant = $reservation->Restaurant;
        if (!$restaurant) {
            return false;
        }
       
      return true;
    }

    public function available_reservations($request, $id)
    {
        $reservations = Reservation::where(
            [
                'Restaurant_id' => $id,
                'reservation_date' => $request->date,
                'status' => 'scheduled',
            ]
        )->orwhere([
          'Restaurant_id' => $id,
          'reservation_date' => $request->date,
          'status' => 'cancelled',
        ])->get();

        return $reservations;
    }
    public function available_capacity($request, $id)
    {
        $times = Reservation::where([
            'reservation_date' => $request->date,
            'Restaurant_id' => $id,
            'status' => 'scheduled',
        ])->get(['id', 'party_size']);
        return $times;
    }
   public function available_times_res($request, $id)
{
    // Check if the date is today
    $reservationDate = new \DateTime($request->date);
    $dayOfWeek = strtolower($reservationDate->format('D'));
    $times = times::where('Restaurant_id', $id)->first();
    if($times->{$dayOfWeek . '_from'} == null){
    return [];
    }
    if ($request->date === now()->format('Y-m-d')) {
    $reservations = Reservation::where('Restaurant_id', $id)
    ->where('reservation_date', $request->date)
    ->whereTime('reservation_time', '>', now()->addHours(3)->format('H:i:s'))
    ->where(function ($query) {
        $query->where('status', 'scheduled')
              ->orWhere('status', 'cancelled')
              ->orWhere('status', 'rejected');
    })
    ->orderBy('reservation_time')
    ->get(['reservation_time']);

} else {
    $reservations = Reservation::where('Restaurant_id', $id)
    ->where('reservation_date', $request->date)
    ->where(function ($query) {
        $query->where('status', 'scheduled')
              ->orWhere('status', 'cancelled')
              ->orWhere('status', 'rejected');
    })
    ->orderBy('reservation_time')
    ->get(['reservation_time']);

}

$times = $reservations->pluck('reservation_time')->unique()->values()->all();

return $times;

}
    public function delete_reservation($id)
    {
    
    
    }
}
