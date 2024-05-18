<?php
namespace App\Http\Controllers\Api;
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
use App\Models\Restaurant;
class UserController extends BaseController
{
     public function reversation(Request $request,$id) //id reservation
    {
       $reservation=Reservation::where('id',$id)->first();
       $res=Restaurant::where('id',$reservation->Restaurant_id)->first();
       $staff=User::where('id',$res->user_id)->first();
       $reservationTime = $reservation->reservation_time instanceof Carbon
       ? $reservation->reservation_time
       : new Carbon($reservation->reservation_time);
        $reservationTimeEnd = $reservationTime->addHours($reservation->duration);
       $reservation->update([
          'customer_id'=>Auth::guard('customer-api')->id(),
          'speacial_request'=>'-',
          'status'=>'next',
          'reservation_time_end'=> $reservationTimeEnd,
       ]);
      $conflictingReservations = Reservation::where('table_id', $reservation->table_id)
     ->where('reservation_date', $reservation->reservation_date)
     ->where(function ($query) use ($reservation) {
     $query->whereBetween('reservation_time', [$reservation->reservation_time, $reservation->reservation_time_end])
     ->orWhereBetween('reservation_time_end', [$reservation->reservation_time, $reservation->reservation_time_end]);
     })->where('id', '!=', $id) ->get();
       $conflictingReservations->each->delete();
       $customer=Customer::where('id',Auth::guard('customer-api')->id())->first();
       $staff->notify(new New_Reservation($customer,$reservation));
       return $this->sendResponse($reservation,'reservation_details');
    }
    public function my_reservations()
    {      $my_reservations=Reservation::where('customer_id',Auth::guard('customer-api')->id())
         ->with(['Restaurant' => function ($query) {
         $query->select('id','name','time_start','time_end','deposit','rating')
         ->with('location','images');}])->with('table')->get();
         $upcoming = $my_reservations->where('status','next')->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'customer_id' => $reservation->customer_id,
                'table_id' => $reservation->table_id,
                'Restaurant_id' => $reservation->Restaurant_id,
                'speacial_request' => $reservation->speacial_request,
                'actual_price' => $reservation->actual_price,
                'reservation_time' => $reservation->reservation_time,
                'reservation_time_end' => $reservation->reservation_time_end,
                'reservation_date' => $reservation->reservation_date,
                'party_size' => $reservation->party_size,
                'status' => $reservation->status,
                'created_at' => $reservation->created_at,
                'updated_at' => $reservation->updated_at,
                'capacity' => optional($reservation->table)->capacity,
                'name_Restaurant' => optional($reservation->Restaurant)->name,
                'location' => optional(optional($reservation->Restaurant)->location)->text,
                'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
            ];
        });
        $history=$my_reservations->where('status','next')->map(function ($reservation) {
         return [
             'id' => $reservation->id,
             'customer_id' => $reservation->customer_id,
             'table_id' => $reservation->table_id,
             'Restaurant_id' => $reservation->Restaurant_id,
             'speacial_request' => $reservation->speacial_request,
             'actual_price' => $reservation->actual_price,
             'reservation_time' => $reservation->reservation_time,
             'reservation_time_end' => $reservation->reservation_time_end,
             'reservation_date' => $reservation->reservation_date,
             'party_size' => $reservation->party_size,
             'status' => $reservation->status,
             'created_at' => $reservation->created_at,
             'updated_at' => $reservation->updated_at,
             'capacity' => optional($reservation->table)->capacity,
             'name_Restaurant' => optional($reservation->Restaurant)->name,
             'location' => optional(optional($reservation->Restaurant)->location)->text,
             'image_logo' => optional($reservation->Restaurant->images->where('type', 'logo')->first())->filename
         ];
     });
         return $this->sendResponse(['upcoming'=>$upcoming,'history'=>$history],'my_reservations');
    }
    public function reversation_details($id)
    {
      $reservation=Reservation::where('id',$id)->with(['Restaurant' => function ($query) {
         $query->select('id','name','time_start','time_end','deposit','rating')->with('location','images');}])->get();
      return $this->sendResponse($reservation,'reversation_details');
    }
    public function reversation_cancel($id)
    {
      
   
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return $this->sendError('Reservation not found.');
        }
    
        $restaurant = $reservation->Restaurant;
    
        if (!$restaurant) {
            return $this->sendError('Restaurant not found.');
        }
    
        // Calculate the time remaining to start the reservation from now
        $currentTime = now();
        $reservationStartTime = Carbon::parse($reservation->reservation_time);
        $timeRemainingInMinutes = $reservationStartTime->diffInMinutes($currentTime);
   
        preg_match('/^([^,]+),/', $restaurant->cancellition_policy, $matches);
        $canceler = trim($matches[1]);    
   
        preg_match('/^([^,]+),/', $restaurant->refund_policy, $value);
         $refunder = trim($value[1]);   
   
        // Compare the time remaining with the cancellation policy
        if ($timeRemainingInMinutes >= $refunder * 60) {
            
            return $this->sendResponse($reservation, 'Reservation cancelled successfully with refunder');
        }
         else 
        {
           return $this->sendResponse($reservation, 'Reservation cancelled successfully without refunder');
        }
   
        if ($timeRemainingInMinutes >= $canceler * 60) {
            
           return $this->sendResponse($reservation, 'Reservation cancelled successfully with refunder');
       }
        else 
       {
          return $this->sendResponse($reservation, 'Reservation cancelled successfully without refunder');
       }
   
    }
      
   public function available_reservations(Request $request,$id)
   {
      $reservations=Reservation::where(
         [
            'Restaurant_id'=>$id,
            'reservation_date'=>$request->date,
            'status'=>'scheduled',
         ])->get();
         if($reservations)
         {
            $reservations=$reservations->reservation_time;
            return $this->sendResponse($reservations,'reservation_details');
         }
         else
         {
            return'not found';
         }
   }
   public function available_capacity(Request $request,$id)
   {
    // request : date - return : reservations(id,time)-times_availables
    $times=Reservation::where([
        'reservation_date'=>$request->date,
        'Restaurant_id'=>$id,
        'status'=>'scheduled',
      ])->get(['id', 'party_size']);
      return $this->sendResponse($times,'available_capacity');
   }
   public function available_times_res(Request $request,$id)  //id res
   {
      // request : date - numPersone - return : reservations(id,time)-times_availables
      $times=Reservation::where([
        'reservation_date'=>$request->date,
        //'reservation_time'=>$request->reservation_time,
        'party_size'=>$request->number,
        'Restaurant_id'=>$id,
        'status'=>'scheduled',
      ])->get(['id', 'reservation_time']);
      return $this->sendResponse($times, 'available_times_res');
   }


  

}