<?php

namespace App\Repositories;

use Hash;
use Carbon\Carbon;
use App\Models\icon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Models\Table;
use App\Models\Cuisine;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Account_Active;
use App\Http\Requests\RestaurantRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository implements AdminRepositoryInterface
{
    public function update_profile_admin($request)
    {

        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'confirmed',
        ]);
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        try {
            DB::beginTransaction();
            $user->update($validatedData);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function statistics()
    {
        $Restaurants_number = Restaurant::count();
        $act = Restaurant::Where(
            [
                'status' => 'active',
            ]
        )->count();
        $tables = Table::all();
        $Restaurant = Restaurant::where('user_id',auth()->id())->first();
        $reserv_count = Reservation::where('status','next')->count();
        if(auth()->user()->roleName == 'staff'){
        $reservations = Reservation::where('Restaurant_id',$Restaurant->id)->where('status','pending')->get();
        $reserv_count = Reservation::where('Restaurant_id',$Restaurant->id)->where('status','next')->count();
        $accepted_reservations = Reservation::where('Restaurant_id',$Restaurant->id)->where('status','next')->get();
        $rejected_reservations = Reservation::where('Restaurant_id',$Restaurant->id)->where('status','rejected')->get();
        $cancelled_reservations = Reservation::where('Restaurant_id',$Restaurant->id)->where('status','cancelled')->get();
        $customers = 0;
         $Restaurants_number =0 ;
        return view('Admin.statistics', compact('Restaurants_number', 'act', 'reservations', 'reserv_count','accepted_reservations','rejected_reservations', 'customers','Restaurant','tables','cancelled_reservations'));
        }
        $customers = Customer::count();
        return view('Admin.statistics', compact('Restaurants_number', 'act', 'reserv_count', 'customers','Restaurant','tables'));
    }
    
    public function admin_profile()
    {
        $user = User::where('id', Auth::id())->first();
        return view('Admin.profile', compact('user'));
    }

    public function all_notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return view('notifications', compact('notifications'));
    }

    public function getNotifications()
    {
        return [
            'read'      => auth()->user()->readNotifications,
            'unread'    => auth()->user()->unreadNotifications,
            'usertype'  => auth()->user()->roleName,
        ];
    }
    public function markAsRead($request)
    {
        return auth()->user()->notifications->where('id', $request->id)->markAsRead();
    }
    public function filterRestaurantsAndUsers($request)
    {
        $cityFilter = $request->input('cities_res');
        $cuisineFilter = $request->input('cuisines');
        $city_us_Filter = $request->input('cities_us');
        $followingsFilter = $request->input('is_followings');
        $reservationsFilter = $request->input('is_reservaions');
        $numRestaurantsFilter = $request->input('num_restaurants');
        $numUsersFilter = $request->input('num_users');
        $num_reservations = $request->input('num_reservations');
        $filteredRestaurants = Restaurant::where(function ($query) use ($cityFilter, $cuisineFilter, $numRestaurantsFilter) {
            if ($cityFilter) {
                $query->whereHas('location', function ($subquery) use ($cityFilter) {
                    $subquery->whereIn('state', $cityFilter);
                });
            }
            if ($cuisineFilter) {
                $query->whereHas('cuisine', function ($subquery) use ($cuisineFilter) {
                    $subquery->whereIn('name', $cuisineFilter);
                });
            }
        })->limit($numRestaurantsFilter)->get();
        $filteredRestaurants = $filteredRestaurants->count();
        $filteredUsers = Customer::where(function ($query) use ($cityFilter, $followingsFilter, $reservationsFilter, $numUsersFilter, $city_us_Filter, $num_reservations) {
            if ($city_us_Filter) {
                $query->whereIn('State', $city_us_Filter);
            }
            if ($followingsFilter) {
                $query->orderBy('followed_restaurants', 'asc');
            }
            if ($reservationsFilter) {
                $query->withCount('reservations')->orderBy('reservations_count', 'asc');
            }
            if ($num_reservations) {
                $query->has('reservations', '<=', $num_reservations);
            }
        })->limit($numUsersFilter)->get();
        $filteredUsers = $filteredUsers->count();
        return response()->json(['restaurants' => $filteredRestaurants, 'users' => $filteredUsers]);
    }
}
