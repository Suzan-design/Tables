<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Customer;
class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $roles = Role::pluck('name', 'name')->all();
        $data = $this->userRepository->getAllUsers();
        return view('Admin.Users.index', compact('data', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $input = $request->all();
        $this->userRepository->createUser($input);
        return redirect()->back()->with('success', 'Customer blocked successfully.');
    }
    
    public function delete_user($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }
    
    public function block_user($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $customer = Customer::findOrFail($id);
        $customer->update(['isBlocked'=>true]);
         $customer->tokens()->delete();
        return redirect()->back()->with('success', 'Customer blocked successfully.');
    }
    
    public function Unblock_user($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $customer = Customer::findOrFail($id);
        $customer->update(['isBlocked'=>false]);
        return redirect()->back()->with('success', 'Customer Unblocked successfully.');
    }

    public function show($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $user = $this->userRepository->findUserById($id);
        return view('Admin.Users.show', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $input = $request->all();
        $this->userRepository->updateUser($id, $input);
        return redirect()->route('users.index')
            ->with('success', 'تم تحديث معلومات المستخدم بنجاح');
    }

    public function destroy(Request $request)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $this->userRepository->deleteUser($request->user_id);
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
    public function active($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $user = User::where('id', $id)->update([
            'status' => 'active'
        ]);
    }
    public function in_active($id)
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $user = User::where('id', $id)->update([
            'status' => 'inactive'
        ]);
    }
    public function staff_all()
{
if(auth()->user()->roleName=='staff'){
            abort(403);
        }
    $users = User::where('roleName', 'staff')
    ->select('id', 'name', 'email', 'phone')->has('Restaurant')
    ->with('Restaurant')->get()
    ->map(function ($user) {
        $user->name_restaurant = $user->Restaurant ? $user->Restaurant->name : null;
        $user->id_restaurant = $user->Restaurant ? $user->Restaurant->id : null;
        unset($user->Restaurant);
        return $user;
    });



    return view('Admin.Users.staff', compact('users'));
}
    public function customers()
    {
    if(auth()->user()->roleName=='staff'){
            abort(403);
        }
        $users = Customer::get();
        return view('Admin.Users.customers', compact('users'));
    }


}
