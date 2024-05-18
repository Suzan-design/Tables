<?php

namespace App\Http\Controllers\Restaurant_staff;
use App\Models\Restaurant;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function restaurant_details()
    {
        $Restaurant = Restaurant::where('user_id',\Illuminate\Support\Facades\Auth::id())->with('cuisine', 'staff', 'menu', 'location')->first();
        return view('staff.Restaurants.show', compact('Restaurant'));
    }
}
