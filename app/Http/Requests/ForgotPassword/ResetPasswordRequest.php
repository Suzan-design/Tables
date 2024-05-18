<?php

namespace App\Http\Requests\ForgotPassword;

use App\Http\Requests\ValidationFormRequest;

class ResetPasswordRequest extends ValidationFormRequest
{

    public function rules(): array
    {
        return [
            'old_password' => 'required | string | min : 8 | max:34 ' ,
            'new_password' => 'required | string | min : 8 | max:34 '
        ];
    }
}
