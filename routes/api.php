<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController as AuthController;
use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\ApiReservationsController;
use App\Http\Controllers\Api\ApiRestaurantsController;
use App\Http\Controllers\Api\NotificationsController;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customer/')->group(function () {
   Route::get('get_all_restaurants', [ApiRestaurantsController::class, 'get_all_restaurants'])->name('get_all_restaurants');
    //Auth
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'create'])->name('create');
    Route::post('verify', [AuthController::class, 'verify'])->name('verify'); //in create or forget_pass
    Route::get('proposal_Restaurants', [ApiRestaurantsController::class, 'proposal_Restaurants'])->name('proposal_Restaurants');
    Route::get('details_guest/{id}', [ApiRestaurantsController::class, 'details_guest'])->name('details_guest');
    Route::post('sendVerify_password', [AuthController::class, 'sendVerify_password'])->name('sendVerify_password');
    Route::post('new_password', [AuthController::class, 'new_password'])->name('new_password');
    
    //forgot password
    Route::post('forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
    Route::post('check_code', [AuthController::class, 'check_code'])->name('check_code');
    Route::post('change_password', [AuthController::class, 'change_password'])->name('change_password');
    
        Route::get('cuisines', [ApiRestaurantsController::class, 'cuisines'])->name('cuisines');
        Route::get('types_tables', [ApiRestaurantsController::class, 'types_tables'])->name('types_tables');
    Route::group(["middleware" => ['ensureToken']], function () {
        //Auth
        Route::post('delete_account',[AuthController::class, 'delete_account'])->name('delete_account');
        Route::get('restaurants_token', [ApiRestaurantsController::class, 'restaurants_token'])->name('restaurants_token');
        Route::post('register_complete', [AuthController::class, 'register_complete'])->name('register_complete');
       
      
        Route::post('reset_password', [AuthController::class, 'resetPassword'])->name('reset_password');
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('edit_profile', [AuthController::class, 'edit_profile']);
        //Home
        
        Route::post('details_offer/{id}', [ApiHomeController::class, 'details_offer'])->name('details_offer');
        Route::get('details_category/{id}', [ApiHomeController::class, 'details_category'])->name('details_category');
        Route::get('details_cuisine/{id}', [ApiRestaurantsController::class, 'cuisine_Restaurants'])->name('cuisine_Restaurants');

        Route::get('map_res', [ApiHomeController::class, 'map_res']);

        Route::get('forYouRestaurants', [ApiRestaurantsController::class, 'forYouRestaurants'])->name('forYouRestaurants');
        Route::get('new_opening', [ApiRestaurantsController::class, 'new_opening'])->name('new_opening');
        Route::get('featured', [ApiRestaurantsController::class, 'featured'])->name('featured');
        Route::get('offers', [ApiRestaurantsController::class, 'offers'])->name('offers');
        Route::get('taste', [ApiRestaurantsController::class, 'taste'])->name('taste');
        Route::get('get_appointments', [ApiRestaurantsController::class, 'get_appointments'])->name('get_appointments');
        //Restaurant
        Route::get('details/{id}', [ApiRestaurantsController::class, 'details'])->name('details');
        Route::post('followUnfollowRestaurant/{id}', [ApiRestaurantsController::class, 'followUnfollowRestaurant'])->name('followUnfollowRestaurant');
        Route::post('follow/{id}', [ApiRestaurantsController::class, 'follow'])->name('follow');
        Route::post('unfollow/{id}', [ApiRestaurantsController::class, 'unfollow'])->name('unfollow');
        Route::get('list_rec_follow', [ApiRestaurantsController::class, 'list_rec_follow'])->name('list_rec_follow');
        Route::post('review/{id}', [ApiRestaurantsController::class, 'review'])->name('review');
        Route::get('reviews/{id}', [ApiRestaurantsController::class, 'reviews'])->name('reviews');
        Route::get('nearest_Restaurants', [ApiRestaurantsController::class, 'nearest_Restaurants'])->name('nearest_Restaurants');
        //Search
        Route::post('search', [ApiRestaurantsController::class, 'search'])->name('search');  //name  //from app
        Route::post('advansearch', [ApiRestaurantsController::class, 'advansearch'])->name('advansearch');  //same filter reservations : //  size location  type  adult  children  da time
        Route::post('filtersearch', [ApiRestaurantsController::class, 'filtersearch'])->name('filtersearch');// longitude latitude price_range name_cuisine
      
        //Reservation
        Route::get('my_reservations', [ApiReservationsController::class, 'my_reservations'])->name('my_reservations');
        Route::get('reservation_details/{id}', [ApiReservationsController::class, 'reservation_details'])->name('reservation_details');
        Route::post('reservation_delete/{id}', [ApiReservationsController::class, 'reservation_delete'])->name('reservation_delete');
        //how reservation
        Route::post('available_times/{id}', [ApiReservationsController::class, 'available_times_res'])->name('available_times'); //get id_restaurant date return times
        Route::post('available_tables/{id}', [ApiReservationsController::class, 'available_tables'])->name('available_tables');
        Route::get('restaurant_info/{id}', [ApiReservationsController::class, 'restaurant_info'])->name('restaurant_info');
         //get id_restaurant date time guest(adult - children) return (tables : id(reservstion) -  size - location - type )
        //Route::post('reversation/{id}', [ApiReservationsController::class, 'reversation'])->name('reversation');  //get id(reservstion) - payment - promocode
        Route::post('promocodes_available/{id}', [ApiReservationsController::class, 'promocodes_available'])->name('promocodes_available');  //get id(restaurants)  return promocodes ()
        //Route::post('reversation_cancel/{id}', [ApiReservationsController::class, 'reversation_cancel'])->name('reversation_cancel');
        Route::post('available_capacity/{id}', [ApiReservationsController::class, 'available_capacity'])->name('available_capacity');
        Route::post('delete_reservation/{id}', [ApiReservationsController::class, 'delete_reservation'])->name('delete_reservation');
        //Offers
        Route::get('current_coupons', [ApiHomeController::class, 'current_coupons'])->name('current_coupons');
        Route::get('achievements', [ApiHomeController::class, 'achievements'])->name('achievements');
        Route::get('accept_invities', [ApiHomeController::class, 'accept_invities'])->name('accept_invities');
        //Notifications
        Route::get('get_notifications/{page_id}', [NotificationsController::class, 'myNotifications'])->name('myNotifications');
        Route::get('unread_notifications', [NotificationsController::class, 'unread_notifications'])->name('unread_notifications');
        //Payment
        Route::post('invoice/create/{restaurant_id}', [\App\Http\Controllers\PaymentController::class, 'createInvoice'])->name('invoice_create');
        Route::post('invoice/resendCode/{reservation_id}', [\App\Http\Controllers\PaymentController::class, 'resendCode'])->name('invoice_resendCode');
        Route::post('invoice/confirmPayment/{reservation_id}', [\App\Http\Controllers\PaymentController::class, 'confirmPayment'])->name('confirm_payment');
        Route::post('invoice/cancelReservation/{id}', [\App\Http\Controllers\PaymentController::class, 'cancelReservation'])->name('cancelReservation');
       
    });
});
