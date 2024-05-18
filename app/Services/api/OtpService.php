<?php


namespace App\Services\api;


use App\Models\User\MobileUser;
use App\Models\User\OtpVerificationCode;
use App\Models\User\User;
use Carbon\Carbon;

class OtpService
{
    public function generateOtp($phoneNumber)
    {
        return OtpVerificationCode::create([
            'phone_number' => $phoneNumber,
            'otp' => rand(1234, 9999),
            'expire_at' => Carbon::now()->addMinutes(30)
        ]);
    }

    public function verifyOtp($phone_number, $otp)
    {
        $verificationCode = OtpVerificationCode::where('phone_number', $phone_number)->where('otp', $otp)->first();

        if (!$verificationCode) {
            return ['status' => false, 'message' => 'Your OTP is not correct'];
        }

        if (Carbon::now()->isAfter($verificationCode->expire_at)) {
            $verificationCode->delete();
            return ['status' => false, 'message' => 'Your OTP has been expired'];
        }

        $user = MobileUser::where('phone_number' , $phone_number);
        $user->update(['is_verified' => true]);
        $verificationCode->delete();

        $user = $user->first() ;
        $token = $user->createToken('Api Token')->plainTextToken  ;

        return ['status' => true, 'message' => $token];
    }
}
