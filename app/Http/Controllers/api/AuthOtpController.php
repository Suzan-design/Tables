<?php
namespace App\Http\Controllers\api;

use App\Http\Requests\OtpVerify\generateRequest;
use App\Http\Requests\OtpVerify\OtpVerifyAccountRequest;
use App\Services\api\OtpService;

class AuthOtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function generate(generateRequest $request)
    {
        $verificationCode = $this->otpService->generateOtp($request->phone_number);

        return response()->json([
            'status' => true,
            'message' => "Your OTP To Login is - ".$verificationCode->otp
        ]);
    }

    public function OtpVerifyAccount(OtpVerifyAccountRequest $request)
    {
        $response = $this->otpService->verifyOtp($request->phone_number, $request->otp);

        return response()->json([
            'status' => $response['status'],
            'token' => $response['message']
        ]);
    }

}
