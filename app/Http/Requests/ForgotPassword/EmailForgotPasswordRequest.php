<?php

namespace App\Http\Requests\ForgotPassword;


use App\Http\Requests\ValidationFormRequest;

class EmailForgotPasswordRequest extends ValidationFormRequest
{

    public function rules(): array
    {
        return [
            'phone_number' => 'required| exists:mobile_users,phone_number',
        ];
    }
}
