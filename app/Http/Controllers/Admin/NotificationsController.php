<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\restaurnats_categories;
use App\Models\PublicNotification;
use App\Models\Customer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $public_notifications = PublicNotification::all() ;
        return view('Admin.Notifications.index' , compact('public_notifications'));
    }

    public function dashboard()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $categories = restaurnats_categories::all() ;
        return view('Admin.Notifications.create' , compact('categories'));
    }

    public function sentNotification(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $notification = new PublicNotification();
        $notification->title = $request->title;
        $notification->description = $request->description;

        // Handle nullable fields
        $notification->target_states = $request->has('user_city') ? implode(',', $request->user_city) : null;
        $notification->target_ages = $request->has('ageRangeStart') && $request->has('ageRangeEnd') ? $request->ageRangeStart . '-' . $request->ageRangeEnd : null;
        $notification->target_reservations = $request->has('bookingRangeStart') && $request->has('bookingRangeEnd') ? $request->bookingRangeStart . '-' . $request->bookingRangeEnd : null;

        $notification->save(); // Save the notification to the database

        $Customers = Customer::query()
            ->when(isset($request->ageRangeStart), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$request->ageRangeStart]);
            })
            ->when(isset($request->ageRangeEnd), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$request->ageRangeEnd]);
            })
            ->withCount(['reservations'])
            ->when(isset($request->bookingRangeStart) && isset($request->bookingRangeEnd), function ($query) use ($request) {
                $query->havingRaw('reservations_count >= ? AND reservations_count <= ?', [$request->bookingRangeStart, $request->bookingRangeEnd]);
            })
            ->when(isset($request->user_city) && !empty($request->user_city), function ($query) use ($request) {
                $query->whereIn('state', $request->user_city);
            })
            ->when(isset($request['user_limit']), function ($query) use ($request) {
                return $query->take($request['user_limit']);
            })
            ->pluck('id');
            
        foreach ($Customers as $Customer) {
            $today = Carbon::today();
                Notification::create([
                    'title' => $request['title'],
                    'ar_title'=>$request['title'],
                    'description' => $request['description'],
                    'ar_description' => $request['description'],
                    'customer_id' => $Customer,
                    'date' => $today
                ]);
                $notificationController = new \App\Http\Controllers\Api\NotificationsController();
                $notificationController->sentNotification($Customer, $request['title'], $request['description']);
             
        }
        return redirect()->route('notification.index');
    }

    protected function getUsersCountNotification(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
         $usersCount = Customer::query()
            //->when(isset($request->user_interest_ids) && !empty($request->user_interest_ids), function ($query) use ($request) {
            //    $query->whereHas('eventCategories', function ($q) use ($request) {
            //        $q->whereIn('events_category_id', $request->user_interest_ids);
             //   });
            //})
            ->when(isset($request->ageRangeStart), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$request->ageRangeStart]);
            })
            ->when(isset($request->ageRangeEnd), function ($query) use ($request) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$request->ageRangeEnd]);
            })
            ->withCount(['bookings'])
            ->when(isset($request->bookingRangeStart) && isset($request->bookingRangeEnd), function ($query) use ($request) {
                $query->havingRaw('bookings_count >= ? AND bookings_count <= ?', [$request->bookingRangeStart, $request->bookingRangeEnd]);
            })
            ->when(isset($request->user_city) && !empty($request->user_city), function ($query) use ($request) {
                $query->whereIn('State', $request->user_city);
            })
            ->when(isset($request['user_limit']), function ($query) use ($request) {
                return $query->take($request['user_limit']);
            })
            ->count() ;
        return response()->json([
            'user_count' => $usersCount,
        ]) ;
    }

}
