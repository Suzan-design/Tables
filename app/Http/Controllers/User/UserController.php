<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\MobileUser;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function search(Request $request)
    {
        $users = MobileUser::query()
            ->where('first_name', 'like', '%' . $request['Search'] . '%')
            ->orWhere('last_name', 'like', '%' . $request['Search'] . '%')
            ->orWhere('gender', 'like', '%' . $request['Search'] . '%')
            ->orWhere('birth_date', '>', $request['Search'])
            ->orWhere('phone_number', 'like', '%' . $request['Search'] . '%')
            ->orWhere('state', 'like', '%' . $request['Search'] . '%')
            ->paginate(10);
        return view('users.index', ['users' => $users]);
    }



    public function index()
    {
        $users = MobileUser::paginate(30);

        return view('users.index', ['users' => $users]);
    }

    public function changeType($id)
    {
        $user = MobileUser::find($id) ;
        if($user)
        {
            if($user->type == 'normal')
                $user->update([
                    'type' => 'organizer'
                    ]) ;
            else
                $user->update([
                    'type' => 'normal'
                ]) ;
        }
        return redirect()->back();
    }

    public function destroy(MobileUser $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function changeActiveType($id)
    {
        $user = MobileUser::find($id);
        if ($user) {
            $newActiveType = $user->active_type == 'normal' ? 'blocked' : 'normal';
            $user->update(['active_type' => $newActiveType]);
            if ($newActiveType == 'blocked') {
                $user->tokens->each(function ($token, $key) {
                    $token->delete();
                });
            }
        }

        return redirect()->back()->with('success', 'User status updated successfully.');
    }

}
