<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Validator;

class ForgotPasswordController extends Controller
{

  public function forget_password() {
return view('Verify') ;
}

  public function change_password(Request $request, $user_id) {
    $data = $request->all();
    Validator::make($data, [
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
User::where('id',$user_id)->update(['password'=>Hash::make($request->password)]);
return redirect()->route('login');
}

public function verify(Request $request) {

    $user = User::where('email', $request->email)->first();

    if (is_null($user)) {
        return redirect()->back()->with('error', 'User not found');
    }

    return view('changePassword',['user_id'=>$user->id]);
}

    use SendsPasswordResetEmails;
}
