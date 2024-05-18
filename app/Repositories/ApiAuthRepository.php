<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\activatetokens;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\Invitation;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\InvitationService;
use App\Repositories\Interfaces\ApiAuthRepositoryInterface;

class ApiAuthRepository implements ApiAuthRepositoryInterface
{
    public function findByPhone($phone)
    {
        return Customer::where('phone', $phone)->first();
    }

    public function updateOtp($customer, $otp)
    {
        $customer->update(['otp' => $otp]);
    }

    public function checkCredentials($phone, $password)
    {
        $customer = $this->findByPhone($phone);
        return $customer && Hash::check($password, $customer->password) ? $customer : false;
    }

   
    function generateUniqueCode()
    {
        do {
            $random = Str::random(6);
        } while (Customer::where('invitationCode', $random)->exists());
        return $random;
    }
   
  
   
}
