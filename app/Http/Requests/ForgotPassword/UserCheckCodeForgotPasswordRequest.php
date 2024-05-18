<?php

namespace App\Http\Requests\ForgotPassword;

use App\Http\Requests\ValidationFormRequest;

class UserCheckCodeForgotPasswordRequest extends ValidationFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required |  exists:reset_code_passwords,code',
            'phone_number' => 'required| exists:mobile_users,phone_number',
        ];
    }
}
