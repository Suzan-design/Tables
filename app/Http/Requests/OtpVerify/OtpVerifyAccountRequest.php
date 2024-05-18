<?php

namespace App\Http\Requests\OtpVerify;

use App\Http\Requests\ValidationFormRequest;

class OtpVerifyAccountRequest extends ValidationFormRequest
{

    public function rules(): array
    {
        return [
            'phone_number' => 'required|exists:mobile_users,phone_number',
            'otp' => 'required'
        ];
    }
}
