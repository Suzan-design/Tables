<?php

namespace App\Http\Requests\ForgotPassword;


use App\Http\Requests\ValidationFormRequest;

class EmailResetPasswordRequest extends ValidationFormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|exists:reset_code_passwords,code',
            'password' => 'required',
            'phone_number' => 'required | exists:mobile_users,phone_number'
        ];
    }
}
