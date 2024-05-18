<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Account_Active;
use App\Models\restaurnats_categories;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RestaurantRequest;
use Illuminate\Support\Facades\Validator;

class RestaurantContoller extends Controller
{
    public function index()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $all = Restaurant::with('cuisine', 'staff')->get();
        $status = "active";
        $active = Restaurant::with('cuisine', 'staff')->whereHas('staff', function ($query) use ($status) {
            $query->where('status', 'active');
        })->get();
        $status = "inactive";
        $pending = Restaurant::with('cuisine', 'staff')->whereHas('staff', function ($query) use ($status) {
            $query->where('status', 'inactive');
        })->get();
        return view('Admin.Restaurants.index', compact('all', 'active', 'pending'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $cuisins = Cuisine::all();
        $categories = restaurnats_categories::all();
        $icons = icon::get();
        return view('Admin.Restaurants.create', compact('cuisins', 'icons', 'categories'));
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
    
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|min:2|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'user_phone' => 'required',
            // 'deposit_desc' => 'required|string|min:20|max:200',
            // 'refund_value' => 'required|numeric|min:20|max:200',
            // 'refund_policy' => 'required|string|min:20|max:200',
            // 'change_desc' => 'required|string|min:20|max:200',
            // 'cancellition_policy' => 'required|string|min:20|max:200',
            'Restaurant_name' => 'required|string',
            'services' => 'required',
            'Restaurant_phone' => 'required',
            'description' => 'required|string',
            'ar_description' => 'required|string',
            'Activation_start' => 'required|date|after:today',
            'Activation_end' => 'required|date|after:Activation_start',
            'isFeatured' => 'required',
            'taxes' => 'required'
        ]);
        
