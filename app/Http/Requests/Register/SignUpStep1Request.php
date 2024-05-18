<?php

namespace App\Http\Requests\Register;

use App\Http\Requests\ValidationFormRequest;
use App\Models\User;

class SignUpStep1Request extends ValidationFormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:2|max:20',
            'last_name' => 'required|string|min:2|max:20',
            'phone_number' => 'required|string|max:10|min:9',
        ];
    }
}
