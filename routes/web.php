<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TableContoller;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\CuisineController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemsController;
use App\Http\Controllers\Admin\RestaurantContoller;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ServicesContoller;
use App\Http\Controllers\Restaurant_staff\TableController as staff_TableController;
use App\Http\Controllers\Restaurant_staff\DashboardController as staff_DashboardController;
use App\Http\Controllers\Restaurant_staff\RestaurantController as staff_RestaurantController;
use App\Http\Controllers\Restaurant_staff\ReservationController as staff_ReservationController;
use App\Http\Controllers\Auth\ForgotPasswordController;


//use App\Http\Controllers\Restaurant_staff\TableController as staff_TableController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear', function () {

    $exitCode = Artisan::call('storage:link');
    return 'All routes cache has just been removed';
});
Route::get('/migrate-refresh', function () {
    try {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $output = Artisan::output();
        return response()->json(['message' => 'Migration and seeding completed successfully', 'output' => $output]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
Route::get('/forget_password', [ForgotPasswordController::class, 'forget_password'])->name('forget_password');
Route::post('/change_password/{user_id}', [ForgotPasswordController::class, 'change_password'])->name('change_password');
Route::post('/Verify', [ForgotPasswordController::class, 'Verify'])->name('Verify');
Route::group([
    'middleware' => ['auth'],
], function () {
    ////////////////////////////ADMIN////////////////////////////
    //Home
    Route::get('/', [DashboardController::class, 'statistics'])->name('statistics');
    //Tables
    Route::resource('tables', TableContoller::class);
    //Route::post('/table_update/{id}', [TableController::class, 'table_update'])->name('table_update');
    Route::post('/staf_table_edit/{id}', [staff_TableController::class, 'update'])->name('staff_table_edit');
    Route::get('/rest_tables/{id}', [TableContoller::class, 'rest_tables'])->name('rest_tables');
    Route::get('/add_table/{res_id}', [TableContoller::class, 'add_table'])->name('add_table');
    Route::get('/table_create/{id}', [TableContoller::class, 'table_create'])->name('table_create');


    //Reservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/accept', [ReservationController::class, 'accept'])->name('accept');
    Route::get('/reject/{id}', [ReservationController::class, 'reject'])->name('reject');

    Route::resource('menus', MenuController::class);
    Route::any('/menu_update', [MenuController::class, 'menu_update'])->name('menu_update');
    Route::resource('items', MenuItemsController::class);
    Route::any('/item_update', [MenuItemsController::class, 'item_update'])->name('item_update');

    Route::get('/reservations_generate/{id}', [ReservationController::class, 'reservations_generate'])->name('reservations_generate_get');
    Route::post('/reservations_generate', [ReservationController::class, 'reservations_generate_post'])->name('reservations_generate_post');
    Route::get('/records_reservations/{id}', [ReservationController::class, 'records_reservations'])->name('records_reservations');
    Route::post('/record_update', [ReservationController::class, 'record_update'])->name('record_update');
    Route::post('/generate_table_reservations', [ReservationController::class, 'generate_table_reservations'])->name('generate_table_reservations');
    Route::post('/staff_generate_table_reservations/{id}', [ReservationController::class, 'staff_generate_table_reservations'])->name('staff_generate_table_reservations');

    Route::post('/Cancelled_Reservations/{id}', [ReservationController::class, 'Cancelled_Reservations'])->name('Cancelled_Reservations');
    Route::get('/Cancelled_Details/{id}', [ReservationController::class, 'Cancelled_Details'])->name('Cancelled_Details');

    Route::get('/Cancell_accept/{id}', [ReservationController::class, 'Cancell_accept'])->name('Cancell_accept');
    Route::get('/Cancelled_reject/{id}', [ReservationController::class, 'Cancelled_reject'])->name('Cancelled_reject');

    //Restaurants
    Route::resource('Restaurants', RestaurantContoller::class);
     Route::resource('services', ServicesContoller::class);
    Route::get('/act_inact__Restaurant/{id}', [RestaurantContoller::class, 'act_inact__Restaurant'])->name('act_inact__Restaurant');  //id Restaurant
    Route::any('/Restaurant_reservations/{id}', [RestaurantContoller::class, 'Restaurant_reservations'])->name('Restaurant_reservations');
    //Offers
    Route::resource('offers', OfferController::class);
    Route::any('/offer_update', [OfferController::class, 'offer_update'])->name('offer_update');
    Route::get('/act_inact__offer/{id}', [OfferController::class, 'act_inact__offer'])->name('act_inact__offer');
    //Users
    Route::get('/managers', [UserController::class, 'managers'])->name('managers');
    Route::get('/staff_all', [UserController::class, 'staff_all'])->name('staff_all');
    Route::get('/customers', [UserController::class, 'customers'])->name('customers');
    Route::any('/update_profile_admin', [DashboardController::class, 'update_profile_admin'])->name('update_profile_admin');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::post('block_user/{id}' , [UserController::class , 'block_user'])->name('block_user') ;
    Route::post('Unblock_user/{id}' , [UserController::class , 'Unblock_user'])->name('Unblock_user') ;
    Route::post('delete_user/{id}' , [UserController::class , 'delete_user'])->name('delete_user');
    //Cuisines
    Route::resource('cuisines', CuisineController::class);

    Route::any('/cuisine_update', [CuisineController::class, 'cuisine_update'])->name('cuisine_update');
       Route::any('/icon_update', [ServicesContoller::class, 'icon_update'])->name('icon_update');


    Route::resource('categories', CategoryController::class);
    Route::any('/category_update', [CategoryController::class, 'category_update'])->name('category_update');

    //Promocodes
    Route::resource('promocodes', PromoController::class);
    Route::post('promocodes_inactive/{id}', [PromoController::class, 'promocodes_inactive'])->name('promocodes_inactive');  //id Restaurant
    //Invitations
    Route::resource('invitations', InvitationController::class);
    Route::post('filter-restaurants-and-users', [DashboardController::class, 'filterRestaurantsAndUsers'])->name('filter.restaurants.and.users');  //id Restaurant
    //Notifications
    Route::get('/all_notifications', [DashboardController::class, 'all_notifications'])->name('all_notifications');
    Route::any('user/notifications/get', [DashboardController::class, 'getNotifications'])->name('getNotifications');
    Route::any('user/notifications/read', [DashboardController::class, 'markAsRead'])->name('markAsRead');
    Route::any('/user/notifications/read/{id}', [DashboardController::class, 'markAsReadAndRedirect'])->name('markAsReadAndRedirect');
    //Profile
    Route::get('/admin_profile', [DashboardController::class, 'admin_profile'])->name('admin_profile');
    ////////////////////////////STAFF////////////////////////////
    //statistics
    Route::get('/staff_statistics', [staff_DashboardController::class, 'staff_statistics'])->name('staff_statistics');
    Route::get('/profile', [staff_DashboardController::class, 'staff_profile'])->name('staff_profile');
    Route::post('/update_profile_staff', [staff_DashboardController::class, 'update_profile'])->name('update_profile_staff');
    //Restaurant
    Route::get('/restaurant_details', [staff_RestaurantController::class, 'restaurant_details'])->name('restaurant_details');
    //Tables
    Route::get('/restaurant_tables', [staff_TableController::class, 'restaurant_tables'])->name('restaurant_tables');
    //Reservations
    Route::any('/restaurant_reservations', [staff_ReservationController::class, 'restaurant_reservations'])->name('restaurant_reservations');
    Route::any('/reservations_details/{id}', [staff_ReservationController::class, 'reservations_details'])->name('reservations_details');
    Route::any('/table_reservations/{id}', [staff_ReservationController::class, 'table_reservations'])->name('table_reservations');
    Route::get('/my_records', [staff_ReservationController::class, 'records_reservations'])->name('my_records');
    Route::get('/my_records/{id}', [ReservationController::class, 'admin_records_reservations'])->name('records_reservations');

    Route::post('/reservations_start_ajax', [staff_ReservationController::class, 'reservations_start_ajax'])->name('reservations_start_ajax');
    Route::get('/reservations_end_ajax/{id}', [staff_ReservationController::class, 'reservations_end_ajax'])->name('reservations_end_ajax');


    Route::get('notification_index' , [\App\Http\Controllers\Admin\NotificationsController::class , 'index'])->name('notification.index') ;
    Route::get('notification_dashboard' , [\App\Http\Controllers\Admin\NotificationsController::class , 'dashboard'])->name('notification_dashboard') ;
    Route::post('sent_notification' , [\App\Http\Controllers\Admin\NotificationsController::class , 'sentNotification'])->name('sent_notification') ;
    Route::post('user_count_notification' , [\App\Http\Controllers\Admin\NotificationsController::class , 'getUsersCountNotification'])->name('user_count_notification') ;
});
Auth::routes();