        //if ($validator->fails()) {
        //    return redirect()->back()
        //        ->withErrors($validator)
        //        ->withInput();
        //}
        try {
            DB::beginTransaction();
            $selectedIconIds = $request->input('services', []);
            $selectedIcons = Icon::whereIn('id', $selectedIconIds)->get();
            $services = $selectedIcons->pluck('image', 'name')->toArray();
            $ar_services = $selectedIcons->pluck('image', 'ar_name')->toArray();
           
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->email,
                'phone' => $request->user_phone,
                'password' => bcrypt($request->password),
                'roleName' => 'staff',
                'status' => 'active',

            ]);
            
            $Restaurant = Restaurant::create([
                'user_id' => $user->id,
                'cuisine_id' => $request->cuisine_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'ar_description' => $request->ar_description,
                'name' => $request->Restaurant_name,
                'Activation_start' => $request->Activation_start,
                'Activation_end' => $request->Activation_end,
                'phone_number' => $request->Restaurant_phone,
                'deposit' => $request->deposite_value,
                'age_range' => serialize([$request->from, $request->to]),
                'services' => json_encode($services),
                'ar_services' => json_encode($ar_services),
                'Deposite_value' => $request->deposite_value,
                'Deposite_desc' => $request->deposit_desc,
                'ar_Deposite_desc' => $request->ar_Deposite_desc,
                'refund_policy' => $request->refund_value  . ',' . $request->refund_desc,
                'change_policy' => "change",
                'cancellition_policy' => $request->cancellition_value  . ',' . $request->cancellition_desc,
                'ar_refund_policy' => $request->refund_value  . ',' . $request->ar_refund_desc,
                'ar_change_policy' => "change",
                'ar_cancellition_policy' => $request->cancellition_value  . ',' . $request->ar_cancellition_desc,
                'website' => $request->website,
                'instagram' => $request->instagram,
                'isFeatured'=> $request->isFeatured== 'on' ? true :false,
                'taxes' => $request->taxes,
            ]);
            
            


            if ($request->hasfile('craousal')) {
                foreach ($request->file('craousal') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/craousal/'), $filename);
                
                    $images = new Image();
                    $images->filename = 'attachments/Restaurants/craousal/'. $filename;
                    $images->type = 'craousal';
                    $images->imageable_id = $Restaurant->id;
                    $images->imageable_type = 'App\Models\Restaurant';
                    $images->save();
                }
            }
            if ($request->hasfile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/gallery/'), $filename);
                
                    $images = new Image();
                    $images->filename = 'attachments/Restaurants/gallery/'. $filename;
                    $images->type = 'gallery';
                    $images->imageable_id = $Restaurant->id;
                    $images->imageable_type = 'App\Models\Restaurant';
                    $images->save();
                }
            }
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                 $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/logo/'), $filename);
                
                $image = new Image();
                $image->filename = 'attachments/Restaurants/logo/'. $filename;
                $image->imageable_id = $Restaurant->id;
                $image->type = 'logo';
                $image->imageable_type = 'App\Models\Restaurant';
                $image->save();
            }
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                 $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/cover/' . $Restaurant->name . '/'), $filename);
                
                $image = new Image();
                $image->filename = 'attachments/Restaurants/cover/' . $Restaurant->name . '/' . $filename;
                $image->imageable_id = $Restaurant->id;
                $image->type = 'cover';
                $image->imageable_type = 'App\Models\Restaurant';
                $image->save();
            }

            $location = Location::create([
                'Restaurant_id' => $Restaurant->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'state' => $request->state,
                'text' => $request->location,
                'ar_text' => $request->ar_location,
            ]);
            
            DB::commit();
            switch ($request->input('action')) {
                case 'more_add':
                    return redirect()->route('Restaurants.create');
                    break;

                case 'add_and_cancel':
                    return redirect()->route('Restaurants.index');
                    break;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getmessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المطعم');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Restaurant = Restaurant::where('id', $id)->with('cuisine', 'staff', 'menu', 'location')->first();

        return view('Admin.Restaurants.show', compact('Restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $icons = icon::all();
        $categories = restaurnats_categories::all();
        $cuisins = Cuisine::all();
        $restaurant = Restaurant::where('id', $id)->with('cuisine', 'staff', 'menu', 'location')->first();
        $selectedServices = json_decode($restaurant->services, true);

        return view('Admin.Restaurants.edit', compact('restaurant', 'cuisins', 'icons', 'categories', 'selectedServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|min:2|max:30',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $Restaurant = Restaurant::where('id', $id)->first();

            $selectedIconIds = $request->input('services', []);
            $selectedIcons = Icon::whereIn('id', $selectedIconIds)->get();
            $services = $selectedIcons->pluck('image', 'name')->toArray();
            
            $ar_services = $selectedIcons->pluck('image', 'ar_name')->toArray();
            $user = User::where('id', $Restaurant->user_id)->first();
            $user->update($request->all());
            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            $Restaurant->update([
                'cuisine_id' => $request->cuisine_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'ar_description' => $request->ar_description,
                'name' => $request->Restaurant_name,
                'Activation_start' => $request->Activation_start,
                'Activation_end' => $request->Activation_end,
                'phone_number' => $request->Restaurant_phone,
                'deposit' => $request->Deposite_value,
                'age_range' => serialize([$request->from, $request->to]),
                'services' => json_encode($services),
                'ar_services' => json_encode($ar_services),
                'Deposite_value' => $request->Deposite_value,
                'Deposite_desc' => $request->Deposite_desc,
                'ar_Deposite_desc' => $request->ar_Deposite_desc,
                'refund_policy' => $request->refund_value . ',' . $request->refund_desc ,
                'change_policy' => "change",
                'cancellition_policy' => $request->cancellition_value . ',' . $request->cancellition_desc,
                'ar_refund_policy' => $request->refund_value . ',' . $request->ar_refund_desc ,
                'ar_change_policy' => "change",
                'taxes' => $request->taxes,
                'ar_cancellition_policy' => $request->cancellition_value . ',' . $request->ar_cancellition_desc,
                'website' => $request->website,
                'instagram' => $request->instagram,
            ]);
            
            if ($request->hasfile('craousal')) {
                $oldImages = $Restaurant->images()->where('type', 'craousal')->get();
                foreach ($oldImages as $image) {
                    Storage::delete($image->filename); // حذف من الخادم
                    $image->delete(); // حذف من قاعدة البيانات
                }
                foreach ($request->file('craousal') as $file) {
                    
                   $filename = time() . '_' . $file->getClientOriginalName();
                   $file->move(public_path('attachments/Restaurants/MenusItems/icons/'), $filename);
                  
                    $images = new Image();
                    $images->filename = 'attachments/Restaurants/MenusItems/icons/'. $filename;
                    $images->type = 'craousal';
                    $images->imageable_id = $Restaurant->id;
                    $images->imageable_type = 'App\Models\Restaurant';
                    $images->save();
                }
            }
            if ($request->hasfile('gallery')) {
                $oldImages = $Restaurant->images()->where('type', 'gallery')->get();
                foreach ($oldImages as $image) {
                    Storage::delete($image->filename); // حذف من الخادم
                    $image->delete(); // حذف من قاعدة البيانات
                }
                foreach ($request->file('gallery') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                   $file->move(public_path('attachments/Restaurants/gallery/'), $filename);
                  
                    $images = new Image();
                    $images->filename = 'attachments/Restaurants/gallery/'. $filename;
                    $images->type = 'gallery';
                    $images->imageable_id = $Restaurant->id;
                    $images->imageable_type = 'App\Models\Restaurant';
                    $images->save();
                }
            }
            if ($request->hasFile('logo')) {
                $oldImage = $Restaurant->images()->where('type', 'logo')->first();
                Storage::delete($oldImage->filename);
                $oldImage->delete();

                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/logo/'), $filename);
                  
                $image = new Image();
                $image->filename = 'attachments/Restaurants/logo/'. $filename;
                $image->imageable_id = $Restaurant->id;
                $image->type = 'logo';
                $image->imageable_type = 'App\Models\Restaurant';
                $image->save();
            }
            if ($request->hasFile('cover')) {
                $oldImage = $Restaurant->images()->where('type', 'cover')->first();
                Storage::delete($oldImage->filename);
                $oldImage->delete();
                $file = $request->file('cover');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('attachments/Restaurants/cover/'), $filename);
                  
                $image = new Image();
                $image->filename = 'attachments/Restaurants/cover/'. $filename;
                $image->imageable_id = $Restaurant->id;
                $image->type = 'cover';
                $image->imageable_type = 'App\Models\Restaurant';
                $image->save();
            }
            $location = Location::where('Restaurant_id', $Restaurant->id)->first();
            $location->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'state' => $request->state,
                'text' => $request->location,
            ]);
            DB::commit();
            return redirect()->route('Restaurants.index');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getmessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل المطعم');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
     $Restaurant = Restaurant::where('id', $id)->first();
     $user_id = $Restaurant->user_id;
        $user = User::findOrFail($user_id)->delete();
        $Restaurant = Restaurant::where('id', $id)->delete();
        Customer::whereJsonContains('followed_restaurants', $id)->each(function ($customer) use ($id) {
        $followed = $customer->followed_restaurants;

        // Ensure that followed_restaurants is an array
        if (is_array($followed)) {
            $followed = array_diff($followed, [$id]); // Remove the restaurant ID
            $customer->followed_restaurants = array_values($followed); // Re-index the array
            $customer->save();
        }
    });
        return redirect()->route('Restaurants.index');
    }
    public function act_inact__Restaurant($id)
    {
        $Restaurant = Restaurant::find($id);
        $newStatus = $Restaurant->status === 'active' ? 'inactive' : 'active';
        $newAvailability = $Restaurant->availability === 'available' ? 'unavailable' : 'available';
        $Restaurant->update([
            'status' => $newStatus,
            'availability' => $newAvailability
        ]);
        return redirect()->route('Restaurants.index');
    }
    public function Restaurant_reservations(Request $request, $id)
    {
        $date = $request->filled('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $reservations = Reservation::where('Restaurant_id',$id)->where('status','pending')->get();
        $accepted_reservations = Reservation::where('Restaurant_id',$id)->where('status','next')->get();
        $rejected_reservations = Reservation::where('Restaurant_id',$id)->where('status','rejected')->get();
        $cancelled_reservations = Reservation::where('Restaurant_id',$id)->where('status','cancelled')->get();
        $tables = Table::all();
        
        
        return view('Admin.Reservations.index', compact('reservations', 'accepted_reservations', 'rejected_reservations','cancelled_reservations','tables'));
    }
}
