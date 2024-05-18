<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Cuisine;
use App\Models\Customer;
use App\Models\Promocode;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\res_prompcodes;
use Illuminate\Support\Facades\DB;
use App\Services\InvitationService;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PromocodeRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    protected $invitationService;
    protected $promocodeRepository;
    public function __construct(InvitationService $invitationService, PromocodeRepositoryInterface $promocodeRepository)
    {
        $this->invitationService = $invitationService;
        $this->promocodeRepository = $promocodeRepository;
    }
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $promocodes = $this->promocodeRepository->getAllPromocodes();
        return view('Admin.Promocodes.index', compact('promocodes'));
    }
    public function create()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $cities = ['Damascus', 'Homs', 'Lattakia', 'Aleppo', 'Tartus', 'As-suwayda'];
        $cuisines = Cuisine::get();
        return view('Admin.Promocodes.create', compact('cities', 'cuisines'));
    }
    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $data = $request->all();
        $rules = [
            'code' => 'required|unique:promotionalcodes,code',
            'start_date' => 'required|date|after_or_equal:' . Carbon::today()->format('Y-m-d'),
            'end_date' => 'required|date|after:start_date',
        ];
        $validatedData = $request->validate($rules);
        $cityFilter = $request->input('cities_res');
        $cuisineFilter = $request->input('cuisines');
        $city_us_Filter = $request->input('cities_us');
        $followingsFilter = $request->input('is_followings');
        $reservationsFilter = $request->input('is_reservaions');
        $numRestaurantsFilter = $request->input('num_restaurants');
        $numUsersFilter = $request->input('num_users');
        $num_reservations = $request->input('num_reservations');
        try {
            DB::beginTransaction();
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
            $num_res = $filteredRestaurants->count();

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
            $num_us = $filteredUsers->count();
            $userIds = $filteredUsers->pluck('id');
            $RestaurantsIds = $filteredRestaurants->pluck('id');
            $data = $request->all();
            $data['num_us'] = $num_res;
            $data['num_res'] = $num_us;
            $promocode = Promocode::create([
                'code' => $request->code,
                'discount' => $request->discount,
                'limit' => $request->limit,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_reservaions' => $request->has('is_reservaions') ? 1 : 0,
                'is_followings' => $request->has('is_followings') ? 1 : 0,
                'num_us' => $num_us,
                'num_res' => $num_res,
                'description' => $request->description,
                // 'filter_us'=>$city_us_Filter,
                // 'filter_res'=>$filter_us,
                'users_ids' => $userIds,
            ]);
           
            $promoCodeId = $promocode->id;
            foreach ($filteredUsers as $user) {
                $promocodes = $user->promocodes ?? [];
                if (!in_array($promoCodeId, $promocodes)) {
                    $promocodes[] = $promoCodeId;
                }
                $user->promocodes = $promocodes;
                $user->save();
                
            }
            if ($request->hasFile('image')) {
            $file = $request->file('image');
             $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Promocodes/'), $filename);
               
                $promocode->update([
                    'image' => 'attachments/Promocodes/' . $filename,
                ]);
            }
            $insertData = $RestaurantsIds->map(function ($restaurantId) use ($promocode) {
                return [
                    'restaurant_id' => $restaurantId,
                    'promocode_id' => $promocode->id,
                ];
            })->all();
            
            res_prompcodes::insert($insertData);
            
            switch ($request->input('action')) {
                case 'more_add':
                    DB::commit();
                    return redirect()->route('promocodes.create');
                    break;
                case 'add_and_cancel':
                    DB::commit();
                    return redirect()->route('promocodes.index');
                    break;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }

    public function show(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $promocode = $this->promocodeRepository->findPromocodeById($id);
        return view('Admin.Promocodes.show', compact('promocode'));
    }

    public function destroy(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->promocodeRepository->deletePromocode($id);
        return redirect()->route('promocodes.index');
    }

    public function promocodes_inactive(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->promocodeRepository->updatePromocodeStatus($id, 'inActive');
        return redirect()->route('promocodes.show', $id);
    }
}
