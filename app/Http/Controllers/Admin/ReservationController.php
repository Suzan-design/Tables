<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\ApiReservationsController;
use App\Models\CancelInvoice;
use App\Models\Notification;
use App\Services\MTNEPaymentService;
use Carbon\Carbon;
use App\Models\Table;
use App\Models\times;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $mtnPaymentService;
    protected $apiReservationsController;

    public function __construct(MTNEPaymentService $mtnPaymentService, ApiReservationsController $apiReservationsController)
    {
        $this->mtnPaymentService = $mtnPaymentService;
        $this->apiReservationsController = $apiReservationsController;
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }
    
    public function admin_records_reservations($id)
    {
        $Restaurant=Restaurant::where('id',$id)->first();
        $times = times::where('Restaurant_id', $Restaurant->id)->get();
        return view('staff.Reservations.records', compact('times', 'Restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $reservationTime = date('H:i', strtotime($request->reservation_time)); // Format time as HH:MM
            Reservation::create([
                'table_id' => $request->table_id,
                'Restaurant_id' => $request->Restaurant_id,
                'reservation_time' => $reservationTime,
                'reservation_date' => $request->reservation_date,
            ]);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }

    public function accept(Request $request)
    {
    if(auth()->user()->roleName=='admin'){
            abort(403);
        }
        $reservation = Reservation::findOrFail($request->res_id);
        $reservation->update([
            'status' => 'next',
            'table_id' => $request->table_id
            
        ]);
        $today = Carbon::today();
        Notification::create([
            'title' => 'Reserve',
            'ar_title' => 'حجز طاولة',
            'description' => 'You are reserved successfully',
            'ar_description' => 'تم حجز الطاولة بنجاح',
            'customer_id' => $reservation->customer_id,
            'date' => $today
        ]);
        $notificationController = new \App\Http\Controllers\Api\NotificationsController();
        $notificationController->sentNotification(Auth::id(), 'Reserve', 'You are reserved successfully','حجز الطاولة','تم حجز الطاولة بنجاح');
        return redirect()->back();
    }
    public function reject($id)
    {
    if(auth()->user()->roleName=='admin'){
            abort(403);
        }
        $reservation = Reservation::findOrFail($id);
        
        $paymentResponse = $this->mtnPaymentService->initiateRefund($reservation->mtn_invoice_id);

        DB::beginTransaction();
        if ($paymentResponse[0] === true) {
            $cancelReservationResponse = $this->apiReservationsController->reversation_reject($id);
            if ($cancelReservationResponse['status'] === true) {
                $confirmResult = $this->mtnPaymentService->confirmRefund($paymentResponse[2], $paymentResponse[1]);
                if ($confirmResult) {
                    DB::commit();
                    CancelInvoice::create([
                        'invoice_id' => $reservation->invoice_id
                    ]);
                    $reservation->update([
                        'status' => 'rejected'
                    ]);
                    $today = Carbon::today();
                    Notification::create([
                        'title' => 'Reject request',
                        'ar_title' => 'رفض الطلب',
                        'description' => 'Your request is rejected',
                        'ar_description' => 'تم رفض الطلب المقدم من قبلك',
                        'customer_id' => $reservation->customer_id,
                        'date' => $today
                    ]);
                    $notificationController = new \App\Http\Controllers\Api\NotificationsController();
                    $notificationController->sentNotification(Auth::id(), 'reject request', 'Your request is rejected','رفض الطلب','تم رفض الطلب المقدم من قبلك');
                    return redirect()->back();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'something wrong in confirm refund'
                    ]);
                }
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'something wrong in reject Reservation'
                ]);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'something wrong in Initial refund',
                'error' => $paymentResponse[1]
            ]);
        }

    }

    public function generate_table_reservations(Request $request)
    {
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $res = Restaurant::where('id',$request->Restaurant_id)->first();
        
        $times = DB::table('restaurantoperatinghours') // Assuming 'times' is the table where working hours are stored
            ->where('Restaurant_id', $request->Restaurant_id)
            ->first();
        $startDate = new \DateTime($times->date_start);

        $endDate = new \DateTime($times->date_end);
        $endDate = $endDate->modify('+1 day'); // Include the end date in the loop
        Reservation::where('table_id','!=',null)->where('Restaurant_id', $request->Restaurant_id)->delete();
        for ($reservationDate = $startDate; $reservationDate < $endDate; $reservationDate->modify('+1 day')) {

            $dayOfWeek = strtolower($reservationDate->format('D'));

            // Dynamic variable names to access the right attributes
            if($times->{$dayOfWeek . '_from'} == null){
                continue;
            }
            $openTime = strtotime($times->{$dayOfWeek . '_from'});

            $closeTime = strtotime($times->{$dayOfWeek . '_to'});
            // Check if the restaurant is open on the given day and time

            $duration = $request->duration; // Duration in minutes

            // Iterate over the time range from open to close time
            for ($startTime = $openTime; $startTime + (30 * 60) <= $closeTime; $startTime += (30 * 60)) {
                $endTime = $startTime + (30 * 60); // Calculate end time for the current slot
                $reservation = Reservation::where('table_id','!=',null)->where('Restaurant_id', $request->Restaurant_id)->first();
                if ($reservation) {
                  continue;
                }
                // Create each reservation within the time frame
                Reservation::create([
                    'Restaurant_id' => $request->Restaurant_id,
                    'duration' => 30,
                    'reservation_time' => date('H:i', $startTime),
                    'reservation_time_end' => date('H:i', $endTime),
                    'reservation_date' => $reservationDate,
                ]);
            }

        }
        DB::commit();
        return redirect()->back();
    }
    
    public function staff_generate_table_reservations($id)
    {
        
        $times = DB::table('restaurantoperatinghours') // Assuming 'times' is the table where working hours are stored
            ->where('Restaurant_id', $id)
            ->first();
            $res = Restaurant::where('id',$id)->first();
            
        $startDate = new \DateTime($times->date_start);

        $endDate = new \DateTime($times->date_end);
        $endDate = $endDate->modify('+1 day'); // Include the end date in the loop
        Reservation::where('table_id',null)->where('Restaurant_id', $id)->delete();
        for ($reservationDate = $startDate; $reservationDate < $endDate; $reservationDate->modify('+1 day')) {

            $dayOfWeek = strtolower($reservationDate->format('D'));

            // Dynamic variable names to access the right attributes
            if($times->{$dayOfWeek . '_from'} == null){
                continue;
            }
            $openTime = strtotime($times->{$dayOfWeek . '_from'});

            $closeTime = strtotime($times->{$dayOfWeek . '_to'});
            // Check if the restaurant is open on the given day and time

            $duration = 30; // Duration in minutes

            // Iterate over the time range from open to close time
            for ($startTime = $openTime; $startTime + (30 * 60) <= $closeTime; $startTime += (30 * 60)) {
                $endTime = $startTime + (30 * 60); // Calculate end time for the current slot
                $reservation = Reservation::where('table_id','!=',null)->where('Restaurant_id', $id)->first();
                if ($reservation) {
                  continue;
                }
                // Create each reservation within the time frame
                Reservation::create([
                    'Restaurant_id' => $id,
                    'duration' => 30,
                    'reservation_time' => date('H:i', $startTime),
                    'reservation_time_end' => date('H:i', $endTime),
                    'reservation_date' => $reservationDate,
                ]);
            }

        }
        DB::commit();
        return redirect()->back();
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::where('id', $id)->with('user', 'table')->first();
        return view('Admin.Reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function record_update(Request $request, $res_id)
{
    $validator = Validator::make($request->all(), [
        'new_time_start' => 'required|date_format:H:i',
        'new_time_end' => 'required|date_format:H:i|after:new_time_start',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    
    $restaurant = Restaurant::findOrFail($res_id);
    $start_date = $restaurant->Activation_start;
    $end_date = $restaurant->Activation_end;
    try {
        DB::beginTransaction();

        // Fetch the operating hours record
        $time = times::where('id', $request->id)->first();
        
        if (!$time) {
            throw new \Exception('Invalid ID, record not found.');
        }
        
        // Determine which day to update
        $day = $request->dayofweek;
        $fromField = $day . '_from';
        $toField = $day . '_to';

        // Get all dates for the specified day of the week between start_date and end_date
        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            (new \DateTime($end_date))->modify('+1 day')
        );

        $datesToUpdate = [];
        foreach ($period as $date) {
        
            if (strtolower($date->format('D')) == $day) {
                $datesToUpdate[] = $date->format('Y-m-d');
            }
        }
        
        // Delete or handle existing reservations
        foreach ($datesToUpdate as $date) {
            Reservation::where('table_id',null)->where('Restaurant_id', $res_id)->where('reservation_date',$date)->delete();
            
            // Convert start and end times to timestamps
            $startTime = strtotime($request->new_time_start);
            $endTime = strtotime($request->new_time_end);
            
            for (; $startTime <= $endTime; $startTime += (30 * 60)) { // increment by 30 minutes
            
                $formattedStartTime = date('H:i', $startTime);
                $formattedEndTime = date('H:i', $startTime + (30 * 60)); // Calculate end time for the current slot
            
                // Now check the availability and create reservations
                $reservation = Reservation::where('table_id', '!=', null)
                    ->where('Restaurant_id', $res_id)
                    ->whereTime('reservation_time', '<=', $formattedStartTime)
                    ->whereTime('reservation_time_end', '>=', $formattedEndTime)
                    ->where('reservation_date', $date)
                    ->first();
                
                if (!$reservation) {
                    // If no overlapping reservation, create a new one
                    Reservation::create([
                        'Restaurant_id' => $res_id,
                        'duration' => 30, // assuming duration is in minutes
                        'reservation_time' => $formattedStartTime,
                        'reservation_time_end' => $formattedEndTime,
                        'reservation_date' => $date,
                    ]);
                }
            }
            

        }
        

        // Update fields based on the day
        $time->$fromField = date('H:i', strtotime($request->new_time_start));
        $time->$toField = date('H:i', strtotime($request->new_time_end));
        $time->save();

        DB::commit();
        return redirect()->back()->with('success', 'Updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());
    }
}



    public function reservations_generate(Request $request, $id)
    {
        $Restaurant = Restaurant::where('id', $id)->first();
        $times = ['null'];
        $id = $id;
        return view('Admin.Reservations.work_time', compact('times', 'id'));
    }


    public function reservations_generate_post(Request $request)
    {
    $times = times::where('Restaurant_id', $request->res_id)->delete();
        DB::beginTransaction();

        switch ($request->input('action')) {
            case 'submit':
                $rules = [
                    'sun_from' => 'required|date_format:H:i',
                    'sun_to' => 'required|date_format:H:i|after:sun_from',
                    'sat_from' => 'required|date_format:H:i',
                    'sat_to' => 'required|date_format:H:i|after:sat_from',
                    'mon_from' => 'required|date_format:H:i',
                    'mon_to' => 'required|date_format:H:i|after:mon_from',
                    'tue_from' => 'required|date_format:H:i',
                    'tue_to' => 'required|date_format:H:i|after:tue_from',
                    'wed_from' => 'required|date_format:H:i',
                    'wed_to' => 'required|date_format:H:i|after:wed_from',
                    'thu_from' => 'required|date_format:H:i',
                    'thu_to' => 'required|date_format:H:i|after:thu_from',
                    'fri_from' => 'required|date_format:H:i',
                    'fri_to' => 'required|date_format:H:i|after:fri_from',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $Restaurant = Restaurant::where('id', $request->res_id)->first();
                $times = times::where('Restaurant_id', $Restaurant->id)->first();

                if ($times) {

                    $times->update([
                        'Restaurant_id' => $Restaurant->id,
                        'date_start' => $Restaurant->Activation_start,
                        'date_end' => $Restaurant->Activation_end,
                        'sat_from' => $request->sat_from,
                        'sat_to' => $request->sat_to,
                        'sun_from' => $request->sun_from,
                        'sun_to' => $request->sun_to,
                        'mon_from' => $request->mon_from,
                        'mon_to' => $request->mon_to,
                        'tue_from' => $request->tue_from,
                        'tue_to' => $request->tue_to,
                        'wed_from' => $request->wed_from,
                        'wed_to' => $request->wed_to,
                        'thu_from' => $request->thu_from,
                        'thu_to' => $request->thu_to,
                        'fri_from' => $request->fri_from,
                        'fri_to' => $request->fri_to,
                    ]);
                    if ($request->filled('sat_closed')) {
                        $times->update([
                            'sat_from' => null,
                            'sat_to' => null,
                        ]);
                    }
                    if ($request->filled('sun_closed')) {
                        $times->update([
                            'sun_from' => null,
                            'sun_to' => null,
                        ]);
                    }
                    if ($request->filled('mon_closed')) {
                        $times->update([
                            'mon_from' => null,
                            'mon_to' => null,
                        ]);
                    }
                    if ($request->filled('tue_closed')) {
                        $times->update([
                            'tue_from' => null,
                            'tue_to' => null,
                        ]);
                    }
                    if ($request->filled('wed_closed')) {
                        $times->update([
                            'wed_from' => null,
                            'wed_to' => null,
                        ]);
                    }
                    if ($request->filled('thu_closed')) {
                        $times->update([
                            'thu_from' => null,
                            'thu_to' => null,
                        ]);
                    }
                    if ($request->filled('fri_closed')) {
                        $times->update([
                            'fri_from' => null,
                            'fri_to' => null,
                        ]);
                    }
                    $times = DB::table('restaurantoperatinghours') // Assuming 'times' is the table where working hours are stored
            ->where('Restaurant_id', $id)
            ->first();
            
        $startDate = new \DateTime($times->date_start);

        $endDate = new \DateTime($times->date_end);
        $endDate = $endDate->modify('+1 day'); // Include the end date in the loop
        Reservation::where('table_id',null)->where('Restaurant_id', $request->res_id)->delete();
        for ($reservationDate = $startDate; $reservationDate < $endDate; $reservationDate->modify('+1 day')) {

            $dayOfWeek = strtolower($reservationDate->format('D'));
            // Dynamic variable names to access the right attributes
            if($times->{$dayOfWeek . '_from'} == null){
            
                continue;
            }
            $openTime = strtotime($times->{$dayOfWeek . '_from'});

            $closeTime = strtotime($times->{$dayOfWeek . '_to'});
            // Check if the restaurant is open on the given day and time

            $duration = 30; // Duration in minutes

            // Iterate over the time range from open to close time
            for ($startTime = $openTime; $startTime + (30 * 60) <= $closeTime; $startTime += (30 * 60)) {
                $endTime = $startTime + (30 * 60); // Calculate end time for the current slot
                $reservation = Reservation::where('table_id','!=',null)->where('Restaurant_id', $request->res_id)->where('reservation_time',$startTime)->first();
                if ($reservation) {
                  continue;
                }
                // Create each reservation within the time frame
                Reservation::create([
                    'Restaurant_id' => $request->res_id,
                    'duration' => 30,
                    'reservation_time' => date('H:i', $startTime),
                    'reservation_time_end' => date('H:i', $endTime),
                    'reservation_date' => $reservationDate,
                ]);
            }
            }
                    DB::commit();
                } else {
                    $times = times::create([
                        'Restaurant_id' => $Restaurant->id,
                        'date_start' => $Restaurant->Activation_start,
                        'date_end' => $Restaurant->Activation_end,
                        'sat_from' => $request->sat_from,
                        'sat_to' => $request->sat_to,
                        'sun_from' => $request->sun_from,
                        'sun_to' => $request->sun_to,
                        'mon_from' => $request->mon_from,
                        'mon_to' => $request->mon_to,
                        'tue_from' => $request->tue_from,
                        'tue_to' => $request->tue_to,
                        'wed_from' => $request->wed_from,
                        'wed_to' => $request->wed_to,
                        'thu_from' => $request->thu_from,
                        'thu_to' => $request->thu_to,
                        'fri_from' => $request->fri_from,
                        'fri_to' => $request->fri_to,
                    ]);
                    if ($request->filled('sat_closed')) {
                        $times->update([
                            'sat_from' => null,
                            'sat_to' => null,
                        ]);
                    }
                    if ($request->filled('sun_closed')) {
                        $times->update([
                            'sun_from' => null,
                            'sun_to' => null,
                        ]);
                    }
                    if ($request->filled('mon_closed')) {
                        $times->update([
                            'mon_from' => null,
                            'mon_to' => null,
                        ]);
                    }
                    if ($request->filled('tue_closed')) {
                        $times->update([
                            'tue_from' => null,
                            'tue_to' => null,
                        ]);
                    }
                    if ($request->filled('wed_closed')) {
                        $times->update([
                            'wed_from' => null,
                            'wed_to' => null,
                        ]);
                    }
                    if ($request->filled('thu_closed')) {
                        $times->update([
                            'thu_from' => null,
                            'thu_to' => null,
                        ]);
                    }
                    if ($request->filled('fri_closed')) {
                        $times->update([
                            'fri_from' => null,
                            'fri_to' => null,
                        ]);
                    }
            $times = DB::table('restaurantoperatinghours') // Assuming 'times' is the table where working hours are stored
            ->where('Restaurant_id', $request->res_id)
            ->first();
            
        $startDate = new \DateTime($times->date_start);

        $endDate = new \DateTime($times->date_end);
        $endDate = $endDate->modify('+1 day'); // Include the end date in the loop
        Reservation::where('table_id',null)->where('Restaurant_id', $Restaurant->id)->delete();
        for ($reservationDate = $startDate; $reservationDate < $endDate; $reservationDate->modify('+1 day')) {

            $dayOfWeek = strtolower($reservationDate->format('D'));

            // Dynamic variable names to access the right attributes
            if($times->{$dayOfWeek . '_from'} == null){
                continue;
            }
            $openTime = strtotime($times->{$dayOfWeek . '_from'});

            $closeTime = strtotime($times->{$dayOfWeek . '_to'});
            // Check if the restaurant is open on the given day and time

            $duration = 30; // Duration in minutes

            // Iterate over the time range from open to close time
            for ($startTime = $openTime; $startTime + (30 * 60) <= $closeTime; $startTime += (30 * 60)) {
                $endTime = $startTime + (30 * 60); // Calculate end time for the current slot
                $reservation = Reservation::where('table_id','!=',null)->where('Restaurant_id', $request->res_id)->where('reservation_time',$startTime)->first();
                if ($reservation) {
                  continue;
                }
                // Create each reservation within the time frame
                $reservation = Reservation::create([
                    'Restaurant_id' => $request->res_id,
                    'duration' => 30,
                    'reservation_time' => date('H:i', $startTime),
                    'reservation_time_end' => date('H:i', $endTime),
                    'reservation_date' => $reservationDate,
                ]);
                
            }
            }
                    DB::commit();
                }
                $reservations = Reservation::where('Restaurant_id',$Restaurant->id)->get();
                
                return redirect()->route('table_create', $Restaurant->id);
                break;
            case 'regenerate';
                DB::commit();

                return redirect()->route('records_reservations', $request->res_id);

                break;
            case 'cancel':
                DB::commit();
                return redirect()->route('Restaurant_reservations', $request->res_id);
                break;
        }
    }
    public function records_reservations($id)
    {
        $times = times::where('Restaurant_id', $id)->get();
        $Restaurant = Restaurant::where('id', $id)->first();
        return view('Admin.Reservations.records', compact('times', 'Restaurant'));
    }
    public function Cancelled_Reservations()
    {
        $cancelled_reservations = Reservation::where('tablereservations.status', 'cancelled')
            ->join('customers', 'tablereservations.customer_id', '=', 'customers.id')
            ->join('restaurants', 'tablereservations.Restaurant_id', '=', 'restaurants.id')
            ->get([
                'tablereservations.id',
                'customers.firstname',
                'customers.lastname',
                'customers.email',
                'customers.phone',
                'tablereservations.reservation_date',
                'tablereservations.reservation_time',
                'tablereservations.created_at as date_request',
                'tablereservations.created_at as date_cancel', // Assuming cancel date is the same as creation date
                'restaurants.Deposite_value',
                'restaurants.Deposite_desc',
                'restaurants.refund_policy',
                'restaurants.change_policy',
                'restaurants.cancellition_policy',
                'restaurants.deposit',
                'tablereservations.payment_method',
                'tablereservations.actual_price as amount_paid'
            ]);
        return view('Admin.Reservations.cancelled_reservations', compact('cancelled_reservations'));
    }

    public function Cancelled_Details($id)
    {
        $reservation = Reservation::where('status', 'cancelled')
            ->join('customers', 'tablereservations.customer_id', '=', 'customers.id')
            ->join('restaurants', 'tablereservations.Restaurant_id', '=', 'restaurants.id')
            ->where('tablereservations.id', $id)
            ->first([
                'tablereservations.id',
                'customers.firstname',
                'customers.lastname',
                'customers.email',
                'customers.phone',
                'tablereservations.reservation_date',
                'tablereservations.reservation_time',
                'tablereservations.created_at as date_request',
                'tablereservations.created_at as date_cancel', // Assuming cancel date is the same as creation date

                'restaurants.Deposite_information',
                'restaurants.refund_policy',
                'restaurants.change_policy',
                'restaurants.cancellition_policy',
                'restaurants.deposit',
                'tablereservations.payment_method',
                'tablereservations.actual_price as amount_paid'
            ]);
        return view('Admin.Reservations.details_reservations', compact('reservation'));
    }

    public function Cancell_accept()
    {
        return 'soon...';
    }
    public function Cancelled_reject()
    {
        return 'soon...';
    }
}
