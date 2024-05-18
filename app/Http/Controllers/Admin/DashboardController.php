<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RestaurantRequest;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\icon;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Cuisine;
use App\Models\Menu;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Reviews;
use App\Models\Location;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Notifications\Account_Active;
use App\Repositories\Interfaces\AdminRepositoryInterface;
class DashboardController extends Controller
{
    protected $AdminRepository;
    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }
    public function statistics()
    {
        return $this->AdminRepository->statistics();
    }
    public function update_profile_admin(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->AdminRepository->update_profile_admin($request);
        return redirect()->back();
    }
    public function admin_profile()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
       return $this->AdminRepository->admin_profile();
    }
    public function all_notifications()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->AdminRepository->all_notifications();
    }

    public function getNotifications()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->AdminRepository->getNotifications();
    }
    public function markAsRead(Request $markAsRead)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->AdminRepository->markAsRead($markAsRead);
    }
    public function filterRestaurantsAndUsers(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->AdminRepository->filterRestaurantsAndUsers($request);
    }
}
