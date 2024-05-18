<?php

namespace App\Http\Controllers\api;


use App\Events\NotificationEvent;
use App\Models\Action\Notification;
use App\Models\Event\Booking;
use App\Models\Event\CancelledBooking;
use App\Models\Event\EventClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function book(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request['bookings'] as $bookingData) {
                $class = EventClass::find($bookingData['class_id']);
                if ($class->ticket_number == 0) {
                    throw new \Exception('Tickets in Class ' . $class->code . ' sold out');
                }
                $ticket_number = $class->ticket_number - 1;
                $class->update(['ticket_number' => $ticket_number]);
            }

            $user_id = Auth::guard('mobile')->id();
            $booking = null ;
            $x = 0;
            foreach ($request['bookings'] as $bookingData) {
                $booking = new Booking();
                $booking->user_id = $user_id;
                $booking->class_id = $bookingData['class_id'];
                $booking->first_name = $bookingData['first_name'];
                $booking->last_name = $bookingData['last_name'];
                $booking->age = $bookingData['age'];
                $booking->phone_number = $bookingData['phone_number'];
                $booking->interest = json_encode($bookingData['options']);
                $booking->save();
                $x++ ;
            }

            Notification::create([
                'title' =>'Booked Successfully' ,
                'description' =>'You have booked ' . $x .' Tickets Successfully in event',
                'user_id' => Auth::id()
            ]);
            event(new NotificationEvent('Booked Successfully' , 'You have booked ' . $x .' Tickets Successfully in event' , Auth::id()));

            // Commit the transaction
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Booking successful'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function my_booking()
    {
        $user = Auth::user();

        $allBookings = $user->bookings()->with(['eventClass' => function ($query) {
            $query->select('id', 'event_id'); // Add the fields you want from eventClass
        }, 'eventClass.event' => function ($query) {
            $query->withoutGlobalScopes()->select('id', 'title' , 'title_ar', 'start_date', 'end_date', 'venue_id' , 'images'); // Add the fields you want from event
        }, 'eventClass.event.venue'=> function ($query) {
            $query->select('id', 'governorate', 'location_description'); // Add the fields you want from venue
        }])->get();

        // Grouping bookings by user_id and event_id
        $groupedBookings = $allBookings->groupBy(function ($item) {
            return $item['user_id'] . '-' . $item['eventClass']['event_id'];
        });

        // Structure grouped data
        $structuredBookings = $groupedBookings->map(function ($group) {
            return [
                'user_id' => $group->first()->user_id,
                'event_id' => $group->first()->eventClass->event_id,
                'bookings' => $group->values()
            ];
        });

        // Separate bookings into upcoming and completed
        $completedBookings = collect([]);
        $upcomingBookings = collect([]);

        foreach ($structuredBookings as $group) {
            if (Carbon::parse($group['bookings']->first()->eventClass->event->start_date)->isPast()) {
                $completedBookings->push($group);
            } else {
                $upcomingBookings->push($group);
            }
        }

        return response()->json([
            'status' => true,
            'bookings' => $upcomingBookings,
            'completed_bookings' => $completedBookings
        ]);
    }



    public function cancelBooking($bookingId , Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($bookingId);
            $class = EventClass::find($booking->class_id);
            $class->increment('ticket_number');

            $cancelledBooking = CancelledBooking::create([
                'user_id' => $booking->user_id,
                'class_id' => $booking->class_id,
                'first_name' => $booking->first_name,
                'last_name' => $booking->last_name,
                'age' => $booking->age,
                'phone_number' => $booking->phone_number,
                'interest' => $booking->interest,
                'reason' => $request->reason ,
            ]);

            $booking->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Booking cancelled successfully',
                'cancelled_booking' => $cancelledBooking
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function myCancelledBookings()
    {
        $user = Auth::user();

        $cancelledBookings = $user->cancelledBookings()
            ->with(['eventClass' => function ($query) {
                $query->select('id', 'event_id'); // Add the fields you want from eventClass
            }, 'eventClass.event' => function ($query) {
                $query->withoutGlobalScopes()->select('id', 'title' , 'title_ar', 'start_date', 'end_date' , 'venue_id' , 'images'); // Add the fields you want from event
            } , 'eventClass.event.venue'=> function ($query) {
                $query->select('id', 'governorate', 'location_description' , 'location_description_ar'); // Add the fields you want from event
            } ])
            ->get();
        return response()->json([
            'status' => true,
            'cancelled_bookings' => $cancelledBookings
        ]);
    }
}
