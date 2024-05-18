<?php

namespace App\Http\Controllers\api;

use App\Services\api\RegisterService;
use App\Http\Requests\Register\LogInRequest;
use App\Http\Requests\Register\SignUpStep1Request;
use App\Http\Requests\Register\SignUpStep2Request;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function SignUpStep1(SignUpStep1Request $request)
    {
        try {
            $user = $this->registerService->signUpStep1($request->all());
            return response()->json(['status' => true, 'user' => $user]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function SignUpStep2(SignUpStep2Request $request)
    {
        try {
            $user = $this->registerService->signUpStep2($request);
            return response()->json(['status' => true, 'user' => $user]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function LogIn(LogInRequest $request)
    {
        try {
            $token = $this->registerService->logIn($request->only('phone_number', 'password'));
            return response()->json(['status' => true, 'token' => $token[0] , 'type' => $token[1] ]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()] ,401 );
        }
    }

    public function LogOut()
    {
        try {
            $this->registerService->logOut();
            return response()->json(['status' => true, 'message' => 'LogOut Successfully']);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }
}
