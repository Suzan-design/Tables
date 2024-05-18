<?php

namespace App\Http\Controllers\Restaurant_staff;

use App\Models\Menu;
use App\Models\User;
use App\Models\Table;
use App\Models\Cuisine;
use App\Models\Reviews;
use App\Models\Reaturant;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function staff_statistics()
    {
        $user_id = Auth::id();
        $Restaurant = Restaurant::where('user_id', $user_id)->first();
        $tables = '2';
        $total_price = '2';
        $reserv = '32';
        $customers = '423';
        return view('staff.statistics', compact('tables', 'total_price', 'reserv', 'customers', 'Restaurant'));
    }
    public function staff_profile()
    {
        $user = User::where('id', Auth::id())->first();
        if ($user->role_name == "staff") {
            return view('staff.profile', compact('user'));
        } else {
            return view('Admin.profile', compact('user'));
        }
    }
    public function update_profile(Request $request)
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
}
