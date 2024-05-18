<?php

namespace App\Http\Requests\OtpVerify;

use App\Http\Requests\ValidationFormRequest;

class generateRequest extends ValidationFormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required|exists:mobile_users,phone_number'
        ];
    }
}
