<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\ForgotPassword\EmailForgotPasswordRequest;
use App\Http\Requests\ForgotPassword\EmailResetPasswordRequest;
use App\Http\Requests\ForgotPassword\UserCheckCodeForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\User\MobileUser;
use App\Models\User\ResetCodePassword;
use App\Services\api\OtpService;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{


    public function userForgotPassword(EmailForgotPasswordRequest $request){

        //Delete all old code that user send before
        ResetCodePassword::query()->where('phone_number',$request['phone_number'])->delete();
        //Generate random code
        $data['code']=mt_rand(1000,9999);

        //Create a new code
        $CodeData=ResetCodePassword::create([
            'code' => $data['code'] ,
            'phone_number' => $request['phone_number']
        ]);

        $otpService = new OtpService() ;
        $otpService->generateOtp($request['phone_number']) ;

        return response()->json([
            'status'=>true,
            'code'=> $data['code']
        ]);
    }

    public function userCheckCode(UserCheckCodeForgotPasswordRequest $request)
    {
        //find the code
        $PasswordReset=ResetCodePassword::query()->where('code',$request['code'])->where('phone_number' , $request['phone_number'])->first();
        if(! $PasswordReset)
        {
            return response()->json([
                'status' =>   false,
                'message' => 'invalid code'
                ]);
        }
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour())
        {
            $PasswordReset->delete();
            return response()->json(['status' => false , 'message'=>trans('password.code_is_expire')],422);
        }
        return response()->json([
            'status'=>true,
            'code' => $PasswordReset['code'],
            'message' => trans('password.code_is_valid')
        ]);
    }

    public function userResetPassword(EmailResetPasswordRequest $request){

        $input = $request->all();
        //find the code
        $PasswordReset=ResetCodePassword::query()->where('code',$request['code'])->where('phone_number' , $request['phone_number'])->first();
        if(! $PasswordReset)
        {
            return response()->json([
                'status'  => false ,
                'message' => 'invalid code'
            ]);
        }
        //check if it is not expired:the time is one hour
        if($PasswordReset['created_at'] > now()->addHour() )
        {
            $PasswordReset->delete();
            return response()->json([ 'status' => false, 'message'=>trans('password code is expire')],422);
        }
        //find users email
        $user = MobileUser::query()->firstWhere('phone_number',$PasswordReset['phone_number']);
        //update user password
        $input['password'] = bcrypt($input['password']);
        $user->update(['password' => $input['password']]);
        //delete current code
        $PasswordReset->delete();

        return response()->json([
            'status'=>true,
            'message' => 'password has been successfully reset']);
    }

}
