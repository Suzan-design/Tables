<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\MobileUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email| unique:users,email',
            'password' => 'required'
        ])->validate();

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('admin'); // Assign the role

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email ',
            'password' => 'required'
        ])->validate();

        if (!Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Check if the user already exists in your database
        $user = MobileUser::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Create a new user record
            $user = MobileUser::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]);
        }

        // Generate a bearer token for the user
        $token = $user->createToken('api')->plainTextToken;

        // Return the token to the user or perform any desired redirect or response
        return response()->json(['token' => $token]);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('google')->redirect();
    }
}
