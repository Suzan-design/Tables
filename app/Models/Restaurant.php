<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Table;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Location;
use App\Models\times;
use App\Models\restaurnats_categories;

use Illuminate\Support\Facades\DB;

class Restaurant extends Model
{
    use HasFactory;
    protected $guarded=[''];
    public function cuisine()
        {
            return $this->belongsTo(Cuisine::class);
        }
        public function category()
        {
            return $this->belongsTo(restaurnats_categories::class);
        }
    public function staff()  //staff
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function menu()
    {
        return $this->hasMany(Menu::class,'restaurant_id');
    }
    public function tables()
    {
        return $this->hasMany(Table::class,'Restaurant_id');
    }
    public function images()
    {
        return $this->hasMany(Image::class,'imageable_id');
    }
    public function reviews()
    {
        return $this->hasMany(Reviews::class,'Restaurant_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class,'Restaurant_id');
    }
    public function location()  //staff
    {
        return $this->hasOne(Location::class,'Restaurant_id');
    }
    public function times()  //staff
    {
        return $this->hasMany(times::class,'Restaurant_id');
    }
    
    public function getRestaurantsBasedOnAgeAndLocation($age, $location)
    {
    return Restaurant::select(
       'Restaurants.*',
       DB::raw('ABS(TIMESTAMPDIFF(YEAR, ?, CURDATE())) - age_range AS age_difference'),
       DB::raw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance')
    )->join('restaurantlocations', 'Restaurants.id', '=', 'restaurantlocations.Restaurant_id')
     ->orderBy('age_difference')
     ->orderBy('distance')
     ->setBindings([$age, $location['latitude'], $location['longitude'], $location['latitude']])
     ->with(['cuisine', 'images' => function ($query) {
        $query->where('type', 'main');
    }, 'location'])
    ->get()
    ->map(function ($restaurant) {
        return $this->transformRestaurantData($restaurant);
    });
    }
public function getFeaturedRestaurants()
{
    return Restaurant::with(['cuisine', 'images' => function ($query) {
        $query->where('type', 'main');
    }, 'location'])
    ->get()
    ->map(function ($restaurant) {
        return $this->transformRestaurantData($restaurant);
    });
}
public function getRestaurantsBasedOnUserTaste($userId)
{
    // هنا يمكن تحليل اختيارات المستخدم السابقة للحجوزات واستخدامها لتحديد المطاعم المفضلة
    // هذا مثال بسيط ويمكن توسيعه بناءً على بيانات المستخدم الفعلية
    return Restaurant::whereHas('reservations', function ($query) use ($userId) {
        $query->where('customer_id', $userId);
    })
    ->with(['cuisine', 'images' => function ($query) {
        $query->where('type', 'main');
    }, 'location'])
    ->get()
    ->map(function ($restaurant) {
        return $this->transformRestaurantData($restaurant);
    });
}
public function getOffers()
{
    // استعلام لجلب العروض
    return DB::table('images_of_offers')
        ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
        ->select('images_of_offers.id', 'images_of_offers.imageable_id', 'images_of_offers.filename')
        ->where('offers.type', 'offer')
        ->where('images_of_offers.type', 'cover')
        ->get();
}

public function getNewOpenings()
{
    // استعلام لجلب الافتتاحيات الجديدة
    return DB::table('images_of_offers')
        ->join('offers', 'images_of_offers.imageable_id', '=', 'offers.id')
        ->select('images_of_offers.id', 'images_of_offers.imageable_id', 'images_of_offers.filename')
        ->where('offers.type', 'new_opening')
        ->where('images_of_offers.type', 'cover')
        ->get();
}
public function transformRestaurantData($restaurant)
{
    return [
        'id' => $restaurant->id,
        'name' => $restaurant->name,
        'deposite' => $restaurant->deposit,
        'cuisine_name' => optional($restaurant->cuisine)->name,
        'category' => $restaurant->category,
        'location_text' => optional($restaurant->location)->text,
        'rating_number' => $restaurant->rating,
        'images' => $restaurant->images->pluck('filename')
    ];
}
public function scopeWithDistance($query, $latitude, $longitude)
{
    return $query->selectRaw(
        '(6371 * acos(cos(radians(?)) * cos(radians(restaurantlocations.latitude)) *
        cos(radians(restaurantlocations.longitude) - radians(?)) + sin(radians(?)) *
        sin(radians(restaurantlocations.latitude)))) as distance',
        [$latitude, $longitude, $latitude]
    );
}
public function scopeWithinDistance($query, $latitude, $longitude, $distance)
{
    return $query->selectRaw('restaurants.*, restaurantlocations.latitude, restaurantlocations.longitude,
        (6371 * acos(cos(radians(?)) * cos(radians(restaurantlocations.latitude))
        * cos(radians(restaurantlocations.longitude) - radians(?)) + sin(radians(?))
        * sin(radians(restaurantlocations.latitude)))) AS distance',
        [$latitude, $longitude, $latitude])
        ->join('restaurantlocations', 'restaurants.id', '=', 'restaurantlocations.restaurant_id')
        ->having('distance', '<', $distance);
}


    


}
