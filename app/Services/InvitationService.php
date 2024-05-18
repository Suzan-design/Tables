<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Cuisine;
use App\Models\Promocode;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\res_prompcodes;
use App\Models\Invitation;
use Str;
use Carbon\Carbon;

class InvitationService
{
    public function generate_promocode_invitation(Invitation $invitation, Customer $customer)
    {
        //filter : num_res is_city is_follwings is_reservations
        //الفلرة بالبداية حسب المدينة ومتابعات المستخدم لهذه المطاعم وعدد حجوزاته
        //لهذه المطاعم واخيرا تحديد اول اكس من المطاعم
        $filteredRestaurants = Restaurant::limit('10')->get();
        $RestaurantsIds = $filteredRestaurants->pluck('id');

        $promocode = Promocode::create([
            'code' => Str::random(6),
            'discount' => $invitation->discount,
            'limit' => $invitation->limit,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($invitation->expire),
            'is_reservaions' => '0',
            'is_followings' => '0',
            'description' => 'Congratulations on completing a new achievements',
            'num_us' => '1',
            'num_res' => '10',
            'users_ids' => $customer->pluck('id'),
            'type' => 'invitation',
            'image' =>$invitation->image,
        ]);

        $insertData = $RestaurantsIds->map(function ($restaurantId) use ($promocode) {
            return [
                'restaurant_id' => $restaurantId,
                'promocode_id' => $promocode->id,
            ];
        })->all();
        res_prompcodes::insert($insertData);
    }
    public function generate_promocode_new(Customer $customer)
    {
        //filter : num_res is_city is_follwings is_reservations
        //الفلرة بالبداية حسب المدينة ومتابعات المستخدم لهذه المطاعم وعدد حجوزاته
        //لهذه المطاعم واخيرا تحديد اول اكس من المطاعم

        $invitation = Invitation::where([
            'target' => '0',
            'type' => 'invitations',
        ])->first();
        if ($invitation) {
            $filteredRestaurants = Restaurant::limit('10')->get();
            $RestaurantsIds = $filteredRestaurants->pluck('id');
            $promocode = Promocode::create([
                'code' => Str::random(6),
                'discount' => $invitation->discount,
                'limit' => $invitation->limit,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays($invitation->expire),
                'is_reservaions' => '0',
                'is_followings' => '0',
                'description' => '-',
                'num_us' => '1',
                'num_res' => '10',
                'users_ids' => $customer->pluck('id'),
                'type' => 'invitation',
                'image' => '-',
            ]);

            $insertData = $RestaurantsIds->map(function ($restaurantId) use ($promocode) {
                return [
                    'restaurant_id' => $restaurantId,
                    'promocode_id' => $promocode->id,
                ];
            })->all();
            res_prompcodes::insert($insertData);
        }
    }
}
