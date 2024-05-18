<?php


namespace App\Services\api;


use App\Http\Requests\Register\SignUpStep2Request;
use App\Models\User\MobileUser;
use App\Traits\FileStorageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService
{

    use FileStorageTrait ;

    public function signUpStep1($requestData)
    {
        $user = MobileUser::where('phone_number', $requestData['phone_number'])->first();
        if ($user && !$user->is_verified) {
            $user->update($requestData);
        } elseif ($user && $user->is_verified) {
            if(! $user->is_complete)
                throw new \Exception('please complete your data',403);

            throw new \Exception('Already Exist');
        }else{
            $user = MobileUser::create([
                'first_name' => $requestData['first_name'] ,
                'last_name' => $requestData['last_name'] ,
                'phone_number' => $requestData['phone_number'] ,
            ]);
        }
        $otpGenerator = new OtpService();
        $otpGenerator->generateOtp($requestData['phone_number']);

        return $user ;
    }


    public function signUpStep2(SignUpStep2Request $requestData)
    {
        $imagePath = $requestData->hasFile('image') ?
            $this->storeFile($requestData->file('image'), 'User_images') :
            $requestData->input('image');

        $user =Auth::user() ;
        $user->update([
            'password' => $requestData['password'],
            'gender' =>$requestData['gender'],
            'birth_date' =>$requestData['birth_date'],
            'state' =>$requestData['state'],
            'image' =>$imagePath ,
            'is_complete' => true
        ]);

        return $user ;
    }



    public function logIn($credentials)
    {
        $user = MobileUser::where('phone_number', $credentials['phone_number'])->first();

        if ($user && $user->is_verified && !$user->is_complete) {
            throw new \Exception('please complete your data',403);
        }if ($user && ! $user->is_verified) {
            throw new \Exception('please verify your phone number ',403);
        }
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid Data');
        }if($user && $user->active_type == 'blocked'){
             throw new \Exception('Blocked');
         }

        return [$user->createToken('API TOKEN')->plainTextToken , $user->type];
    }

    public function logOut()
    {
        Auth::guard('mobile')->user()->tokens()->delete();
    }

}
